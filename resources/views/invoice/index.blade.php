@extends('layouts.master')
@section('title', 'Invoices')

@push('styles')
<style>
.badge-status { font-size: 0.85rem; padding: 0.4em 0.6em; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1">Invoices</h4>
        <p class="text-muted mb-0">Track and manage all invoices</p>
    </div>
    <div class="d-flex align-items-center text-success small">
        <i class="fas fa-bolt me-1"></i>
        Auto reminders active
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 display">
                <thead class="table-light">
                    <tr>
                        <th>Invoice #</th>
                        <th class="d-none d-lg-table-cell">Customer</th>
                        <th class="d-none d-sm-table-cell">Due Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="d-none d-md-table-cell">Reminders</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($invoices as $invoice)
                        @php
                            $balance = $invoice->balance_amount ?? 0;
                            $dueDate = $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date) : null;
                            $daysOverdue = $dueDate && $balance > 0 && $dueDate->lt(now()) 
                                            ? $dueDate->diffInDays(now()) 
                                            : 0;

                            $status = 'Pending';
                            if ($balance <= 0) $status = 'Paid';
                            elseif ($daysOverdue > 0) $status = 'Overdue';
                        @endphp

                        <tr>
                            <td>{{ $invoice->document_number ?? '-' }}</td>

                            <td class="d-none d-lg-table-cell fw-semibold">
                                {{ $invoice->customer->name ?? 'N/A' }}
                            </td>

                            <td class="d-none d-sm-table-cell text-muted">
                                {{ $dueDate ? $dueDate->format('d M Y') : '-' }}
                            </td>

                            <td>${{ number_format($invoice->total_amount ?? 0, 2) }}</td>

                            <td>
                                @if($status === 'Overdue')
                                    <span class="badge bg-danger badge-status">Overdue ({{ $daysOverdue }} days)</span>
                                @elseif($status === 'Paid')
                                    <span class="badge bg-success badge-status">Paid</span>
                                @else
                                    <span class="badge bg-primary badge-status">Pending</span>
                                @endif
                            </td>

                            <td class="d-none d-md-table-cell">
                                {{-- Dynamic auto reminders if implemented --}}
                                {{ $invoice->auto_reminders_sent ?? 0 }}
                            </td>

                            <td>
                                <a href="{{ url('invoice/detail/'.$invoice->id) }}" class="btn btn-outline-secondary btn-sm">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush
