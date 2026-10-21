<?= $this->extend('pages/admin/layout/template') ?>

<?= $this->section('content') ?>

<div class="container py-4">

  <!-- PAGE TITLE -->
  <h2 class="fw-bold mb-4">Member Management</h2>

  <div class="card shadow-sm bg-light mb-3 p-3">
    <div class="card-body row g-3">

      <!-- ACTION BAR -->
        <div class="col-md-6 input-group" style="width: 380px;">
          <span class="input-group-text">
            <i class="bi bi-search"></i>
          </span>
          <input
            type="text"
            data-testid="search-member-field"
            class="form-control"
            placeholder="Search member..."
            id="searchMember">
        </div>
    
          <div class="col-md-2 d-grid">
            <button class="btn btn-success" data-testid="export-to-csv">
              <i class="bi bi-file-earmark-arrow-down me-1"></i>
              Export CSV
            </button>
          </div>

          <div class="col-md-2 d-grid">
            <button
              class="btn btn-danger"
              data-bs-toggle="modal"
              data-bs-target="#addMemberModal"
              data-testid="add-member-modal-btn"
              >
              <i class="bi bi-person-plus me-1"></i>
              Add Member
            </button>
          </div>
    

    </div>
  </div>

  <!-- TABLE -->
  <div class="card shadow-sm">
    <div class="card-body p-0">

      <?php if (empty($members ?? [])): ?>
        <div class="p-4 text-center text-muted" data-testid="membership-data-table-empty">
          No member data available.
        </div>
      <?php else: ?>

        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle mb-0"
                 data-testid="membership-data-table"
                  >
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Type</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($members as $i => $m): ?>

                <tr data-testid="membership-data-table-row">
                  <!-- ID -->
                  <td data-testid="td-number"><?= $i + 1 ?></td>

                  <!-- NAME -->
                  <td class="fw-semibold" data-testid="td-name">
                    <?= esc($m['name']) ?>
                  </td>

                  <!-- MEMBER TYPE -->
                  <td data-testid="td-memberType">
                    <span class="badge <?= $m['member_type'] === 'STUDENT' ? 'bg-primary' : 'bg-secondary' ?>">
                      <?= esc($m['member_type']) ?>
                    </span>
                  </td>

                  <!-- PHONE -->
                  <td data-testid="td-phone"><?= esc($m['phone'] ?? '-') ?></td>

                  <!-- EMAIL -->
                  <td data-testid="td-email"><?= esc($m['email'] ?? '-') ?></td>

                  <!-- MEMBER STATUS -->
                  <td data-testid="td-status">
                    <?php if (isset($m['is_active'])): ?>
                      <?php
                        $status = $m['is_active'];
                        $badge  = match ($status) {
                          '1'     => 'bg-success',
                          '0'    => 'bg-danger',
                          default      => 'bg-secondary',
                        };
                      ?>
                      <span class="badge <?= $badge ?>">
                        <?= esc($status === '1' ? 'ACTIVE' : 'INACTIVE') ?>
                      </span>
                      <?php else: ?>
                        <span class="badge bg-secondary">No Status</span>
                    <?php endif; ?>
                  </td>

                  <!-- ACTIONS -->
                  <td class="text-center">
                    <!-- VIEW MEMBER -->
                    <button
                      class="btn btn-sm btn-outline-primary"
                      title="View Member"
                      data-bs-toggle="modal"
                      data-bs-target="#memberModal"
                      data-id="<?= esc($m['id']) ?>"
                      data-name="<?= esc($m['name']) ?>"
                      data-dob="<?= esc($m['date_of_birth']) ?>"
                      data-target="<?= esc($m['member_type']) ?>"
                      data-gender="<?= esc($m['gender']) ?>"
                      data-phone="<?= esc($m['phone']) ?>"
                      data-email="<?= esc($m['email']) ?>"
                      data-address="<?= esc($m['address']) ?>"
                      data-testid="view-member-details-btn"
                      >
                      <i class="bi bi-eye"></i>
                    </button>

                    <!-- EDIT MEMBER -->
                    <button
                      class="btn btn-sm btn-outline-warning"
                      title="Edit Member"
                      data-bs-toggle="modal"
                      data-bs-target="#editMemberModal"
                      data-id="<?= esc($m['id']) ?>"
                      data-name="<?= esc($m['name']) ?>"
                      data-dob="<?= esc($m['date_of_birth']) ?>"
                      data-target="<?= esc($m['member_type']) ?>"
                      data-gender="<?= esc($m['gender']) ?>"
                      data-phone="<?= esc($m['phone']) ?>"
                      data-email="<?= esc($m['email']) ?>"
                      data-address="<?= esc($m['address']) ?>"
                      data-active="<?= esc($m['is_active']) ?>"
                      data-testid="edit-member-details-btn"
                      >
                      <i class="bi bi-pencil"></i>
                    </button>

                    <!-- VIEW MEMBERSHIP -->
                    <button
                      class="btn btn-sm btn-outline-info"
                      title="View Membership"
                      data-bs-toggle="modal"
                      data-bs-target="#viewMembershipModal"
                      data-id="<?= esc($m['id']) ?>"
                      data-name="<?= esc($m['name']) ?>"
                      data-member-type="<?= esc($m['member_type']) ?>"
                      data-membership='<?= esc(json_encode($m['membership'] ?? []), 'attr') ?>'
                      data-testid="view-membership-details-btn"
                      >
                      <i class="bi bi-card-list"></i>
                    </button>

                  <!-- ADD MEMBERSHIP -->
                    <button
                      class="btn btn-sm btn-danger"
                      data-bs-toggle="modal"
                      data-bs-target="#addMembershipModal"

                      data-id="<?= esc($m['id']) ?>"
                      data-name="<?= esc($m['name']) ?>"
                      data-type="<?= strtoupper(trim($m['member_type'])) ?>"
                      data-testid="add-memberships-btn"
                      title="Add Membership">
                      <i class="bi bi-plus-circle"></i>
                    </button>
                  </td>

                </tr>

              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      <?php endif; ?>

    </div>
  </div>

</div>

<!-- MODALS -->
<?= $this->include('pages/admin/member_data/modals/add-member') ?>
<?= $this->include('pages/admin/member_data/modals/edit-member') ?>
<?= $this->include('pages/admin/member_data/modals/delete-member') ?>
<?= $this->include('pages/admin/member_data/modals/view-member') ?>
<?= $this->include('pages/admin/member_data/modals/view-membership') ?>
<?= $this->include('pages/admin/member_data/modals/add-membership') ?>

<script> window.memberUpdateBaseUrl = "<?= base_url('admin/member/update') ?>"</script>
<script>window.addMembershipBaseUrl ="<?= base_url('admin/membership/store') ?>"</script>
<script src="<?= base_url('assets/js/admin-member/add-membership.js') ?>"></script>
<script src="<?= base_url('assets/js/admin-member/edit-member-data.js') ?>"></script>
<script src="<?= base_url('assets/js/admin-member/view-member-data-details.js') ?>"></script>
<script src="<?= base_url('assets/js/admin-member/view-member-membership.js') ?>"></script>

<?= $this->endSection() ?>