<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'website',
        'green_api_instance',
        'green_api_token',
        'green_webhook_url',
        'green_sent_message',
        'green_received_message',
        'green_monthly_limit',
        'green_active',
        'erp_system',
        'erp_api_base_url',
        'erp_api_token',
        'erp_api_secret',
        'erp_auto_sync',
        'erp_active',
        'status',
        'login_user_id',
        'createdby_id',
        'updatedby_id',
        'company_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'company_id', 'id');
    }
}
