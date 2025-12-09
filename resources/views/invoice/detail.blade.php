@extends('layouts.master')
@section('title', 'Invoice Details')

@section('content')

    <a href="{{ url('invoice') }}" class="btn btn-link text-decoration-none">
        <i class="fas fa-arrow-left me-2"></i>
        Back to Invoices
    </a>
    <br>
    <br>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Invoice INV-1234</h3>
            <p class="text-muted mb-0">Invoice details and reminder history</p>
        </div>
        <div class="d-flex align-items-center text-success small">
            <i class="fas fa-bolt me-2"></i>
            Auto reminders enabled
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
                        <span class="fw-semibold">Acme Industries</span>
                    </div>

                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Invoice Date:</span>
                        <span class="fw-semibold">2025-10-15</span>
                    </div>

                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Due Date:</span>
                        <span class="fw-semibold">2025-11-15</span>
                    </div>

                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Amount:</span>
                        <span class="fw-semibold">$45,000</span>
                    </div>

                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Days Overdue:</span>
                        <span class="badge bg-danger-subtle text-danger">4 days</span>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Status:</span>
                        <span class="badge bg-danger-subtle text-danger">Overdue</span>
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
                        <span class="fw-semibold">$45,000</span>
                    </div>

                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Amount Paid:</span>
                        <span class="fw-semibold">$0</span>
                    </div>

                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Balance:</span>
                        <span class="fw-semibold text-danger">$45,000</span>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Payment Method:</span>
                        <span class="fw-semibold">Bank Transfer</span>
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

            <div class="alert alert-primary d-flex align-items-center">
                <i class="fas fa-bolt me-2"></i>
                Reminders are sent automatically based on your settings rules
            </div>

            @php
                $reminderHistory = [
                    ['id' => 1, 'date' => '2025-11-19 10:30', 'status' => 'Delivered', 'rule' => 'Overdue +4 days'],
                    ['id' => 2, 'date' => '2025-11-15 09:00', 'status' => 'Read', 'rule' => 'Due date reminder'],
                    ['id' => 3, 'date' => '2025-11-10 14:00', 'status' => 'Delivered', 'rule' => '5 days before due'],
                ];
            @endphp

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

                    <span
                        class="badge 
                    {{ $reminder['status'] == 'Read' ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }}">
                        {{ $reminder['status'] }}
                    </span>

                </div>
            @endforeach

        </div>
    </div>
@endsection
