<div class="modal fade" id="companyModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title">Add / Edit Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                        <form id="companyForm">
                              <input type="hidden" id="id">
                              <div class="mb-3">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter company name">
                                    <small class="text-danger error-text" id="error_name"></small>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="contact@company.com">
                                    <small class="text-danger error-text" id="error_email"></small>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="+1 555-0100">
                                    <small class="text-danger error-text" id="error_phone"></small>
                              </div>
                              <div class="mb-3">
                                    <label class="form-label">Old Company ID</label>
                                    <input type="number" class="form-control" id="company_id" name="company_id" placeholder="1, 2, 3">
                                    <small class="text-danger error-text" id="error_company_id"></small>
                              </div>
                        </form>
                  </div>
                  <div class="modal-footer">
                        <button class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-blue" id="createCompanyBtn">Create Company</button>
                        <button class="btn btn-blue" id="updateCompanyBtn" style="display:none;">Update Company</button>
                  </div>
            </div>
      </div>
</div>