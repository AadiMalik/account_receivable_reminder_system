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
                        @foreach ($invoices as $item)
                            <tr>
                                <td>{{ $item['number'] }}</td>

                                <td class="d-none d-lg-table-cell fw-semibold">
                                    {{ $item['customer'] }}
                                </td>

                                <td class="d-none d-sm-table-cell text-muted">
                                    {{ $item['due_date'] }}
                                </td>

                                <td>${{ number_format($item['amount']) }}</td>

                                <td>
                                    @if ($item['status'] === 'Overdue')
                                        <span class="badge bg-danger">Overdue ({{ $item['daysOverdue']??0 }}days)</span>
                                    @elseif($item['status'] === 'Paid')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-primary">Pending</span>
                                    @endif
                                </td>

                                <td class="d-none d-md-table-cell">
                                    {{-- @if ($item['autoReminders'] > 0)
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-bolt text-primary me-1"></i>
                                            <span>{{ $item['autoReminders'] }} sent</span>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif --}}
                                    0
                                </td>

                                <td>
                                    <a href="{{ url('invoice/detail/'.$item['id']) }}" class="btn btn-outline-secondary btn-sm">
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
