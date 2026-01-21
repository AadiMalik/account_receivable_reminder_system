<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceReminderLog;
use Illuminate\Support\Facades\Auth;

class InvoiceReminderLogController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company->id;

        // Fetch messages for this company
        $messages = InvoiceReminderLog::with(['customer', 'invoice'])
            ->where('company_id', $company_id)
            ->orderBy('sent_at', 'desc')
            ->get();

        // Stats
        $totalMessages = $messages->count();
        $sentToday = $messages->where('sent_at', '>=', now()->startOfDay())->count();
        $unreadCount = $messages->where('whatsapp_exists', true)->whereNull('message_sent')->count();

        return view('whatsapp.index', compact('messages', 'totalMessages', 'sentToday', 'unreadCount'));
    }
}
