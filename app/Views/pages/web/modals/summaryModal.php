<div class="modal fade" id="summaryModal">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      
    
    <div class="modal-header">
        <h5 class="modal-title" data-testid="modal-title"></h5>

        <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>



      <div class="modal-body">

      <h6>Package Details</h6>
      <div class="border rounded p-3 mb-3">  
        <ul>
          <li>Category: <strong data-testid="order-category" id="sCategory"></strong></li>
          <li>Package: <strong data-testid="order-package" id="sPackage"></strong></li>
          <li>Quantity: <strong data-testid="order-quantity" id="sQty"></strong></li>
          <li>Total Duration: <strong><span data-testid="order-duration" id="sDuration"></span> days</strong></li>
        </ul>
      </div>

        <h6 class="mt-3">Price Summary</h6>
        <div class="border rounded p-3 mb-3">
        <ul>
          <li>Unit Price: IDR <span data-testid="order-unit-price" id="sUnitPrice"></span></li>
          <li class="fw-bold">Total: IDR <span data-testid="order-total-price" id="sTotalPrice"></span></li>
        </ul>
      </div>

        <h6 class="mt-3">Payment Method</h6>

        <div class="border rounded p-3 mb-3">
            <div class="form-check mb-3">
                <input
                    class="form-check-input"
                    type="radio"
                    name="summary_payment_method"
                    id="summaryPaymentCash"
                    value="CASH"
                    checked
                    data-testid="payment-method-cash"
                >
                <label class="form-check-label ml-2" for="summaryPaymentCash">
                    <strong>Cash</strong>
                    <div class="small text-muted mt-1">
                        Pay directly at the front desk.
                    </div>
                </label>
            </div>

            <div class="form-check mb-3">
                <input
                    class="form-check-input"
                    type="radio"
                    name="summary_payment_method"
                    id="summaryPaymentTransfer"
                    value="TRANSFER"
                    data-testid="payment-method-transfer"
                >
                <label class="form-check-label ml-2" for="summaryPaymentTransfer">
                    <strong>Bank Transfer</strong>
                    <div class="small text-muted mt-1">
                        Transfer payment and upload proof.
                    </div>
                </label>
            </div>

            <div class="form-check">
                <input
                    class="form-check-input"
                    type="radio"
                    name="summary_payment_method"
                    id="summaryPaymentQris"
                    value="QRIS"
                    data-testid="payment-method-qris"
                    disabled
                >
                <label class="form-check-label ml-2" for="summaryPaymentQris">
                    <strong>QRIS</strong>
                    <div class="small text-muted mt-1">
                        Coming soon via payment gateway.
                    </div>
                </label>
            </div>
        </div>

        <h6 class="mt-3">Applicant Information</h6>
        <div class="border rounded p-3 mb-3">
          <ul>
            <li>Name: <strong data-testid="order-name" id="sName"></strong></li>
            <li>Email: <strong data-testid="order-email" id="sEmail"></strong></li>
            <li>Phone: <strong data-testid="order-phone" id="sPhone"></strong></li>
          </ul>
        </div>

        <div id="summaryWarning" class="text-danger mb-3 d-none" data-testid="error-message">
          Please complete all required fields before continuing.
        </div>

        <small class="text-muted" data-testid="order-term">
          By continuing, you agree that this request cannot be edited after payment.
        </small>

      </div>

      
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" data-testid="back-edit-btn">
                ← Back to Edit
            </button>

            <button type="button" class="btn btn-primary" id="confirmPaymentBtn" data-testid="continue-payment-btn">
                Continue to Payment →
            </button>
        </div>


    </div>
  </div>
</div>