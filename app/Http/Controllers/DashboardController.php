<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\CustomerInvoice;
use App\Models\InvoiceReminderLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company->id;
        $total_receivables = CustomerInvoice::where('company_id', $company_id)
            ->where('balance_amount', 0)
            ->sum('total_amount');
        $overdue_amount = CustomerInvoice::where('company_id', $company_id)
            ->where('balance_amount', '>', 0)
            ->whereDate('due_date', '<', Carbon::today())
            ->sum('balance_amount');
        $total_customers = Customer::where('company_id', $company_id)->count();
        $overdue_invoices = CustomerInvoice::where('company_id', $company_id)
            ->where('balance_amount', '>', 0)
            ->whereDate('due_date', '<', Carbon::today())
            ->count();
        $top_overdue_accounts = CustomerInvoice::selectRaw('
                customer_id,
                SUM(balance_amount) as amount,
                MAX(DATEDIFF(CURDATE(), due_date)) as days
            ')
            ->where('company_id', $company_id)
            ->where('balance_amount', '>', 0)
            ->whereDate('due_date', '<', Carbon::today())
            ->groupBy('customer_id')
            ->orderByDesc('amount')
            ->limit(5)
            ->with('customer:id,name')
            ->get()
            ->map(function ($inv) {
                return [
                    'customer' => !empty($inv->customer->name) ? $inv->customer->name : $inv->customer->commercial_name,
                    'amount'   => $inv->amount,
                    'days'     => $inv->days,
                ];
            });

        $recent_activities = InvoiceReminderLog::with('customer')
            ->where('company_id', $company_id)
            ->orderByDesc('sent_at')
            ->limit(5)
            ->get()
            ->map(function ($log) {
                return [
                    'customer' => $log->customer?->name ?? $log->customer?->commercial_name ?? 'Unknown',
                    'message'  => $log->message ? $log->error_message ?? '' : '',
                    'time'     => $log->sent_at ? $log->sent_at : '-',
                    'type'     => $log->message_sent ? 'sent' : 'failed',
                ];
            });

        return view('dashboard', compact(
            'total_receivables',
            'overdue_amount',
            'total_customers',
            'overdue_invoices',
            'top_overdue_accounts',
            'recent_activities'
        ));
    }
}
