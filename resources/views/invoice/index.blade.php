@extends('layouts.master')
@section('title', 'Invoices')
@push('styles')
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

    @php
        $invoices = [
            [
                'id' => 1,
                'number' => 'INV-1234',
                'customer' => 'Acme Industries',
                'date' => '2025-10-15',
                'dueDate' => '2025-11-15',
                'amount' => 45000,
                'status' => 'Overdue',
                'daysOverdue' => 4,
                'autoReminders' => 2,
            ],
            [
                'id' => 2,
                'number' => 'INV-1235',
                'customer' => 'TechCorp Solutions',
                'date' => '2025-11-01',
                'dueDate' => '2025-12-01',
                'amount' => 89000,
                'status' => 'Paid',
                'daysOverdue' => 0,
                'autoReminders' => 0,
            ],
            [
                'id' => 3,
                'number' => 'INV-1236',
                'customer' => 'Global Retail Co.',
                'date' => '2025-10-01',
                'dueDate' => '2025-11-01',
                'amount' => 78000,
                'status' => 'Overdue',
                'daysOverdue' => 18,
                'autoReminders' => 3,
            ],
            [
                'id' => 4,
                'number' => 'INV-1237',
                'customer' => 'Smart Systems Inc',
                'date' => '2025-09-15',
                'dueDate' => '2025-10-15',
                'amount' => 23000,
                'status' => 'Overdue',
                'daysOverdue' => 35,
                'autoReminders' => 5,
            ],
            [
                'id' => 5,
                'number' => 'INV-1238',
                'customer' => 'Digital Marketing Ltd',
                'date' => '2025-11-10',
                'dueDate' => '2025-12-10',
                'amount' => 99600,
                'status' => 'Pending',
                'daysOverdue' => 0,
                'autoReminders' => 0,
            ],
        ];
    @endphp

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
                            <tr>
                                <td>{{ $invoice['number'] }}</td>

                                <td class="d-none d-lg-table-cell fw-semibold">
                                    {{ $invoice['customer'] }}
                                </td>

                                <td class="d-none d-sm-table-cell text-muted">
                                    {{ $invoice['dueDate'] }}
                                </td>

                                <td>${{ number_format($invoice['amount']) }}</td>

                                <td>
                                    @if ($invoice['status'] === 'Overdue')
                                        <span class="badge bg-danger">Overdue ({{ $invoice['daysOverdue'] }}d)</span>
                                    @elseif($invoice['status'] === 'Paid')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-primary">Pending</span>
                                    @endif
                                </td>

                                <td class="d-none d-md-table-cell">
                                    @if ($invoice['autoReminders'] > 0)
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-bolt text-primary me-1"></i>
                                            <span>{{ $invoice['autoReminders'] }} sent</span>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ url('invoice/detail/') }}" class="btn btn-outline-secondary btn-sm">
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
