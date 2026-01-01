@extends('layouts.master')
@section('title', 'ERP Sync')

@push('styles')
<style>
.stat-card { border-radius:12px; background:#fff; padding:20px; box-shadow:0 2px 8px rgba(0,0,0,0.05); }
.icon-box { padding:12px; border-radius:10px; text-align:center; }
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
        <button id="syncBtn" class="btn btn-primary" style="height: 40px;">
            <i class="fas fa-sync"></i> Sync Now
        </button>
    </div>

    <!-- Status Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card d-flex justify-content-between align-items-center">
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

        <div class="col-md-4">
            <div class="stat-card d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Last Sync</p>
                    <h5 class="m-0">{{ $lastUpdate }}</h5>
                </div>
                <div class="icon-box bg-primary bg-opacity-10">
                    <i class="fas fa-clock text-primary fs-4"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Next Auto Sync</p>
                    <h5 class="m-0">4 hours</h5>
                </div>
                <div class="icon-box" style="background-color:rgb(233, 211, 236); color:purple;">
                    <i class="fas fa-sync-alt fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Synced -->
    <div class="row g-4 mb-4">
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

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"><strong>Data Synced</strong></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Invoices:</span>
                        <span class="fw-bold text-success">{{ $dataSynced['invoices'] ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Customers:</span>
                        <span class="fw-bold text-success">{{ $dataSynced['customers'] ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted">Payments:</span>
                        <span class="fw-bold text-success">{{ $dataSynced['payments'] ?? 0 }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Last Update:</span>
                        <span class="fw-bold">{{ $dataSynced['last_update'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sync History Table -->
    <div class="card">
        <div class="card-header"><strong>Sync History</strong></div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
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
                    @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->synced_at }}</td>
                            <td><span class="badge bg-secondary">Auto Sync</span></td>
                            <td>{{ $log->invoices_added + $log->invoices_updated }}</td>
                            <td>{{ $log->customers_added + $log->customers_updated }}</td>
                            <td>{{ round((strtotime(now()) - strtotime($log->synced_at)),2) }}s</td>
                            <td>
                                <span class="badge {{ ($log->invoices_added+$log->invoices_updated) > 0 ? 'bg-success':'bg-danger' }}">
                                    <i class="fas {{ ($log->invoices_added+$log->invoices_updated) > 0 ? 'fa-check-circle':'fa-times-circle' }} me-1"></i>
                                    {{ ($log->invoices_added+$log->invoices_updated) > 0 ? 'Success':'Failed' }}
                                </span>
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
<script>
document.getElementById('syncBtn').addEventListener('click', function(){
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-sync fa-spin"></i> Syncing...';

    fetch("{{ route('erp.sync.run') }}", {
        method:'POST',
        headers:{
            'X-CSRF-TOKEN':'{{ csrf_token() }}',
            'Content-Type':'application/json'
        },
        body: JSON.stringify({ company_id: {{ Auth::user()->company->id }} })
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-sync"></i> Sync Now';
        if(res.success){
            alert(`ERP Sync Completed: ${res.log.invoices} invoices, ${res.log.customers} customers`);
            location.reload();
        }else{
            alert('Sync Failed: '+res.message);
        }
    })
    .catch(err => {
        btn.disabled=false;
        btn.innerHTML = '<i class="fas fa-sync"></i> Sync Now';
        alert('Sync Error');
        console.error(err);
    });
});
</script>
@endpush
