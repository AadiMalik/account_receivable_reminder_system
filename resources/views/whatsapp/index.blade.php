@extends('layouts.master')
@section('title', 'WhatsApp Messages')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">WhatsApp Messages</h4>
            <p class="text-muted mb-0">Communication history with customers</p>
        </div>

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#whatsappInvoiceModal">
            <i class="fas fa-paper-plane me-2"></i>
            Send New Message
        </button>
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
                    @foreach ($messages as $msg)
                        <tr class="{{ $msg->message_sent ? '' : 'table-primary' }}">
                            <td>{{ $msg->customer->name ?? 'N/A' }}</td>
                            <td>{{ $msg->invoice->document_number ?? 'N/A' }}</td>
                            <td class="text-truncate" style="max-width: 250px;">
                                {{ $msg->message_sent ?? ($msg->error_message ?? 'No message') }}</td>
                            <td>{{ $msg->sent_at ? $msg->sent_at->format('Y-m-d H:i') : 'N/A' }}</td>
                            <td>
                                <span
                                    class="badge {{ $msg->message_sent ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                    {{ $msg->message_sent ? 'Sent' : 'Received' }}
                                </span>
                            </td>
                            <td>
                                @if (!$msg->message_sent)
                                    <span class="badge bg-danger-subtle text-danger">Unread</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>


            </table>

        </div>
    </div>
    <div class="modal fade" id="whatsappInvoiceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fab fa-whatsapp"></i> Send Invoice via WhatsApp
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="whatsappInvoiceForm">
                        @csrf

                        {{-- Invoice Dropdown --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Invoice<span class="text-danger">*</span></label>
                                <select class="form-select" name="invoice_id" id="invoice_id" required>
                                    <option value="">-- Select Invoice --</option>
                                    @foreach ($invoices as $invoice)
                                        <option value="{{ $invoice->id }}"
                                            data-name="{{ $invoice->customer->name ? $invoice->customer->name : $invoice->customer->commercial_name }}"
                                            data-phone="{{ $invoice->customer->phone }}"
                                            data-amount="{{ number_format($invoice->total_amount, 2) }}">
                                            {{ $invoice->document_number }} - Issue
                                            Date:{{ $invoice->issue_date->format('d M Y') }} - Due
                                            Date:{{ $invoice->issue_date->format('d M Y') }} -
                                            Amount:{{ number_format($invoice->total_amount, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Reminder Type<span class="text-danger">*</span></label>
                                <select class="form-select" name="reminder_type" id="reminder_type" required>
                                    <option value="">-- Select Reminder Type --</option>
                                    <option value="before_due"> Before Due Date</option>
                                    <option value="on_due"> On Due Date</option>
                                    <option value="after_due_1"> 1st After Due Date</option>
                                    <option value="after_due_2"> 2nd After Due Date</option>
                                </select>
                            </div>
                        </div>

                        {{-- Auto Filled --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Customer<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="customer_name" id="customer_name" required>
                            </div>
                            <div class="col-md-6">
                                <label>Phone<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="customer_phone" id="customer_phone"
                                    required>
                            </div>
                        </div>

                        {{-- Message --}}
                        <div class="mb-3">
                            <label>Message<span class="text-danger">*</span></label>
                            <textarea name="message" id="message" rows="5" class="form-control" required>
Dear customer,
Your invoice amount is Rs  amount.
Please clear your payment.
Thank you.
                            </textarea>
                        </div>

                    </form>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-success" id="sendWhatsappBtn">
                        <i class="fas fa-paper-plane"></i> Send
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        $('#invoice_id').on('change', function() {
            let option = $(this).find(':selected');

            let name = option.data('name');
            let phone = option.data('phone');
            let amount = option.data('amount');

            $('#customer_name').val(name);
            $('#phone').val(phone);

            let msg = `Dear ${name},
Your invoice amount is Rs ${amount}.
Please clear your payment.
Thank you.`;

            $('#message').val(msg);
        });

        $('#sendWhatsappBtn').click(function() {
            $.ajax({
                url: "{{ url('whatsapp/send-message') }}",
                type: "POST",
                data: $('#whatsappInvoiceForm').serialize(),
                success: function(res) {
                    if (res.status) {
                        alert('WhatsApp message sent');
                        $('#whatsappInvoiceModal').modal('hide');
                        location.reload();
                    } else {
                        alert(res.message);
                    }
                },
                error: function() {
                    alert('Something went wrong');
                }
            });
        });
    </script>
@endpush
