@extends('layouts.master')
@section('title', 'Customer')

@section('content')
@push('styles')
@endpush
<div class="p-4">

      <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                  <h4 class="mb-1">Customers</h4>
                  <p class="text-muted mb-0">Manage your customer accounts</p>
            </div>
      </div>

      @php
      $customers = [
      ['id'=>1,'name'=>'Acme Industries','phone'=>'+1 555-0101','balance'=>45000,'overdue'=>45000,'invoices'=>2],
      ['id'=>2,'name'=>'TechCorp Solutions','phone'=>'+1 555-0102','balance'=>89000,'overdue'=>0,'invoices'=>3],
      ['id'=>3,'name'=>'Global Retail Co.','phone'=>'+1 555-0103','balance'=>78000,'overdue'=>78000,'invoices'=>1],
      ['id'=>4,'name'=>'Smart Systems Inc','phone'=>'+1 555-0104','balance'=>23000,'overdue'=>23000,'invoices'=>4],
      ['id'=>5,'name'=>'Digital Marketing Ltd','phone'=>'+1 555-0105','balance'=>99600,'overdue'=>99600,'invoices'=>2],
      ];
      @endphp

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
                                    @foreach($customers as $customer)
                                    <tr>
                                          <td>
                                                <p class="mb-0 fw-semibold">{{ $customer['name'] }}</p>
                                                <small class="text-muted">{{ $customer['invoices'] }} invoices</small>
                                          </td>
                                          <td class="d-none d-sm-table-cell">
                                                <i class="fas fa-comment text-success me-1"></i>
                                                {{ $customer['phone'] }}
                                          </td>
                                          <td>${{ number_format($customer['balance']) }}</td>
                                          <td class="d-none d-lg-table-cell {{ $customer['overdue'] > 0 ? 'text-danger' : 'text-muted' }}">
                                                ${{ number_format($customer['overdue']) }}
                                          </td>
                                          <td class="d-none d-md-table-cell">
                                                @if($customer['overdue'] > 0)
                                                <span class="badge bg-danger">Overdue</span>
                                                @else
                                                <span class="badge bg-success">Current</span>
                                                @endif
                                          </td>
                                          <td>
                                                <!-- <a class="btn btn-outline-secondary btn-sm" href="{{url('customer/detail/'.$customer['id'])}}">View</a> -->
                                                <a class="btn btn-outline-secondary btn-sm" href="{{url('customer/detail/')}}">View</a>
                                          </td>
                                    </tr>
                                    @endforeach
                              </tbody>
                        </table>
                  </div>
            </div>
      </div>
</div>
@endsection