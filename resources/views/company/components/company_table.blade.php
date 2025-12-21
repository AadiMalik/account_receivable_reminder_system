@foreach($companies as $company)
<div class="company-card p-3 d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-3">
            <div class="icon-box">
                  <i class="fas fa-building" style="color:#2563eb;"></i>
            </div>
            <div>
                  <div class="company-title">{{ $company->name }}</div>
                  <div class="company-sub">
                        Email: {{ $company->email }} • Phone: {{ $company->phone }} • Created: {{ $company->created_at->format('Y-m-d') }}
                  </div>
            </div>
      </div>
      <div class="d-flex gap-2">
            <a href="{{ url('/company/'.$company->id.'/login') }}" class="btn btn-blue btn-sm">Open Workspace</a>
            <!-- <a href="/dashboard" class="btn btn-blue btn-sm">Open Workspace</a> -->
            <button class="btn btn-light btn-sm editCompanyBtn" data-id="{{ $company->id }}">
                  <i class="fas fa-edit text-blue"></i>
            </button>
            <button class="btn btn-light btn-sm deleteCompanyBtn" data-id="{{ $company->id }}">
                  <i class="fas fa-trash text-danger"></i>
            </button>
      </div>
</div>
@endforeach

@if($companies->isEmpty())
<div class="text-center text-muted mt-3">No companies found. Add a new one!</div>
@endif