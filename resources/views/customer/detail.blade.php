@extends('layouts.master')
@section('title', 'Customer Detail')

@section('content')

<a href="{{ url('customer') }}" class="btn btn-outline-secondary mb-3">
    <i class="fas fa-arrow-left me-1"></i> Back to Customers
</a>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4>Acme Industries</h4>
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
            <h5>$125,000</h5>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card p-3">
            <small class="text-muted">Overdue Amount</small>
            <h5 class="text-danger">$45,000</h5>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card p-3">
            <small class="text-muted">WhatsApp Number</small>
            <h5>+1 555-0101</h5>
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
                    @php
                        $invoices = [
                            ['number'=>'INV-1234','date'=>'2025-10-15','dueDate'=>'2025-11-15','amount'=>45000,'status'=>'Overdue','daysOverdue'=>4],
                            ['number'=>'INV-1198','date'=>'2025-11-01','dueDate'=>'2025-12-01','amount'=>80000,'status'=>'Paid','daysOverdue'=>0],
                        ];
                    @endphp
                    @foreach($invoices as $inv)
                    <tr>
                        <td>{{ $inv['number'] }}</td>
                        <td>{{ $inv['date'] }}</td>
                        <td>{{ $inv['dueDate'] }}</td>
                        <td>${{ number_format($inv['amount']) }}</td>
                        <td>
                            @if($inv['status'] == 'Overdue')
                                <span class="badge bg-danger">Overdue ({{ $inv['daysOverdue'] }} days)</span>
                            @else
                                <span class="badge bg-success">{{ $inv['status'] }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
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
        @php
            $whatsappHistory = [
                ['message'=>'Auto reminder sent for INV-1234 (4 days overdue)','date'=>'2025-11-19 10:30','type'=>'sent'],
                ['message'=>'Customer replied: Payment will be processed by Friday','date'=>'2025-11-19 10:45','type'=>'received'],
                ['message'=>'Auto reminder sent for INV-1234 (due in 1 day)','date'=>'2025-11-14 09:00','type'=>'sent'],
            ];
        @endphp
        @foreach($whatsappHistory as $item)
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
