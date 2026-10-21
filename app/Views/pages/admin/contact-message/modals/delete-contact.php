<div class="modal fade" id="deleteContact" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title text-danger">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          Delete Contact
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p class="mb-0">
          Are you sure you want to delete
          <strong id="deleteMemberName"></strong>?
        </p>
        <p class="text-muted mt-2 mb-0">
          This action cannot be undone.
        </p>
      </div>

      <div class="modal-footer">
        <button type="button"
                class="btn btn-outline-secondary"
                data-bs-dismiss="modal">
          No
        </button>

        <form id="deleteMemberForm" method="post" class="m-0">
          <?= csrf_field() ?>
          <button type="submit" class="btn btn-danger">
            Yes, Delete
          </button>
        </form>
      </div>

    </div>
  </div>
</div>