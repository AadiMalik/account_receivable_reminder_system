<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'old_invoice_id',
        'product_id',
        'invoice_series',
        'invoice_type',
        'invoice_number',
        'issue_date',
        'total_amount',
        'old_company_id',
        'payment_type',
        'credit_days',
        'registration_date',
        'status_id',
        'document_type',
        'document_date',
        'iva',
        'idp',
        'oi',
        'user_id',
        'erp_uuid',
        'erp_uuid_series',
        'erp_invoice_number',
        'currency',
        'paid_amount',
        'exchange_rate',
        'fel_address',
        'cancellation_date',
        'due_date',
        'createdby_id',
        'updatedby_id',
        'company_id',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id', 'id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

}
