<div class="col-lg-6">
      <div class="card p-3">
            <h6>Top Overdue Accounts</h6>
            <div class="mt-3">
                  @php
                  $accounts = [
                  ['customer'=>'Digital Marketing Ltd','amount'=>99600,'days'=>45],
                  ['customer'=>'Global Retail Co.','amount'=>78000,'days'=>30],
                  ['customer'=>'Acme Industries','amount'=>45000,'days'=>4],
                  ];
                  @endphp
                  @foreach($accounts as $acc)
                  <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                        <div>
                              <p class="mb-0 fw-semibold">{{ $acc['customer'] }}</p>
                              <small class="text-muted">${{ number_format($acc['amount']) }}</small>
                        </div>
                        <span class="badge bg-danger">{{ $acc['days'] }} days</span>
                  </div>
                  @endforeach
            </div>
      </div>
</div>