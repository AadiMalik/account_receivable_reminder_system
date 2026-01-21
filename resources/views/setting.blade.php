@extends('layouts.master')
@section('title', 'Settings')

@section('content')

<h4 class="mb-1">Settings</h4>
<p class="text-muted mb-4">Configure your account and automation rules</p>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    <div class="col-md-9">

        {{-- Company Information --}}
        <form action="{{ route('settings.update.company') }}" method="POST">
            @csrf
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Company Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="company-name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" id="company-name" name="name" class="form-control" value="{{ old('name', $company->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="company-email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" id="company-email" name="email" class="form-control" value="{{ old('email', $company->email) }}" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="company-phone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" id="company-phone" name="phone" class="form-control" value="{{ old('phone', $company->phone) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="company-website" class="form-label">Website</label>
                            <input type="text" id="company-website" name="website" class="form-control" value="{{ old('website', $company->website) }}">
                        </div>
                    </div>
                    <button class="btn btn-primary">Update Company</button>
                </div>
            </div>
        </form>

        {{-- WhatsApp Integration --}}
        <form action="{{ route('settings.update.whatsapp') }}" method="POST">
            @csrf
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">WhatsApp Integration</h5>
                    <span id="whatsapp-status-badge"
                        class="badge {{ $company->green_active ? 'bg-success' : 'bg-danger' }}"
                        style="cursor:pointer;"
                        onclick="toggleWhatsAppStatus({{ $company->id }})">
                        <i class="bi {{ $company->green_active ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                        {{ $company->green_active ? 'Connected' : 'Disconnected' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="green_api_instance" class="form-label">Id Instance <span class="text-danger">*</span></label>
                        <input type="text" id="green_api_instance" name="green_api_instance" class="form-control" value="{{ old('green_api_instance', $company->green_api_instance) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="green_api_token" class="form-label">API Token Instance <span class="text-danger">*</span></label>
                        <input type="text" id="green_api_token" name="green_api_token" class="form-control" value="{{ old('green_api_token', $company->green_api_token) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="green_webhook_url" class="form-label">API URL</label>
                        <input type="text" id="green_webhook_url" name="green_webhook_url" class="form-control" value="{{ old('green_webhook_url', $company->green_webhook_url) }}">
                    </div>

                    <div class="p-3 mb-3 bg-primary bg-opacity-10 border border-primary rounded">
                        <p class="fw-semibold mb-2">API Usage Today</p>
                        <div class="d-flex justify-content-between">
                            <span>Messages sent:</span><span>{{ $company->green_sent_message ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Messages received:</span><span>{{ $company->green_received_message ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Monthly limit:</span><span>{{ $company->green_monthly_limit ?? 0 }}</span>
                        </div>
                    </div>

                    <button class="btn btn-primary">Update WhatsApp</button>
                </div>
            </div>
        </form>

        {{-- ERP Integration --}}
        <form action="{{ route('settings.update.erp') }}" method="POST">
            @csrf
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">ERP Integration</h5>
                    <span id="erp-status-badge"
                        class="badge {{ $company->erp_active ? 'bg-success' : 'bg-danger' }}"
                        style="cursor:pointer;"
                        onclick="toggleERPStatus({{ $company->id}})">
                        <i class="bi {{ $company->erp_active ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                        {{ $company->erp_active ? 'Connected' : 'Disconnected' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="erp_system" class="form-label">ERP System <span class="text-danger">*</span></label>
                        <select id="erp_system" name="erp_system" class="form-select" required>
                            <option value="sap" {{ $company->erp_system == 'sap' ? 'selected' : '' }}>SAP Business One</option>
                            <option value="oracle" {{ $company->erp_system == 'oracle' ? 'selected' : '' }}>Oracle NetSuite</option>
                            <option value="quickbooks" {{ $company->erp_system == 'quickbooks' ? 'selected' : '' }}>QuickBooks</option>
                            <option value="xero" {{ $company->erp_system == 'xero' ? 'selected' : '' }}>Xero</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="erp_api_base_url" class="form-label">API Endpoint <span class="text-danger">*</span></label>
                        <input type="text" id="erp_api_base_url" name="erp_api_base_url" class="form-control" value="{{ old('erp_api_base_url', $company->erp_api_base_url) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="erp_api_token" class="form-label">API Key <span class="text-danger">*</span></label>
                        <input type="text" id="erp_api_token" name="erp_api_token" class="form-control" value="{{ old('erp_api_token', $company->erp_api_token) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="erp_auto_sync" class="form-label">Auto Sync Frequency (Hours) <span class="text-danger">*</span></label>
                        <input type="number" id="erp_auto_sync" name="erp_auto_sync" class="form-control" value="{{ old('erp_auto_sync', $company->erp_auto_sync ?? 6) }}" required>
                    </div>

                    <button class="btn btn-primary">Update ERP</button>
                </div>
            </div>
        </form>

        {{-- Automated Reminder Rules --}}
        <form action="{{ route('settings.update.reminders') }}" method="POST">
            @csrf
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-lightning-charge text-success fs-4"></i>
                    <h5 class="mb-0">Automated Reminder Rules</h5>
                </div>
                <div class="card-body">
                    <div class="p-3 mb-3 bg-success bg-opacity-10 border border-success rounded">
                        <p class="mb-0">Reminders are automatically sent via WhatsApp based on these rules. No manual action needed!</p>
                    </div>

                    <div class="mb-3">
                        <label for="before_due" class="form-label">Days before due date to send first reminder <span class="text-danger">*</span></label>
                        <input type="number" id="before_due" name="before_due" class="form-control" value="{{ old('before_due', $company->before_due ?? 7) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="on_due" class="form-label">Send reminder on due date <span class="text-danger">*</span></label>
                        <select id="on_due" name="on_due" class="form-select" required>
                            <option value="1" {{ ($company->on_due ?? '1') == '1' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ ($company->on_due ?? '0') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="after_due_1" class="form-label">Days after overdue for first follow-up <span class="text-danger">*</span></label>
                        <input type="number" id="after_due_1" name="after_due_1" class="form-control" value="{{ old('after_due_1', $company->after_due_1 ?? 3) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="after_due_2" class="form-label">Days after overdue for second follow-up <span class="text-danger">*</span></label>
                        <input type="number" id="after_due_2" name="after_due_2" class="form-control" value="{{ old('after_due_2', $company->after_due_2 ?? 7) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="max_reminders" class="form-label">Maximum reminders per invoice <span class="text-danger">*</span></label>
                        <input type="number" id="max_reminders" name="max_reminders" class="form-control" value="{{ old('max_reminders', $company->max_reminders ?? 5) }}" required>
                    </div>

                    <button class="btn btn-primary">Update Reminders</button>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection
<script>
function toggleWhatsAppStatus(companyId) {
    let badge = document.getElementById('whatsapp-status-badge');
    let current = badge.textContent.trim();
    let newStatus = current === 'Connected' ? 0 : 1;

    fetch(`/setting/toggle-whatsapp/${companyId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ green_active: newStatus })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            badge.className = 'badge ' + (newStatus ? 'bg-success' : 'bg-danger');
            badge.innerHTML = `<i class="bi ${newStatus ? 'bi-check-circle' : 'bi-x-circle'} me-1"></i>${newStatus ? 'Connected' : 'Disconnected'}`;
        }
    });
}

function toggleERPStatus(companyId) {
    let badge = document.getElementById('erp-status-badge');
    let current = badge.textContent.trim();
    let newStatus = current === 'Connected' ? 0 : 1;

    fetch(`/setting/toggle-erp/${companyId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ erp_active: newStatus })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            badge.className = 'badge ' + (newStatus ? 'bg-success' : 'bg-danger');
            badge.innerHTML = `<i class="bi ${newStatus ? 'bi-check-circle' : 'bi-x-circle'} me-1"></i>${newStatus ? 'Connected' : 'Disconnected'}`;
        }
    });
}
</script>
