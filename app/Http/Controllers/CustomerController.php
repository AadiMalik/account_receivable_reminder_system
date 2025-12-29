<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;

        $customers = Customer::with(['invoices' => function ($q) {
            $q->select('id', 'customer_id', 'total_amount', 'paid_amount', 'due_date');
        }])
            ->where('company_id', $company_id)
            ->get()
            ->map(function ($customer) {
                $balance = $customer->invoices->sum(fn($inv) => $inv->total_amount - $inv->paid_amount);
                $overdue  = $customer->invoices->filter(fn($inv) => ($inv->total_amount - $inv->paid_amount) > 0 && $inv->due_date < now())->sum(fn($inv) => $inv->total_amount - $inv->paid_amount);
                return [
                    'customer_code' => $customer->code,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'balance' => $balance,
                    'overdue' => $overdue,
                    'invoices' => $customer->invoices->count(),
                ];
            });

        return view('customer.index', compact('customers'));
    }

    public function detail($customer_code)
    {
        $company_id = Auth::user()->company->company_id;

        $customer = Customer::with(['invoices' => function ($q) {
            $q->select('id', 'customer_id', 'number', 'issue_date', 'due_date', 'total_amount', 'paid_amount')
                ->orderBy('issue_date', 'desc');
        }])->where('company_id', $company_id)
            ->where('code', $customer_code)
            ->firstOrFail();

        $customerData = [
            'customer_code' => $customer->code,
            'name' => $customer->name,
            'phone' => $customer->phone,
            'balance' => $customer->invoices->sum(fn($inv) => $inv->total_amount - $inv->paid_amount),
            'overdue' => $customer->invoices->filter(fn($inv) => ($inv->total_amount - $inv->paid_amount) > 0 && $inv->due_date < now())->sum(fn($inv) => $inv->total_amount - $inv->paid_amount),
            'invoices' => $customer->invoices->map(function ($inv) {
                $status = ($inv->total_amount - $inv->paid_amount) > 0 && $inv->due_date < now()
                    ? 'Overdue'
                    : 'Paid';
                $days_overdue = $status === 'Overdue'
                    ? now()->diffInDays($inv->due_date)
                    : 0;
                return [
                    'number' => $inv->number,
                    'issue_date' => $inv->issue_date->format('d M Y'),
                    'due_date' => $inv->due_date->format('d M Y'),
                    'amount' => $inv->total_amount - $inv->paid_amount,
                    'status' => $status,
                    'days_overdue' => $days_overdue,
                ];
            })->toArray(),
        ];

        return view('customer.detail', ['customer' => $customerData]);
    }
}
