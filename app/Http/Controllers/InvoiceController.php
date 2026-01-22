<?php

namespace App\Http\Controllers;

use App\Models\CustomerInvoice;
use App\Models\InvoiceReminderLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company->id;
        $invoices = CustomerInvoice::with('customer')->where('company_id', $company_id)->get();
        return view('invoice.index', compact('invoices'));
    }

    public function detail($invoice_id)
    {
        $company_id = Auth::user()->company->id;
        $invoice = CustomerInvoice::with('customer')
            ->where('id', $invoice_id)
            ->where('company_id', $company_id)
            ->firstOrFail();

        $balance = $invoice->balance_amount ?? 0;
        $dueDate = $invoice->due_date;
        $daysOverdue = $dueDate && $balance > 0 && $dueDate->lt(now())
            ? $dueDate->diffInDays(now())
            : 0;
        $status = 'Pending';
        if ($balance <= 0) $status = 'Paid';
        elseif ($daysOverdue > 0) $status = 'Overdue';
        $invoice = [
            'id' => $invoice->id,
            'document_number' => $invoice->document_number,
            'customer' => !empty($invoice->customer->name) ? $invoice->customer->name : $invoice->customer->commercial_name,
            'issue_date' => $invoice->issue_date?->format('d M Y'),
            'due_date' => $invoice->due_date?->format('d M Y'),
            'amount' => $invoice->total_amount,
            'balance' => $invoice->balance_amount,
            'amount_paid' => $invoice->total_amount - $invoice->balance_amount,
            'payment_method' => $invoice->payment_type,
            'days_overdue' => $daysOverdue,
            'status' => $status,
            'auto_reminders_sent' => $invoice->auto_reminders_sent ?? 0, // if exists
        ];
        // Fetch reminder logs for this invoice
        $logs = InvoiceReminderLog::where('customer_invoice_id', $invoice_id)
            ->orderByDesc('sent_at')
            ->get();

        // Map to blade-friendly array
        $reminderHistory = $logs->map(function ($log) {

            // Rule = reminder_type
            $rule = ucfirst(str_replace('_', ' ', $log->reminder_type));

            // Status
            $status = $log->message_sent ? 'Read' : 'Pending';

            return [
                'date' => $log->sent_at ? $log->sent_at : '-',
                'rule' => $rule,
                'status' => $status,
            ];
        })->toArray();

        return view('invoice.detail', compact('invoice', 'reminderHistory'));
    }
}
