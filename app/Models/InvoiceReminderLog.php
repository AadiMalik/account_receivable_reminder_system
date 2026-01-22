<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceReminderLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'customer_invoice_id',
        'customer_id',
        'reminder_type',
        'message',
        'customer_phone',
        'whatsapp_exists',
        'message_sent',
        'whatsapp_message_id',
        'request_payload',
        'response_payload',
        'error_message',
        'sent_at',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function invoice()
    {
        return $this->belongsTo(CustomerInvoice::class, 'customer_invoice_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
