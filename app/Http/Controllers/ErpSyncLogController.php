<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\ErpSyncLog;
use App\Models\InvoiceDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class ErpSyncLogController extends Controller
{
    public function index()
    {
        $lastSync = ErpSyncLog::latest()->where('company_id', Auth::user()->company->company_id)->first();
        $logs = ErpSyncLog::latest()->where('company_id', Auth::user()->company->company_id)->take(10)->get();
        $lastUpdate = $lastSync ? Carbon::parse($lastSync->synced_at)->format('d M Y H:i') : 'N/A';
        $dataSynced = [
            'invoices' => Invoice::count(),
            'customers' => Customer::count(),
            'payments' => 0, // agar payments table hai to uska count
            'last_update' => $lastSync ? Carbon::parse($lastSync->synced_at)->format('d M Y H:i') : 'N/A',
        ];

        return view('erp_sync.index', compact('logs', 'dataSynced', 'lastSync', 'lastUpdate'));
    }
    public function sync()
    {
        $old_company_id = Auth::user()->company->company_id;
        $company_id = Auth::user()->company->id;
        if (!$old_company_id) {
            return response()->json(['success' => false, 'message' => 'company_id required'], 400);
        }

        $startTime = microtime(true);

        DB::beginTransaction();
        try {
            $api_url = env('CORE_BASE_URL') . '/erp_sync.php?company_id=' . $old_company_id;

            // Hit Core ERP API
            $response = Http::get($api_url);
            if (!$response->ok()) {
                return response()->json(['success' => false, 'message' => 'Failed to fetch ERP data'], 500);
            }

            $erpData = $response->json();
            $customers = $erpData['data']['customers'] ?? [];
            $invoices  = $erpData['data']['invoices'] ?? [];

            $logs = [
                'customers_added' => 0,
                'customers_updated' => 0,
                'invoices_added' => 0,
                'invoices_updated' => 0,
                'invoice_items_added' => 0,
                'invoice_items_updated' => 0
            ];

            // --------- Customers ----------
            foreach ($customers as $c) {
                $customer = Customer::updateOrCreate(
                    ['code' => $c['code'], 'company_id' => $company_id],
                    [
                        'name' => $c['name'],
                        'commercial_name' => $c['commercial_name'],
                        'address' => $c['address'],
                        'phone' => $c['phone'],
                        'nit' => $c['nit'],
                        'email' => $c['email'],
                        'credit_limit' => $c['credit_limit'],
                        'credit_days' => $c['credit_days'],
                        'old_company_id' => $c['old_company_id'],
                        'company_id' => $company_id,
                        'createdby_id' => Auth::user()->id
                    ]
                );

                if ($customer->wasRecentlyCreated) {
                    $logs['customers_added']++;
                } elseif (count(array_diff_key($customer->getChanges(), ['updated_at' => 1, 'createdby_id' => 1])) > 0) {
                    $logs['customers_updated']++;
                }
            }

            // --------- Invoices ----------
            foreach ($invoices as $inv) {
                $invoice_items = $inv['invoice_items'] ?? [];
                unset($inv['invoice_items']);

                $inv['company_id'] = $company_id;
                $inv['createdby_id'] = Auth::user()->id;

                $invoice = Invoice::updateOrCreate(
                    ['old_invoice_id' => $inv['old_invoice_id'], 'company_id' => $company_id],
                    $inv
                );

                if ($invoice->wasRecentlyCreated) {
                    $logs['invoices_added']++;
                } elseif (count(array_diff_key($invoice->getChanges(), ['updated_at' => 1, 'createdby_id' => 1])) > 0) {
                    $logs['invoices_updated']++;
                }

                // --------- Invoice Items ----------
                foreach ($invoice_items as $item) {
                    $item['invoice_id'] = $invoice->id; // link to new invoice
                    $invoiceItem = InvoiceDetail::updateOrCreate(
                        ['old_invoice_detail_id' => $item['old_invoice_detail_id'], 'invoice_id' => $invoice->id],
                        $item
                    );

                    if ($invoiceItem->wasRecentlyCreated) {
                        $logs['invoice_items_added']++;
                    } elseif (count(array_diff_key($invoiceItem->getChanges(), ['updated_at' => 1, 'createdby_id' => 1])) > 0) {
                        $logs['invoice_items_updated']++;
                    }
                }
            }

            // --------- Create ERP Sync Log ----------
            $log = ErpSyncLog::create([
                'company_id' => $company_id,
                'customers_added' => $logs['customers_added'],
                'customers_updated' => $logs['customers_updated'],
                'invoices_added' => $logs['invoices_added'],
                'invoices_updated' => $logs['invoices_updated'],
                'invoice_items_added' => $logs['invoice_items_added'],
                'invoice_items_updated' => $logs['invoice_items_updated'],
                'synced_at' => now(),
                'createdby_id' => Auth::user()->id,

            ]);

            DB::commit();

            $duration = round(microtime(true) - $startTime, 2) . 's';

            // Return data for Blade table
            return response()->json([
                'success' => true,
                'log' => [
                    'timestamp' => $log->synced_at,
                    'type' => 'Auto Sync',
                    'invoices' => $logs['invoices_added'] + $logs['invoices_updated'],
                    'customers' => $logs['customers_added'] + $logs['customers_updated'],
                    'duration' => $duration,
                    'status' => 'Success'
                ]
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'log' => [
                    'timestamp' => now(),
                    'type' => 'Auto Sync',
                    'invoices' => 0,
                    'customers' => 0,
                    'duration' => round(microtime(true) - $startTime, 2) . 's',
                    'status' => 'Failed'
                ],
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
