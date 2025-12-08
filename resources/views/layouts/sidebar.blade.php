<aside class="d-none d-md-block bg-white border-end sidebar">
      <div class="p-4 border-bottom">
            <h5>AR Reminder</h5>
            <small class="text-muted">WhatsApp Edition</small>
      </div>

      <nav class="p-3">
            @php
            $menuItems = [
            ['id'=>'dashboard','label'=>'Dashboard','url'=>'/dashboard','icon'=>'fas fa-tachometer-alt'],
            ['id'=>'customers','label'=>'Customers','url'=>'/customer','icon'=>'fas fa-users'],
            ['id'=>'invoices','label'=>'Invoices','url'=>'/dashboard','icon'=>'fas fa-file-invoice'],
            ['id'=>'whatsapp','label'=>'WhatsApp','url'=>'/dashboard','icon'=>'fab fa-whatsapp'],
            ['id'=>'erp-sync','label'=>'ERP Sync','url'=>'/dashboard','icon'=>'fas fa-sync-alt'],
            ['id'=>'settings','label'=>'Settings','url'=>'/dashboard','icon'=>'fas fa-cog'],
            ];
            $currentPage = $currentPage ?? 'dashboard';
            @endphp

            <ul class="list-unstyled">
                  @foreach($menuItems as $item)
                  <li class="mb-1">
                        <a href="{{$item['url']}}" class="d-flex align-items-center gap-2 px-3 py-2 rounded {{ $currentPage == $item['id'] ? 'bg-primary bg-opacity-10 text-primary' : 'text-dark text-decoration-none' }}">
                              <i class="{{ $item['icon'] }}"></i>
                              {{ $item['label'] }}
                        </a>
                  </li>
                  @endforeach
            </ul>
      </nav>
</aside>