<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3">
      <div class="container-fluid d-flex justify-content-between align-items-center">

            <!-- Left: Company Info -->
            <div class="d-flex align-items-center gap-3">
                  <div class="bg-primary bg-opacity-10 rounded p-2">
                        <i class="fas fa-building" style="font-size: 1.2rem;"></i>
                  </div>
                  <div>
                        <h6 class="mb-0">{{ $companyName ?? 'Company Name' }}</h6>
                        <small class="text-muted d-none d-sm-block">Accounts Receivable System</small>
                  </div>
            </div>

            <!-- Right: Buttons -->
            <div class="d-flex gap-2 align-items-center">
                  <a href="{{url('company')}}" class="btn btn-outline-secondary btn-sm d-none d-sm-flex">
                        <i class="fas fa-sync-alt me-1" style="margin-top: 5px;"></i>
                        Switch Company
                  </a>
                  <a href="{{url('login')}}" class="btn btn-outline-secondary btn-sm d-none d-sm-flex">
                        <i class="fas fa-sign-out-alt me-1" style="margin-top: 5px;"></i>
                        Logout
                  </a>

                  <!-- Mobile Menu -->
                  <div class="dropdown d-block d-md-none">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                              <i class="fas fa-bars"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end mt-2">
                              <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{url('company')}}">
                                          <i class="fas fa-sync-alt me-2" style="margin-top: 5px;"></i>
                                          Switch Company
                                    </a>
                              </li>
                              <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{url('login')}}">
                                          <i class="fas fa-sign-out-alt me-2" style="margin-top: 5px;"></i>
                                          Logout
                                    </a>
                              </li>
                        </ul>
                  </div>

            </div>
      </div>
</nav>