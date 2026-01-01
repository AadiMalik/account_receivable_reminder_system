<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerInvoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'document_type',
        'document_number',
        'document_series',
        'customer_code',
        'issue_date',
        'total_amount',
        'balance_amount',
        'credit_days',
        'due_date',
        'payment_type',
        'concept',
        'erp_uuid',
        'erp_series',
        'erp_document_number',
        'old_company_id',
        'customer_id',
        'company_id',
        'createdby_id',
        'updatedby_id',
    ];

    /* =========================
       CASTS
    =========================*/
    protected $casts = [
        'issue_date'     => 'date',
        'due_date'       => 'date',
        'total_amount'   => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'credit_days'    => 'integer',
        'company_id'     => 'integer',
        'customer_id'    => 'integer',
        'old_company_id' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
