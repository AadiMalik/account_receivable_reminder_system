<div class="col-lg-6">
      <div class="card p-3">
            <h6>Recent WhatsApp Activity</h6>
            <div class="mt-3">
                  @php
                  $activities = [
                  ['customer'=>'Acme Industries','message'=>'Reminder sent for overdue invoice','time'=>'2 hours ago','type'=>'sent'],
                  ['customer'=>'Global Retail Co.','message'=>'Payment confirmation received','time'=>'5 hours ago','type'=>'received'],
                  ['customer'=>'TechCorp Solutions','message'=>'Reminder sent for upcoming due date','time'=>'1 day ago','type'=>'sent'],
                  ];
                  @endphp

                  @foreach($activities as $activity)
                  <div class="d-flex align-items-start gap-2 border-bottom pb-2 mb-2">
                        <div class="rounded-circle p-2 mt-1 {{ $activity['type']=='sent' ? 'bg-primary bg-opacity-10 text-primary' : 'bg-success bg-opacity-10 text-success' }}" style="width: 40px;text-align: center;">
                              <i class="fas fa-clock"></i>
                        </div>
                        <div class="flex-grow-1">
                              <p class="mb-0 fw-semibold">{{ $activity['customer'] }}</p>
                              <small class="text-muted d-block">{{ $activity['message'] }}</small>
                              <small class="text-muted">{{ $activity['time'] }}</small>
                        </div>
                  </div>
                  @endforeach
            </div>
      </div>
</div>