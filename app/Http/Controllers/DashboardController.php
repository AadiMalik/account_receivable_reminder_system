<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;

        // Total Receivables (all invoices minus paid)
        $total_receivables = Invoice::where('company_id', $company_id)
            ->sum('paid_amount');

        // Overdue Amount
        $overdue_amount = Invoice::where('company_id', $company_id)
            ->whereRaw('total_amount - paid_amount > 0')
            ->whereDate('due_date', '<', Carbon::today())
            ->sum(DB::raw('total_amount - paid_amount'));

        // Total Customers
        $total_customers = Customer::where('company_id', $company_id)->count();

        // Overdue Invoices
        $overdue_invoices = Invoice::where('company_id', $company_id)
            ->whereRaw('total_amount - paid_amount > 0')
            ->whereDate('due_date', '<', Carbon::today())
            ->count();

        // Top Overdue Accounts
        $top_overdue_accounts = Invoice::selectRaw('company_id, SUM(total_amount - paid_amount) as amount, MAX(DATEDIFF(CURDATE(), due_date)) as days')
            ->where('company_id', $company_id)
            ->whereRaw('total_amount - paid_amount > 0')
            ->groupBy('company_id')
            ->orderByDesc('amount')
            ->limit(5)
            ->with('company:id,name') // eager load customer
            ->get()
            ->map(function ($inv) {
                return [
                    'customer' => $inv->company->name ?? 'N/A',
                    'amount' => $inv->amount,
                    'days' => $inv->days
                ];
            });

        $recent_activities = [];

        return view('dashboard', [
            'total_receivables' => $total_receivables,
            'overdue_amount' => $overdue_amount,
            'total_customers' => $total_customers,
            'overdue_invoices' => $overdue_invoices,
            'top_overdue_accounts' => $top_overdue_accounts,
            'recent_activities' => $recent_activities,
        ]);
    }
}
