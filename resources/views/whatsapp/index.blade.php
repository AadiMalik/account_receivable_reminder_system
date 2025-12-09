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
                        <h4 class="mb-0">247</h4>
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
                        <h4 class="mb-0">12</h4>
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
                        <h4 class="mb-0">2</h4>
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

            @php
                $messages = [
                    ['id'=>1,'customer'=>'Acme Industries','invoice'=>'INV-1234','msg'=>'Payment will be processed by Friday','date'=>'2025-11-19 10:45','type'=>'received','unread'=>false],
                    ['id'=>2,'customer'=>'Global Retail Co.','invoice'=>'INV-1236','msg'=>'Reminder: Invoice INV-1236 is overdue','date'=>'2025-11-18 15:20','type'=>'sent','unread'=>false],
                    ['id'=>3,'customer'=>'TechCorp Solutions','invoice'=>'INV-1235','msg'=>'Payment has been processed. Thank you!','date'=>'2025-11-19 09:15','type'=>'received','unread'=>true],
                    ['id'=>4,'customer'=>'Digital Marketing Ltd','invoice'=>'INV-1238','msg'=>'Can we extend the payment deadline?','date'=>'2025-11-17 14:30','type'=>'received','unread'=>true],
                    ['id'=>5,'customer'=>'Smart Systems Inc','invoice'=>'INV-1237','msg'=>'Reminder: Invoice INV-1237 is 35 days overdue','date'=>'2025-11-16 11:00','type'=>'sent','unread'=>false],
                ];
            @endphp

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
                        <tr class="{{ $msg['unread'] ? 'table-primary' : '' }}">
                            <td>{{ $msg['customer'] }}</td>
                            <td>{{ $msg['invoice'] }}</td>
                            <td class="text-truncate" style="max-width: 250px;">{{ $msg['msg'] }}</td>
                            <td>{{ $msg['date'] }}</td>
                            <td>
                                <span class="badge 
                                    {{ $msg['type']=='sent' ? 'bg-primary-subtle text-primary' : 'bg-success-subtle text-success' }}">
                                    {{ ucfirst($msg['type']) }}
                                </span>
                            </td>
                            <td>
                                @if($msg['unread'])
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
