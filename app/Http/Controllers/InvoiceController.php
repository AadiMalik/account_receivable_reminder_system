<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company->company_id;

        $url = env('CORE_BASE_URL') . '/get_invoices.php?company_id=' . $company_id;
        $json = file_get_contents($url);
        $data = json_decode($json, true);

        $invoices = $data['data'] ?? [];
        return view('invoice.index', compact('invoices'));
    }

    public function detail($invoice_id)
    {
        $company_id = Auth::user()->company->company_id;

        $url = env('CORE_BASE_URL') . '/get_invoice_detail.php?company_id=' . $company_id . '&invoice_id=' . $invoice_id;
        $json = file_get_contents($url);
        $data = json_decode($json, true);

        if (!$data || empty($data['data'])) {
            abort(404, 'Invoice not found');
        }

        $invoice = $data['data'];

        return view('invoice.detail', compact('invoice'));
    }
}
