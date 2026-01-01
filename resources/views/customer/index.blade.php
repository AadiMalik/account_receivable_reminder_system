@extends('layouts.master')
@section('title', 'Customers')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1">Customers</h4>
        <p class="text-muted mb-0">Manage your customer accounts</p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 display">
                <thead class="table-light">
                    <tr>
                        <th>Customer Name</th>
                        <th class="d-none d-sm-table-cell">Phone Number</th>
                        <th>Balance</th>
                        <th class="d-none d-lg-table-cell">Overdue</th>
                        <th class="d-none d-md-table-cell">Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>
                                <p class="mb-0 fw-semibold">{{ $customer['name']??'-' }}</p>
                                <small class="text-muted">{{ $customer['invoices_count'] }} invoices</small>
                            </td>

                            <td class="d-none d-sm-table-cell">
                                <i class="fas fa-comment text-success me-1"></i>
                                {{ $customer['phone'] ?? '-' }}
                            </td>

                            <td>${{ number_format($customer['balance'] ?? 0, 2) }}</td>

                            <td class="d-none d-lg-table-cell {{ ($customer['overdue'] ?? 0) > 0 ? 'text-danger' : 'text-muted' }}">
                                ${{ number_format($customer['overdue'] ?? 0, 2) }}
                            </td>

                            <td class="d-none d-md-table-cell">
                                @if (($customer['overdue'] ?? 0) > 0)
                                    <span class="badge bg-danger">Overdue</span>
                                @else
                                    <span class="badge bg-success">Current</span>
                                @endif
                            </td>

                            <td>
                                <a class="btn btn-outline-secondary btn-sm" href="{{ url('customer/detail/' . $customer['id']) }}">
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
