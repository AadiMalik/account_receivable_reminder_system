<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\CustomerInvoice;
use App\Models\ErpSyncLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class ErpSyncLogController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company->id;

        $lastSync = ErpSyncLog::where('company_id', $companyId)->latest()->first();
        $logs = ErpSyncLog::where('company_id', $companyId)->latest()->take(10)->get();

        $lastUpdate = $lastSync
            ? Carbon::parse($lastSync->synced_at)->format('d M Y H:i')
            : 'N/A';

        $dataSynced = [
            'customers'   => Customer::where('company_id', $companyId)->count(),
            'invoices'    => CustomerInvoice::where('company_id', $companyId)->count(),
            'last_update' => $lastUpdate,
        ];

        return view('erp_sync.index', compact('logs', 'dataSynced', 'lastSync', 'lastUpdate'));
    }

    public function sync()
    {
        $old_company_id = Auth::user()->company->company_id; // ERP company
        $company_id     = Auth::user()->company->id;         // Local company

        $startTime = microtime(true);

        DB::beginTransaction();
        try {

            $api_url = env('CORE_BASE_URL') . '/erp_sync.php?company_id=' . $old_company_id;
            $response = Http::get($api_url);

            if (!$response->ok()) {
                throw new Exception('Failed to fetch ERP data');
            }

            $erpData  = $response->json();
            $customers = $erpData['data'] ?? [];

            $logs = [
                'customers_added'   => 0,
                'customers_updated' => 0,
                'invoices_added'    => 0,
                'invoices_updated'  => 0,
            ];

            foreach ($customers as $c) {

                $customer = Customer::updateOrCreate(
                    [
                        'code'       => trim($c['customer_code']),
                        'company_id' => $company_id,
                    ],
                    [
                        'name'            => $c['name'],
                        'commercial_name' => $c['commercial_name'],
                        'email'           => $c['email'],
                        'nit'             => $c['nit'],
                        'address'         => $c['address'],
                        'phone'           => $c['phone'],
                        'credit_limit'    => $c['credit_limit'],
                        'credit_days'     => $c['credit_days'],
                        'old_company_id'  => $c['old_company_id'],
                        'createdby_id'    => Auth::user()->id,
                        'updatedby_id'    => Auth::user()->id,
                    ]
                );

                $customer->wasRecentlyCreated
                    ? $logs['customers_added']++
                    : ($customer->wasChanged() ? $logs['customers_updated']++ : null);

                foreach ($c['invoices'] ?? [] as $inv) {

                    $invoice = CustomerInvoice::updateOrCreate(
                        [
                            'company_id'      => $company_id,
                            'document_type'   => $inv['document_type'],
                            'document_series' => $inv['document_series'],
                            'document_number' => $inv['document_number'],
                        ],
                        [
                            'customer_id'         => $customer->id,
                            'customer_code'       => $c['customer_code'],
                            'issue_date'          => $inv['issue_date'],
                            'total_amount'        => $inv['total_amount'],
                            'balance_amount'      => $inv['balance_amount'],
                            'credit_days'         => $inv['credit_days'],
                            'due_date'            => $inv['due_date'],
                            'payment_type'        => $inv['payment_type'],
                            'concept'             => $inv['concept'],
                            'erp_uuid'            => $inv['erp_uuid'],
                            'erp_series'          => $inv['erp_series'],
                            'erp_document_number' => $inv['erp_document_number'],
                            'old_company_id'      => $inv['old_company_id'],
                            'createdby_id'        => Auth::user()->id,
                            'updatedby_id'        => Auth::user()->id,
                        ]
                    );

                    $invoice->wasRecentlyCreated
                        ? $logs['invoices_added']++
                        : ($invoice->wasChanged() ? $logs['invoices_updated']++ : null);
                }
            }

            $log = ErpSyncLog::create([
                'company_id'        => $company_id,
                'customers_added'   => $logs['customers_added'],
                'customers_updated' => $logs['customers_updated'],
                'invoices_added'    => $logs['invoices_added'],
                'invoices_updated'  => $logs['invoices_updated'],
                'synced_at'         => now(),
                'createdby_id'      => Auth::user()->id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'log' => [
                    'timestamp' => $log->synced_at,
                    'customers' => $logs['customers_added'] + $logs['customers_updated'],
                    'invoices'  => $logs['invoices_added'] + $logs['invoices_updated'],
                    'duration'  => round(microtime(true) - $startTime, 2) . 's',
                    'status'    => 'Success',
                ],
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
