<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\InvoiceReminderLog;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * Display a list of customers with balances and overdue amounts.
     */
    public function index()
    {
        $company_id = Auth::user()->company->id;

        $customers = Customer::with(['invoices' => function ($q) {
            $q->select(
                'id',
                'customer_id',
                'document_number',
                'issue_date',
                'due_date',
                'total_amount',
                'balance_amount'
            );
        }])
            ->where('company_id', $company_id)
            ->get()
            ->map(function ($customer) {
                $balance = $customer->invoices->sum(fn($inv) => $inv->balance_amount ?? 0);
                $overdue = $customer->invoices->filter(
                    fn($inv) => ($inv->balance_amount ?? 0) > 0 && $inv->due_date && $inv->due_date < now()
                )->sum(fn($inv) => $inv->balance_amount ?? 0);

                return [
                    'id' => $customer->id,
                    'customer_code' => $customer->code,
                    'name' => !empty($customer->name) ? $customer->name : $customer->commercial_name,
                    'phone' => $customer->phone,
                    'balance' => $balance,
                    'overdue' => $overdue,
                    'invoices_count' => $customer->invoices->count(),
                ];
            });
        return view('customer.index', compact('customers'));
    }

    /**
     * Display customer detail with invoices.
     */
    public function detail($customer_id)
    {
        $company_id = Auth::user()->company->id;

        $customer = Customer::with(['invoices' => function ($q) {
            $q->select(
                'id',
                'customer_id',
                'document_number',
                'issue_date',
                'due_date',
                'total_amount',
                'balance_amount'
            )->orderBy('issue_date', 'desc');
        }])
            ->where('company_id', $company_id)
            ->where('id', $customer_id)
            ->firstOrFail();

        $customer = [
            'customer_code' => $customer->code,
            'name' => !empty($customer->name) ? $customer->name : $customer->commercial_name,
            'phone' => $customer->phone,
            'balance' => $customer->invoices->sum(fn($inv) => $inv->balance_amount ?? 0),
            'overdue' => $customer->invoices->filter(
                fn($inv) => ($inv->balance_amount ?? 0) > 0 && $inv->due_date && $inv->due_date < now()
            )->sum(fn($inv) => $inv->balance_amount ?? 0),
            'invoices' => $customer->invoices->map(function ($inv) {
                $balance = $inv->balance_amount ?? 0;
                $due_date = $inv->due_date;
                $status = $balance <= 0 ? 'Paid' : ($due_date && $due_date < now() ? 'Overdue' : 'Pending');
                $days_overdue = $status === 'Overdue' && $due_date
                    ? now()->diffInDays($due_date)
                    : 0;

                return [
                    'document_number' => $inv->document_number,
                    'issue_date' => $inv->issue_date?->format('d M Y') ?? '-',
                    'due_date' => $inv->due_date?->format('d M Y') ?? '-',
                    'amount' => $inv->total_amount ?? 0,
                    'balance' => $balance,
                    'status' => $status,
                    'days_overdue' => $days_overdue,
                ];
            })->toArray(),
        ];

        // Fetch logs for this customer only
        $history = InvoiceReminderLog::where('customer_id', $customer_id)
            ->orderByDesc('sent_at')
            ->limit(50)
            ->get()
            ->map(function ($log) {

                $message = '';
                if ($log->request_payload) {
                    $payload = json_decode($log->request_payload, true);
                    $message = $payload['message'] ?? '';
                }

                // Determine type
                $type = $log->message_sent ? 'sent' : 'received';

                return [
                    'message' => $message,
                    'date' => $log->sent_at??'-',
                    'type' => $type,
                ];
            });

        return view('customer.detail', compact('customer','history'));
    }
}
