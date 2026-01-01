<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'commercial_name',
        'email',
        'code',
        'nit',
        'address',
        'phone',
        'credit_limit',
        'credit_days',
        'old_company_id',
        'company_id',
        'createdby_id',
        'updatedby_id',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    
    public function invoices()
    {
        return $this->hasMany(CustomerInvoice::class, 'customer_id', 'id');
    }
}
