@extends('layouts.master')
@section('title', 'Settings')

@section('content')

    <h4 class="mb-1">Settings</h4>
    <p class="text-muted mb-4">Configure your account and automation rules</p>

    <div class="row">
        <div class="col-md-9">
            {{-- Company Information --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Company Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="company-name" class="form-label">Company Name</label>
                            <input type="text" id="company-name" class="form-control" value="Acme Corporation">
                        </div>
                        <div class="col-md-6">
                            <label for="company-email" class="form-label">Email</label>
                            <input type="email" id="company-email" class="form-control" value="contact@acme.com">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="company-phone" class="form-label">Phone</label>
                            <input type="text" id="company-phone" class="form-control" value="+1 555-0100">
                        </div>
                        <div class="col-md-6">
                            <label for="company-website" class="form-label">Website</label>
                            <input type="text" id="company-website" class="form-control" value="www.acme.com">
                        </div>
                    </div>
                    <button class="btn btn-primary">Save Changes</button>
                </div>
            </div>

            {{-- WhatsApp Integration --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">WhatsApp Integration</h5>
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Connected</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="whatsapp-number" class="form-label">WhatsApp Business Number</label>
                        <input type="text" id="whatsapp-number" class="form-control" value="+1 555-0199">
                    </div>
                    <div class="mb-3">
                        <label for="api-key" class="form-label">API Key</label>
                        <input type="password" id="api-key" class="form-control" value="••••••••••••••••">
                    </div>
                    <div class="mb-3">
                        <label for="webhook" class="form-label">Webhook URL</label>
                        <input type="text" id="webhook" class="form-control"
                            value="https://app.acme.com/webhooks/whatsapp">
                    </div>

                    <div class="p-3 mb-3 bg-primary bg-opacity-10 border border-primary rounded">
                        <p class="fw-semibold mb-2">API Usage Today</p>
                        <div class="d-flex justify-content-between">
                            <span>Messages sent:</span><span>12</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Messages received:</span><span>8</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Monthly limit:</span><span>1,000</span>
                        </div>
                    </div>

                    <button class="btn btn-primary">Update Settings</button>
                </div>
            </div>

            {{-- ERP Integration --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">ERP Integration</h5>
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Connected</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="erp-system" class="form-label">ERP System</label>
                        <select id="erp-system" class="form-select">
                            <option value="sap" selected>SAP Business One</option>
                            <option value="oracle">Oracle NetSuite</option>
                            <option value="quickbooks">QuickBooks</option>
                            <option value="xero">Xero</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="erp-url" class="form-label">API Endpoint</label>
                        <input type="text" id="erp-url" class="form-control" value="https://erp.acme.com/api/v1">
                    </div>
                    <div class="mb-3">
                        <label for="erp-key" class="form-label">API Key</label>
                        <input type="password" id="erp-key" class="form-control" value="••••••••••••••••">
                    </div>
                    <div class="mb-3">
                        <label for="sync-frequency" class="form-label">Auto Sync Frequency</label>
                        <select id="sync-frequency" class="form-select">
                            <option value="1">Every 1 hour</option>
                            <option value="3">Every 3 hours</option>
                            <option value="6" selected>Every 6 hours</option>
                            <option value="12">Every 12 hours</option>
                            <option value="24">Every 24 hours</option>
                        </select>
                    </div>
                    <button class="btn btn-primary">Update ERP Settings</button>
                </div>
            </div>

            {{-- Automated Reminder Rules --}}
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-lightning-charge text-success fs-4"></i>
                    <h5 class="mb-0">Automated Reminder Rules</h5>
                </div>
                <div class="card-body">
                    <div class="p-3 mb-3 bg-success bg-opacity-10 border border-success rounded">
                        <p class="mb-0">Reminders are automatically sent via WhatsApp based on these rules. No manual
                            action
                            needed!</p>
                    </div>

                    <div class="mb-3">
                        <label for="before-due" class="form-label">Days before due date to send first reminder</label>
                        <input type="number" id="before-due" class="form-control" value="7">
                        <small class="text-muted">Sends reminder 7 days before invoice due date</small>
                    </div>

                    <div class="mb-3">
                        <label for="on-due" class="form-label">Send reminder on due date</label>
                        <select id="on-due" class="form-select">
                            <option value="yes" selected>Yes</option>
                            <option value="no">No</option>
                        </select>
                        <small class="text-muted">Sends reminder on the invoice due date</small>
                    </div>

                    <div class="mb-3">
                        <label for="after-due-1" class="form-label">Days after overdue for first follow-up</label>
                        <input type="number" id="after-due-1" class="form-control" value="3">
                        <small class="text-muted">Sends first overdue reminder 3 days after due date</small>
                    </div>

                    <div class="mb-3">
                        <label for="after-due-2" class="form-label">Days after overdue for second follow-up</label>
                        <input type="number" id="after-due-2" class="form-control" value="7">
                        <small class="text-muted">Sends second overdue reminder 7 days after due date</small>
                    </div>

                    <div class="mb-3">
                        <label for="max-reminders" class="form-label">Maximum reminders per invoice</label>
                        <input type="number" id="max-reminders" class="form-control" value="5">
                        <small class="text-muted">Stops sending after 5 automatic reminders</small>
                    </div>

                    <button class="btn btn-primary">Save Automation Rules</button>
                </div>
            </div>

        </div>
    </div>
@endsection
