@extends('layouts.master')
@section('title', 'ERP Sync')

@push('styles')
<style>
    .stat-card {
        border-radius: 12px;
        background: #fff;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    .icon-box {
        padding: 12px;
        border-radius: 10px;
    }
    .text-purple { color:#6f42c1 !important; }
    .bg-purple { background:#6f42c1 !important; }
</style>
@endpush

@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-sm-row justify-content-between mb-4">
        <div>
            <h4 class="mb-1">ERP Synchronization</h4>
            <p class="text-muted">Sync invoices and customers from your ERP system</p>
        </div>

        <button class="btn btn-primary" style="height: 40px;">
            <i class="fas fa-sync"></i> Sync Now
        </button>
    </div>

    <!-- Status Cards -->
    <div class="row g-3 mb-4">

        <!-- ERP Connection -->
        <div class="col-md-4">
            <div class="stat-card d-flex justify-content-between">
                <div>
                    <p class="text-muted mb-1">ERP Connection</p>
                    <h5 class="m-0 text-success">
                        <i class="fas fa-check-circle"></i> Connected
                    </h5>
                </div>

                <div class="icon-box bg-success bg-opacity-10">
                    <i class="fas fa-database text-success fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Last Sync -->
        <div class="col-md-4">
            <div class="stat-card d-flex justify-content-between">
                <div>
                    <p class="text-muted mb-1">Last Sync</p>
                    <h5 class="m-0">2 hours ago</h5>
                </div>

                <div class="icon-box bg-primary bg-opacity-10">
                    <i class="fas fa-clock text-primary fs-4"></i>
                </div>
            </div>
        </div>

        <!-- Next Auto Sync -->
        <div class="col-md-4">
            <div class="stat-card d-flex justify-content-between">
                <div>
                    <p class="text-muted mb-1">Next Auto Sync</p>
                    <h5 class="m-0">4 hours</h5>
                </div>

                <div class="icon-box" style="background-color:rgb(233, 211, 236); text-align: center;color: purple;">
                    <i class="fas fa-sync-alt fs-4"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Details Row -->
    <div class="row g-4 mb-4">
        
        <!-- ERP System Details -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><strong>ERP System Details</strong></div>

                <div class="card-body">

                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">System:</span>
                        <span class="fw-bold">SAP Business One</span>
                    </div>

                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Status:</span>
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i> Online
                        </span>
                    </div>

                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Auto Sync:</span>
                        <span class="badge bg-primary">Enabled</span>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Sync Frequency:</span>
                        <span class="fw-bold">Every 6 hours</span>
                    </div>

                </div>
            </div>
        </div>

        <!-- Data Synced -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><strong>Data Synced</strong></div>

                <div class="card-body">

                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Invoices:</span>
                        <span class="fw-bold text-success">142 synced</span>
                    </div>

                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Customers:</span>
                        <span class="fw-bold text-success">12 synced</span>
                    </div>

                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Payments:</span>
                        <span class="fw-bold text-success">45 synced</span>
                    </div>

                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Last Update:</span>
                        <span class="fw-bold">Today at 3:00 PM</span>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Notice -->
    <div class="card mb-4">
        <div class="card-body d-flex">

            <div class="icon-box bg-primary bg-opacity-10 me-3" style="height: 50px;">
                <i class="fas fa-database fs-4 text-primary"></i>
            </div>

            <div>
                <h5>How ERP Sync Works</h5>
                <ul class="text-muted mb-0">
                    <li>Invoices and customer data are automatically synced from your ERP</li>
                    <li>You cannot edit invoices here — edit in ERP only</li>
                    <li>Auto sync runs every 6 hours or manually trigger anytime</li>
                    <li>WhatsApp reminders run based on synced invoice data</li>
                </ul>
            </div>

        </div>
    </div>

    <!-- Sync History Table -->
    <div class="card">
        <div class="card-header"><strong>Sync History</strong></div>

        <div class="card-body">

            <table id="syncTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Type</th>
                        <th>Invoices</th>
                        <th>Customers</th>
                        <th>Duration</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>2025-11-19 15:00:00</td>
                        <td><span class="badge bg-secondary">Auto Sync</span></td>
                        <td>142</td>
                        <td>12</td>
                        <td>2.3s</td>
                        <td><span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>Success
                        </span></td>
                    </tr>

                    <tr>
                        <td>2025-11-19 03:00:00</td>
                        <td><span class="badge bg-secondary">Auto Sync</span></td>
                        <td>135</td>
                        <td>5</td>
                        <td>2.5s</td>
                        <td><span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>Success
                        </span></td>
                    </tr>

                    <tr>
                        <td>2025-11-18 15:00:00</td>
                        <td><span class="badge bg-secondary">Auto Sync</span></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0.5s</td>
                        <td><span class="badge bg-danger">
                            <i class="fas fa-times-circle me-1"></i>Failed
                        </span></td>
                    </tr>

                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection
