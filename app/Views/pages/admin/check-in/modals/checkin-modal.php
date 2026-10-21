<div class="modal fade" id="checkinModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="ciTitle">Confirm Action</h5>
      </div>

      <form method="POST" action="<?= base_url('/admin/checkin/confirm') ?>">
        <div class="modal-body">

          <p><strong>Name:</strong> <span id="ciName"></span></p>
          <p><strong>Phone:</strong> <span id="ciPhone"></span></p>

          <input type="hidden" name="token" id="ciToken">
          <input type="hidden" id="ciState">

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancel
          </button>

          <button id="ciSubmitBtn" class="btn btn-success">
            Confirm
          </button>
        </div>

      </form>

    </div>
  </div>
</div>