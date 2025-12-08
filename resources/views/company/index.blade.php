<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Select Company</title>

      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Font Awesome 5 -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

      <style>
            body {
                  background: #f9fafb;
            }

            .company-card {
                  border: 1px solid #e5e7eb;
                  border-radius: 10px;
                  background: #fff;
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
</head>

<body>

      <div class="min-vh-100 d-flex align-items-center justify-content-center p-4">
            <div class="w-100" style="max-width: 900px;">

                  <!-- Heading -->
                  <div class="text-center mb-5">
                        <h2 class="mb-1" style="color:#111827;">Select Company</h2>
                        <p style="color:#6b7280;">Choose a company to manage or add a new one</p>
                  </div>

                  <!-- COMPANY LIST -->
                  <div class="mb-4">

                        <!-- Repeatable Company Card -->
                        <div class="company-card p-4 mb-3">
                              <div class="d-flex align-items-center justify-content-between">

                                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                                          <div class="icon-box">
                                                <i class="fas fa-building" style="font-size: 1.2rem; color: #2563eb;"></i>
                                          </div>

                                          <div class="flex-grow-1">
                                                <div class="company-title">Acme Corporation</div>
                                                <div class="company-sub">
                                                      142 customers • 487 invoices • Created 2024-01-15
                                                </div>
                                          </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                          <a href="/dashboard" class="btn btn-blue px-3">Open Workspace</a>
                                          <a class="px-3" href="#" data-bs-toggle="modal" data-bs-target="#addCompanyModal" style="margin-top:5px;">
                                                <i class="fas fa-edit" style="color: blue;"></i>
                                          </a>
                                          <a class="px-3" href="#" data-bs-toggle="modal" data-bs-target="#deleteCompanyModal" style="margin-top:5px;">
                                                <i class="fas fa-trash" style="color: red;"></i>
                                          </a>
                                    </div>

                              </div>
                        </div>

                        <!-- Repeat 2 -->
                        <div class="company-card p-4 mb-3">
                              <div class="d-flex align-items-center justify-content-between">

                                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                                          <div class="icon-box">
                                          <i class="fas fa-building" style="font-size: 1.2rem; color: #2563eb;"></i>
                                          </div>

                                          <div class="flex-grow-1">
                                                <div class="company-title">Global Enterprises</div>
                                                <div class="company-sub">
                                                      98 customers • 325 invoices • Created 2024-03-22
                                                </div>
                                          </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                          <a href="/dashboard" class="btn btn-blue px-3">Open Workspace</a>
                                          <a class="px-3" href="#" data-bs-toggle="modal" data-bs-target="#addCompanyModal" style="margin-top:5px;">
                                                <i class="fas fa-edit" style="color: blue;"></i>
                                          </a>
                                          <a class="px-3" href="#" data-bs-toggle="modal" data-bs-target="#deleteCompanyModal" style="margin-top:5px;">
                                                <i class="fas fa-trash" style="color: red;"></i>
                                          </a>
                                    </div>

                              </div>
                        </div>

                        <!-- Repeat 3 -->
                        <div class="company-card p-4 mb-3">
                              <div class="d-flex align-items-center justify-content-between">

                                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                                          <div class="icon-box">
                                          <i class="fas fa-building" style="font-size: 1.2rem; color: #2563eb;"></i>
                                          </div>

                                          <div class="flex-grow-1">
                                                <div class="company-title">Tech Solutions Inc</div>
                                                <div class="company-sub">
                                                      203 customers • 612 invoices • Created 2024-05-10
                                                </div>
                                          </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                          <a href="/dashboard" class="btn btn-blue px-3">Open Workspace</a>
                                          <a class="px-3" href="#" data-bs-toggle="modal" data-bs-target="#addCompanyModal" style="margin-top:5px;">
                                                <i class="fas fa-edit" style="color: blue;"></i>
                                          </a>
                                          <a class="px-3" href="#" data-bs-toggle="modal" data-bs-target="#deleteCompanyModal" style="margin-top:5px;">
                                                <i class="fas fa-trash" style="color: red;"></i>
                                          </a>
                                    </div>

                              </div>
                        </div>

                  </div>

                  <!-- ADD NEW COMPANY BUTTON -->
                  <button class="btn btn-blue w-100" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
                        <i class="fas fa-plus" style="color: #fff;"></i>
                        Add New Company
                  </button>

                  <!-- INCLUDE MODAL -->
                  @include('company/components.company_form')

            </div>
      </div>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>