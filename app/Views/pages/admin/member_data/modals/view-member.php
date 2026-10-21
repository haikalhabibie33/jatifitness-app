<div class="modal fade" id="memberModal" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">

                  <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" data-testid="view-member-modal-title">
                      <i class="bi bi-person-badge me-1"></i>
                      Member Detail
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <div class="modal-body">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <strong>Name:</strong>
                        <div id="mName" data-testid="view-member-name"></div>
                      </div>

                      <div class="col-md-6">
                        <strong>Date of Birth:</strong>
                        <div id="mDob" data-testid="view-member-dob"></div>
                      </div>

                      <div class="col-md-6">
                        <strong>Gender:</strong>
                        <div id="mGender" data-testid="view-member-gender"></div>
                      </div>

                      <div class="col-md-6">
                        <strong>Type:</strong>
                        <div id="mTarget" data-testid="view-member-target-type"></div>
                      </div>

                      <div class="col-md-6">
                        <strong>Phone:</strong>
                        <div id="mPhone" data-testid="view-member-phone"></div>
                      </div>

                      <div class="col-md-6">
                        <strong>Email:</strong>
                        <div id="mEmail" data-testid="view-member-email"></div>
                      </div>

                      <div class="col-12">
                        <strong>Address:</strong>
                        <div id="mAddress" data-testid="view-member-address"></div>
                      </div>
                    </div>
                  </div>

                  <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" data-testid="view-member-close-btn">Close</button>
                  </div>

                </div>
              </div>
            </div>