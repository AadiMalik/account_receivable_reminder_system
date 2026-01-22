<?php

namespace App\Http\Controllers;

use App\Models\CustomerInvoice;
use Illuminate\Http\Request;
use App\Models\InvoiceReminderLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'invoice_id'     => 'required|exists:customer_invoices,id',
            'reminder_type'  => 'required|string',
            'customer_name'  => 'required|string',
            'customer_phone' => 'required|string',
            'message'        => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'type'    => 'validation',
                'message' => $validator->errors()->all()
            ], 422);
        }

        $invoice = CustomerInvoice::with(['customer', 'company'])
            ->find($request->invoice_id);

        $invoice->customer_name  = $request->customer_name;
        $invoice->customer_phone = $request->customer_phone;
        $invoice->custom_message = $request->message;

        // 🔥 RESULT RECEIVE
        $result = $this->processReminder($invoice, $request->reminder_type);

        if (!$result['status']) {
            return response()->json($result, 400);
        }

        return response()->json([
            'status'  => true,
            'message' => 'WhatsApp message sent successfully'
        ]);
    }


    private function processReminder($invoice, $type)
    {
        $company  = Auth::user()->company;
        $customer = $invoice->customer;

        if (!$customer || !$invoice->customer_phone) {
            return [
                'status'  => false,
                'message' => 'Customer phone number is missing'
            ];
        }

        if (
            !$company ||
            !$company->green_api_instance ||
            !$company->green_api_token
        ) {
            return [
                'status'  => false,
                'message' => 'Company WhatsApp configuration is missing'
            ];
        }

        try {

            $exists = $this->checkNumber(
                $company->green_api_instance,
                $company->green_api_token,
                $invoice->customer_phone
            );

            if (!$exists) {
                return [
                    'status'  => false,
                    'message' => 'This number is not registered on WhatsApp'
                ];
            }

            $message = $invoice->custom_message
                ?? "Dear {$invoice->customer_name}, your invoice {$invoice->document_number} amount {$invoice->balance_amount} is pending.";

            $this->sendMessage(
                $company->green_api_instance,
                $company->green_api_token,
                $invoice->customer_phone,
                $message
            );

            return [
                'status'  => true
            ];
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'WhatsApp API error: ' . $e->getMessage()
            ];
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
