<?php

namespace App\Http\Controllers;

use App\Models\CustomerInvoice;
use Illuminate\Http\Request;
use App\Models\InvoiceReminderLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class InvoiceReminderLogController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company->id;

        // Fetch messages for this company
        $messages = InvoiceReminderLog::with(['customer', 'invoice'])
            ->where('company_id', $company_id)
            ->orderBy('sent_at', 'desc')
            ->get();

        // Stats
        $totalMessages = $messages->count();
        $sentToday = $messages->where('sent_at', '>=', now()->startOfDay())->count();
        $unreadCount = $messages->where('whatsapp_exists', true)->whereNull('message_sent')->count();
        $invoices = CustomerInvoice::with('customer')->where('company_id', $company_id)->get();

        return view('whatsapp.index', compact('messages', 'totalMessages', 'sentToday', 'unreadCount', 'invoices'));
    }

    public function sendWhatsAppMessage(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:customer_invoices,id',
            'reminder_type'    => 'required|string',
            'customer_name'    => 'required|string',
            'customer_phone'    => 'required|string',
            'message'    => 'required|string',
        ]);

        $invoice = CustomerInvoice::with(['customer', 'company'])
            ->findOrFail($request->invoice_id);

        // message temporarily attach kar do invoice pe
        $invoice->customer_name = $request->customer_name;
        $invoice->customer_phone = $request->customer_phone;
        $invoice->custom_message = $request->message;
        $this->processReminder($invoice, $request->reminder_type);

        return response()->json([
            'status' => true,
            'message' => 'Reminder process initiated'
        ]);
    }

    private function processReminder($invoice, $type)
    {
        $company  = Auth::user()->company;
        $customer = $invoice->customer;

        $log = InvoiceReminderLog::create([
            'company_id'     => $invoice->company_id,
            'customer_invoice_id' => $invoice->id,
            'customer_id'    => $customer?->id,
            'reminder_type'  => $type,
            'customer_phone' => $invoice?->customer_phone,
            'request_payload' => json_encode([
                'invoice_id' => $invoice->id,
                'type' => $type
            ])
        ]);

        if (!$customer || !$invoice->customer_phone) {
            $log->update(['error_message' => 'Customer phone missing']);
            return;
        }

        if (
            !$company ||
            !$company->green_api_instance ||
            !$company->green_api_token
        ) {
            $log->update(['error_message' => 'Company whatsapp configuration missing']);
            return;
        }

        try {
            $exists = $this->checkNumber(
                $company->green_api_instance,
                $company->green_api_token,
                $invoice->customer_phone
            );

            $log->update(['whatsapp_exists' => $exists]);

            if (!$exists) {
                return;
            }

            $message = $invoice->custom_message
                ?? "Dear {$invoice->customer_name}, your invoice {$invoice->document_number} amount {$invoice->balance_amount} is pending.";

            // ✅ send message
            $response = $this->sendMessage(
                $company->green_api_instance,
                $company->green_api_token,
                $invoice->customer_phone,
                $message
            );

            $log->update([
                'message_sent'        => true,
                'message'             => $message,
                'whatsapp_message_id' => $response['idMessage'] ?? null,
                'response_payload'    => json_encode($response),
                'sent_at'             => now(),
            ]);
        } catch (\Throwable $e) {
            $log->update([
                'message_sent' => false,
                'error_message' => $e->getMessage()
            ]);
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

            return false;
        }
    }
    private function sendMessage($instance, $token, $phone, $message)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        $url = "https://api.green-api.com/waInstance{$instance}/sendMessage/{$token}";

        $payload = [
            'chatId' => $phone . '@c.us',
            'message' => $message
        ];

        $response = Http::timeout(20)->post($url, $payload);

        if (!$response->successful()) {
            throw new \Exception('Failed to send message: HTTP ' . $response->status());
        }

        $data = $response->json();

        return [
            'idMessage' => $data['idMessage'] ?? null,
            'sent' => $data['sent'] ?? false,
            'request_payload' => $payload,
            'response_payload' => $data
        ];
    }
}
