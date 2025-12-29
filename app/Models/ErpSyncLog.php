<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpSyncLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'customers_added',
        'customers_updated',
        'invoices_added',
        'invoices_updated',
        'invoice_items_added',
        'invoice_items_updated',
        'synced_at',
        'createdby_id',
        'updatedby_id',
        'company_id',
    ];

    public function company()
    {
        return $this->hasOne(Company::class, 'company_id', 'id');
    }
}
