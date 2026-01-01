@extends('layouts.master')
@section('title', 'Invoice Details')

@section('content')

<a href="{{ url('invoice') }}" class="btn btn-link text-decoration-none">
    <i class="fas fa-arrow-left me-2"></i> Back to Invoices
</a>
<br><br>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Invoice # {{ $invoice['document_number'] }}</h3>
        <p class="text-muted mb-0">Invoice details and reminder history</p>
    </div>
    <div class="d-flex align-items-center text-success small">
        <i class="fas fa-bolt me-2"></i> Auto reminders enabled
    </div>
</div>

<div class="row g-4">

    {{-- Invoice Details --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Invoice Details</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Customer:</span>
                    <span class="fw-semibold">{{ $invoice['customer'] }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Invoice Date:</span>
                    <span class="fw-semibold">{{ $invoice['issue_date'] }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Due Date:</span>
                    <span class="fw-semibold">{{ $invoice['due_date'] }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Amount:</span>
                    <span class="fw-semibold">${{ number_format($invoice['amount'],2) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Days Overdue:</span>
                    <span class="badge {{ $invoice['days_overdue'] > 0 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}">
                        {{ $invoice['days_overdue'] }} days
                    </span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted">Status:</span>
                    <span class="badge {{ $invoice['status']=='Paid' ? 'bg-success-subtle text-success' : ($invoice['status']=='Overdue' ? 'bg-danger-subtle text-danger':'bg-primary-subtle text-primary') }}">
                        {{ $invoice['status'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Info --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Payment Information</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Amount Due:</span>
                    <span class="fw-semibold">${{ number_format($invoice['balance'],2) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Amount Paid:</span>
                    <span class="fw-semibold">${{ number_format($invoice['amount_paid'],2) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Balance:</span>
                    <span class="fw-semibold text-danger">${{ number_format($invoice['balance'],2) }}</span>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <span class="text-muted">Payment Method:</span>
                    <span class="fw-semibold">{{ $invoice['payment_method'] }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Automated Reminders --}}
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Automated Reminder History</h5>
    </div>
    <div class="card-body">
        @if(empty($reminderHistory))
            <div class="alert alert-info">No reminders sent yet.</div>
        @endif

        @foreach ($reminderHistory as $reminder)
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success-subtle rounded-circle p-2">
                        <i class="fas fa-comment text-success"></i>
                    </div>
                    <div>
                        <p class="fw-semibold mb-0">WhatsApp Auto Reminder</p>
                        <p class="text-muted mb-0">{{ $reminder['date'] }}</p>
                        <p class="text-secondary small mb-0 mt-1">Rule: {{ $reminder['rule'] }}</p>
                    </div>
                </div>
                <span class="badge {{ $reminder['status']=='Read' ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }}">
                    {{ $reminder['status'] }}
                </span>
            </div>
        @endforeach
    </div>
</div>

@endsection
