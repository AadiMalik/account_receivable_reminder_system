@extends('layouts.master')
@section('title', 'WhatsApp Messages')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">WhatsApp Messages</h4>
        <p class="text-muted mb-0">Communication history with customers</p>
    </div>

    <a href="#" class="btn btn-success">
        <i class="fas fa-paper-plane me-2"></i>
        Send New Message
    </a>
</div>

{{-- Stats --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body d-flex justify-content-between">
                <div>
                    <p class="text-muted mb-1">Total Messages</p>
                    <h4 class="mb-0">{{ $totalMessages }}</h4>
                </div>
                <div class="bg-primary-subtle p-3 rounded">
                    <i class="fas fa-comments text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body d-flex justify-content-between">
                <div>
                    <p class="text-muted mb-1">Sent Today</p>
                    <h4 class="mb-0">{{ $sentToday }}</h4>
                </div>
                <div class="bg-success-subtle p-3 rounded">
                    <i class="fas fa-paper-plane text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body d-flex justify-content-between">
                <div>
                    <p class="text-muted mb-1">Unread</p>
                    <h4 class="mb-0">{{ $unreadCount }}</h4>
                </div>
                <div class="bg-danger-subtle p-3 rounded">
                    <i class="fas fa-envelope text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Message Table --}}
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="card-title mb-0">Message History</h5>
    </div>

    <div class="card-body">

        <table class="table table-striped table-hover display">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Invoice</th>
                    <th>Last Message</th>
                    <th>Date & Time</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($messages as $msg)
                <tr class="{{ $msg->message_sent ? '' : 'table-primary' }}">
                    <td>{{ $msg->customer->name ?? 'N/A' }}</td>
                    <td>{{ $msg->invoice->invoice_number ?? 'N/A' }}</td>
                    <td class="text-truncate" style="max-width: 250px;">{{ $msg->message_sent ?? $msg->error_message ?? 'No message' }}</td>
                    <td>{{ $msg->sent_at ? $msg->sent_at->format('Y-m-d H:i') : 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $msg->message_sent ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                            {{ $msg->message_sent ? 'Sent' : 'Received' }}
                        </span>
                    </td>
                    <td>
                        @if(!$msg->message_sent)
                        <span class="badge bg-danger-subtle text-danger">Unread</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>


        </table>

    </div>
</div>
@endsection
@section('scripts')
@endsection