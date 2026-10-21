<?= $this->extend('pages/admin/layout/template') ?>

<?= $this->section('content') ?>

<div class="container py-4">

    <!-- <h2 class="mb-4 fw-bold">Contact Message Management</h2> -->

  <h2 class="fw-bold mb-5">Contact Message Management</h2>      
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="input-group"
             style="width: 380px">
            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>
            <input 
              type="text"
              class="form-control"
              placeholder="Search message..."
              id="SearchContactMessage">
        </div>
    <button
        class="btn btn-success d-inline-flex align-items-center gap-1"
        id="exportToCsvBtn">
        <i class="bi bi-file-earmark-arrow-down"></i>
    Export to CSV
    </button>
  </div>
  
    
    <div class="card shadow-sm pb-3">
        <div class="card-body p-0">

            <?php if (empty($contacts)): ?>
                <div class="p-4 text-center text-muted">
                    No contact message data available.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-nowrap" data-sort="id">#</th>
                                <th class="text-nowrap" data-sort="name">Name</th>
                                <th class="text-nowrap" data-sort="subject">Subject</th>
                                <th class="text-nowrap" data-sort="email">Email</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contacts as $i => $m): ?>
                                <tr>
                                    <td><?= esc($i + 1) ?></td>
                                    <td><?= esc($m['name']) ?></td>
                                    <td><?= esc($m['subject']) ?></td>
                                    <td><?= esc($m['email']) ?></td>
                                    <td class="text-center">
                                        <!-- VIEW (POPUP MODAL) -->
                                         <button
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#contactModal"
                                            data-name="<?= esc($m['name']) ?>"
                                            data-subject="<?= esc($m['subject']) ?>"
                                            data-message="<?= esc($m['message']) ?>"
                                            data-email="<?= esc($m['email']) ?>"
                                            title="View">
                                            <i class="bi bi-eye"></i>
                                          </button>

                                        <button
                                          type="button"
                                          class="btn btn-sm btn-outline-danger"
                                          data-bs-toggle="modal"
                                          data-bs-target="#deleteContact"
                                          data-id="<?= esc($m['id']) ?>"
                                          data-name="<?= esc($m['name']) ?>"
                                          title="Delete">
                                          <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>

            <!-- Modal for viewing contact details -->
            <?= $this->include('pages/admin/contact-message/modals/view-contact') ?>

            <!-- Modal for deleting contact details -->
            <?= $this->include('pages/admin/contact-message/modals/delete-contact') ?>
        </div>
    </div>

</div>
<?= $this->endSection('content') ?>
