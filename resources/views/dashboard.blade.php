@extends('layouts.master')
@section('title', 'Dashboard')

@section('content')
<h4 class="mb-1">Dashboard</h4>
<p class="text-muted mb-4">Overview of your accounts receivable</p>

<div class="row g-3 mb-4">
      <!-- Total Receivables -->
      <div class="col-6 col-lg-3">
            <div class="card p-3">
                  <div class="bg-primary bg-opacity-10 rounded p-2 mb-2" style="width: 40px; text-align: center;color: blue;">
                        <i class="fas fa-dollar-sign"></i>
                  </div>
                  <small class="text-muted">Total Receivables</small>
                  <h5>$423,500</h5>
            </div>
      </div>

      <!-- Overdue Amount -->
      <div class="col-6 col-lg-3">
            <div class="card p-3">
                  <div class="bg-danger bg-opacity-10 rounded p-2 mb-2" style="width: 40px; text-align: center;color: red;">
                        <i class="fas fa-exclamation-triangle"></i>
                  </div>
                  <small class="text-muted">Overdue Amount</small>
                  <h5 class="text-danger">$145,000</h5>
            </div>
      </div>

      <!-- Total Customers -->
      <div class="col-6 col-lg-3">
            <div class="card p-3">
                  <div class="bg-success bg-opacity-10 rounded p-2 mb-2" style="width: 40px; text-align: center;color: green;">
                        <i class="fas fa-users"></i>
                  </div>
                  <small class="text-muted">Total Customers</small>
                  <h5>142</h5>
            </div>
      </div>

      <!-- Overdue Invoices -->
      <div class="col-6 col-lg-3">
            <div class="card p-3">
                  <div class="bg-opacity-10 rounded p-2 mb-2" style=" background-color:rgb(233, 211, 236); width: 40px; text-align: center;color: purple;">
                        <i class="fas fa-file-invoice"></i>
                  </div>
                  <small class="text-muted">Overdue Invoices</small>
                  <h5>32</h5>
            </div>
      </div>
</div>


<!-- Recent Activity & Overdue Accounts -->
<div class="row g-3">
      @include('partials.dashboard_recent_activity')
      @include('partials.dashboard_overdue_accounts')
</div>

@endsection