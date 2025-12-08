<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>@yield('title', 'AR System')</title>

      <!-- Bootstrap CSS -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Font Awesome 5 -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

      <!-- DataTables Bootstrap 5 CSS -->
      <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
      <style>
            body {
                  background: #f9fafb;
            }

            .sidebar {
                  width: 240px;
                  min-height: 100vh;
            }

            .company-card {
                  border: 1px solid #e5e7eb;
                  border-radius: 10px;
                  transition: 0.2s;
            }

            .company-card:hover {
                  border-color: #d1d5db;
            }

            .icon-box {
                  background: #dbeafe;
                  padding: 12px;
                  border-radius: 8px;
            }

            .company-title {
                  font-size: 20px;
                  font-weight: 600;
                  color: #111827;
            }

            .company-sub {
                  color: #6b7280;
            }

            .btn-blue {
                  background: #2563eb;
                  color: #fff;
            }

            .btn-blue:hover {
                  background: #1e40af;
                  color: #fff;
            }
      </style>

      @stack('styles')
</head>

<body>

      <div class="d-flex">

            <!-- Sidebar -->
            @include('layouts.sidebar')

            <div class="flex-grow-1">

                  <!-- Navbar -->
                  @include('layouts.navbar', ['companyName'=> $companyName ?? 'Acme Corporation'])

                  <!-- Main Content -->
                  <main class="p-4">
                        @yield('content')
                  </main>

            </div>
      </div>

      <!-- Bootstrap JS -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
      <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
      <!-- DataTables JS -->
      <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
      <script>
            $(document).ready(function() {
                  $('.display').each(function() {
                        $(this).DataTable({
                              pageLength: 5,
                              lengthMenu: [5, 10, 25, 50],
                              columnDefs: [{
                                          orderable: false,
                                          targets: -1
                                    } // Last column non-orderable
                              ]
                        });
                  });
            });
      </script>
      @stack('scripts')
</body>

</html>