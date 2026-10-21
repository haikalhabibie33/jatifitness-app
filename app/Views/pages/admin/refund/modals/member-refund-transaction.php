<div class="modal fade" id="memberRefundModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Member Refund</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="memberRefundTransactionId" name="transaction_id">

                <p><strong>Name:</strong> <span id="memberRefundCustomerName">-</span></p>
                <p><strong>Phone:</strong> <span id="memberRefundCustomerPhone">-</span></p>
                <p><strong>Plan:</strong> <span id="memberRefundPlanName">-</span></p>
                <p><strong>Total:</strong> <span id="memberRefundTotalAmount">Rp -</span></p>
            </div>

            <div class="modal-footer">
                <form id="memberRefundForm" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" id="memberRefundTransactionIdField" name="transaction_id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Refund</button>
                </form>
            </div>
        </div>
    </div>
</div>