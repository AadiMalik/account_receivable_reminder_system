@extends('layouts.master')
@section('title', 'Customer Detail')

@section('content')

<a href="{{ url('customer') }}" class="btn btn-link text-decoration-none mb-3">
    <i class="fas fa-arrow-left me-1"></i> Back to Customers
</a>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">{{ $customer['name'] }}</h4>
        <small class="text-muted">Customer details and invoice history</small>
    </div>
    <div class="d-flex align-items-center text-success">
        <i class="fas fa-bolt me-1"></i>
        <span>Auto reminders enabled</span>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <div class="card p-3">
            <small class="text-muted">Total Balance</small>
            <h5>${{ number_format($customer['balance'] ?? 0, 2) }}</h5>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card p-3">
            <small class="text-muted">Overdue Amount</small>
            <h5 class="text-danger">${{ number_format($customer['overdue'] ?? 0, 2) }}</h5>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card p-3">
            <small class="text-muted">WhatsApp Number</small>
            <h5>{{ $customer['phone'] ?? '-' }}</h5>
        </div>
    </div>
</div>

<!-- Invoices Table -->
<div class="card mb-4">
    <div class="card-header">
        <strong>Invoices</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 display">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($customer['invoices']))
                    @foreach($customer['invoices'] as $inv)
                        <tr>
                            <td>{{ $inv['document_number'] }}</td>
                            <td>{{ $inv['issue_date'] }}</td>
                            <td>{{ $inv['due_date'] }}</td>
                            <td>${{ number_format($inv['amount'] ?? 0, 2) }}</td>
                            <td>
                                @if($inv['status'] === 'Overdue')
                                    <span class="badge bg-danger">Overdue ({{ $inv['days_overdue'] }} days)</span>
                                @else
                                    <span class="badge bg-success">{{ $inv['status'] }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center text-muted">No invoices found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- WhatsApp Communication History -->
<div class="card">
    <div class="card-header">
        <strong>WhatsApp Communication History</strong>
    </div>
    <div class="card-body">
        @foreach($history as $item)
            <div class="d-flex align-items-start gap-2 pb-2 mb-2 border-bottom">
                <div class="{{ $item['type']=='sent' ? 'bg-primary bg-opacity-10 text-primary' : 'bg-success bg-opacity-10 text-success' }} rounded-circle p-2 mt-1">
                    <i class="fas fa-comment" style="width: 25px; text-align: center;"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="mb-0">{{ $item['message'] }}</p>
                    <small class="text-muted">{{ $item['date'] }}</small>
                </div>
                <span class="badge {{ $item['type']=='sent' ? 'bg-primary' : 'bg-success' }}">
                    {{ $item['type']=='sent' ? 'Auto Sent' : 'Received' }}
                </span>
            </div>
        @endforeach
    </div>
</div>

@endsection
