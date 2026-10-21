<div class="modal fade" id="memberRefundDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Member Refund Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" id="memberDetailName" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" id="memberDetailPhone" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="text" id="memberDetailEmail" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Purchase Type</label>
                        <input type="text" id="memberDetailPurchaseType" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Target Type</label>
                        <input type="text" id="memberDetailTargetType" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Plan</label>
                        <input type="text" id="memberDetailPlan" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Payment Method</label>
                        <input type="text" id="memberDetailMethod" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Payment Status</label>
                        <input type="text" id="memberDetailStatus" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Paid At</label>
                        <input type="text" id="memberDetailPaidAt" class="form-control" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Total</label>
                        <input type="text" id="memberDetailTotal" class="form-control" readonly>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button
                    type="button"
                    id="openMemberRefundFromDetailBtn"
                    class="btn btn-danger"
                >
                    Continue Refund
                </button>
            </div>
        </div>
    </div>
</div>