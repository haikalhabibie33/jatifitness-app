<!-- Deactivate Member Modal -->
<div class="modal fade" id="deleteMemberModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title text-danger">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          Deactivate Member
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p class="mb-1">
          Are you sure you want to deactivate
          <strong id="deleteMemberName"></strong>?
        </p>
        <p class="text-muted mb-0">
          This member will not be able to buy new memberships,
          but existing membership history will remain.
        </p>
      </div>

      <div class="modal-footer">

        <button
          type="button"
          class="btn btn-outline-secondary"
          data-bs-dismiss="modal">
          Cancel
        </button>

        <form
          id="deleteMemberForm"
          method="post"
          action=""
          class="m-0">

          <?= csrf_field() ?>

          <!-- kita tidak delete, hanya set is_active = false -->
          <input type="hidden" name="is_active" value="0">

          <button
            type="submit"
            class="btn btn-danger">
            Yes, Deactivate
          </button>
        </form>

      </div>

    </div>
  </div>
</div>