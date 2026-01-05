<?php

namespace App\Console\Commands;

use App\Models\CustomerInvoice;
use App\Models\InvoiceReminderLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendInvoiceReminderCron extends Command
{
    protected $signature = 'invoice:reminders';
    protected $description = 'Send WhatsApp invoice reminders';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = now()->toDateString();

        CustomerInvoice::with(['company', 'customer', 'reminderLogs'])
            ->where('balance_amount', '>', 0)
            ->whereNotNull('due_date')
            ->whereHas('company', function ($q) {
                $q->where('green_active', 1)
                    ->where('status', 1);
            })
            ->chunk(50, function ($invoices) use ($today) {

                foreach ($invoices as $invoice) {

                    $type = $this->detectReminderType($invoice, $today);

                    if (!$type) {
                        continue;
                    }

                    $this->processReminder($invoice, $type);
                }
            });

        $this->info('Invoice reminder cron executed.');
    }

    private function detectReminderType($invoice, $today)
    {
        $company = $invoice->company;
        $due     = $invoice->due_date->copy();

        $map = [
            'before_due'   => $company->before_due
                ? $due->copy()->subDays($company->before_due)->toDateString()
                : null,

            'on_due'       => $company->on_due
                ? $due->toDateString()
                : null,

            'after_due_1'  => $company->after_due_1
                ? $due->copy()->addDays($company->after_due_1)->toDateString()
                : null,

            'after_due_2'  => $company->after_due_2
                ? $due->copy()->addDays($company->after_due_2)->toDateString()
                : null,
        ];

        foreach ($map as $type => $date) {

            if ($date !== $today) {
                continue;
            }

            // max reminders
            if (
                $company->max_reminders &&
                $invoice->reminderLogs->count() >= $company->max_reminders
            ) {
                return null;
            }

            // duplicate block
            if ($invoice->reminderLogs
                ->where('reminder_type', $type)
                ->count()
            ) {
                return null;
            }

            return $type;
        }

        return null;
    }
    private function processReminder($invoice, $type)
    {
        $company  = $invoice->company;
        $customer = $invoice->customer;

        $log = InvoiceReminderLog::create([
            'company_id'     => $invoice->company_id,
            'invoice_id'     => $invoice->id,
            'customer_id'    => $customer?->id,
            'reminder_type'  => $type,
            'customer_phone' => $customer?->phone,
        ]);

        if (!$customer || !$customer->phone) {
            $log->update(['error_message' => 'Customer phone missing']);
            return;
        }
        if (!$company || !$company->green_api_instance || !$company->green_api_token) {
            $log->update(['error_message' => 'Company whatsapp configuration missing']);
            return;
        }

        try {
            // ✅ check number
            $exists = $this->checkNumber(
                $company->green_api_instance,
                $company->green_api_token,
                $customer->phone
            );

            $log->update(['whatsapp_exists' => $exists]);

            if (!$exists) {
                return;
            }

            $message = "Dear {$customer->name}, your invoice "
                . "{$invoice->document_number} amount "
                . "{$invoice->balance_amount} is pending.";

            // ✅ send message
            $response = $this->sendMessage(
                $company->green_api_instance,
                $company->green_api_token,
                $customer->phone,
                $message
            );

            $log->update([
                'message_sent' => true,
                'whatsapp_message_id' => $response['idMessage'] ?? null,
                'response_payload' => $response,
                'sent_at' => now(),
            ]);
        } catch (\Throwable $e) {
            $log->update(['error_message' => $e->getMessage()]);
        }
    }

    private function checkNumber($instance, $token, $phone)
    {
        try {

            // phone format clean (92300xxxxxxx)
            $phone = preg_replace('/[^0-9]/', '', $phone);

            $url = "https://api.green-api.com/waInstance{$instance}/checkWhatsapp/{$token}";

            $response = Http::timeout(20)->post($url, [
                'phoneNumber' => $phone
            ]);

            if (!$response->successful()) {
                return false;
            }

            $data = $response->json();

            /*
            Green API response example:
            {
              "existsWhatsapp": true
            }
        */

            return isset($data['existsWhatsapp']) && $data['existsWhatsapp'] === true;
        } catch (\Throwable $e) {

            // optional: log laravel error log
            Log::error('Green API checkNumber error', [
                'error' => $e->getMessage(),
                'phone' => $phone ?? null,
            ]);

            return false;
        }
    }

    private function sendMessage($instance, $token, $phone, $message)
    {
        try {
            $phone = preg_replace('/[^0-9]/', '', $phone);

            $url = "https://api.green-api.com/waInstance{$instance}/sendMessage/{$token}";

            $payload = [
                'chatId' => $phone . '@c.us', // Green API format
                'message' => $message
            ];

            $response = Http::timeout(20)->post($url, $payload);

            if (!$response->successful()) {
                throw new \Exception('Failed to send message: HTTP ' . $response->status());
            }

            $data = $response->json();

            return [
                'idMessage' => $data['idMessage'] ?? null,
                'status' => $data['sent'] ?? false,
                'request_payload' => $payload,
                'response_payload' => $data
            ];
        } catch (\Throwable $e) {
            // Optional: log for debugging
            Log::error('Green API sendMessage error', [
                'error' => $e->getMessage(),
                'phone' => $phone ?? null,
                'message' => $message
            ]);

            throw $e;
        }
    }
}
