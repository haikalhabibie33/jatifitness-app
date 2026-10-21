<?= $this->extend('pages/admin/layout/template') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-3 py-md-4">
  <div class="mx-auto" style="max-width: 1000px;">

    <h2 class="fw-bold mb-4">Gym Check-In</h2>

    <div class="card shadow-sm bg-light">
      <div class="card-body">
        <!-- ✅ ACTION BAR -->
        <div class="row g-3 mb-4">
    
          <div class="col-12 d-flex flex-wrap gap-2">
            <button class="btn btn-outline-primary" onclick="startScanner()">
              📷 Scan QR
            </button>
            <button class="btn btn-danger" onclick="stopScanner()">
              🛑 Stop Scanner
            </button>
          </div>
          
          <div class="col-12 col-md-8">
            <form method="GET" action="<?= base_url('/admin/checkin') ?>" class="d-flex gap-2 flex-grow-1">
              <span class="input-group-text">🔑</span>
              <input type="text" name="token" id="tokenInput"
                     class="form-control" placeholder="Scan QR or Paste Token">
              <button class="btn btn-outline-primary">Load</button>
            </form>
          </div>
        </div>

      </div>
    </div>


    <!-- ✅ QR SCANNER AREA -->
    <div id="qr-reader" style="display:none; max-width:400px;" class="mb-4"></div>

    <!-- ✅ RESULTS -->
    <?php if (!empty($searchResults)): ?>

    <div class="card shadow-sm">
      <div class="card-body">

        <h6 class="fw-bold mb-3">Check-In Candidates</h6>

        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">

            <thead class="table-light">
              <tr>
                <th>Name</th>
                <th>Contacts</th>
                <th>Access</th>
                <th>Type</th>
                <th>Status</th>
                <th width="160">Action</th>
              </tr>
            </thead>

            <tbody>
            <?php foreach ($searchResults as $item):
                $data = $item['data'] ?? [];

                if ($item['type'] === 'MEMBER') {
                    $isActive  = ($data['status'] ?? '') === 'ACTIVE';
                    $isExpired = false;
                } else {
                    $isActive  = in_array($data['status'] ?? '', ['UNUSED','ACTIVE']);
                    $isExpired = !empty($data['expires_at']) &&
                                 strtotime($data['expires_at']) < time();
                }
            ?>
              <tr>
                <!-- Action button -->
                <td><?= esc($data['name'] ?? '-') ?></td>

                <td>
                  <?= esc($data['phone'] ?? '-') ?><br>
                  <small class="text-muted"><?= esc($data['email'] ?? '-') ?></small>
                </td>
                <td>
                  <?php if ($item['type'] === 'MEMBER'): ?>
                    <span><?= esc($data['plan_name']) ?></span><br>
                    <small><?= esc($data['start_date'] . ' - ' . $data['end_date']) ?></small>
                  <?php elseif ($item['type'] === 'CASUAL'): ?>
                    <span><?= esc($data['plan_name']) ?></span><br>
                    <small><?= esc($data['qty']) . ' x Used' ?></small>
                  <?php endif; ?>
                </td>

                <td>
                  <span class="badge <?= $item['type'] === 'MEMBER' ? 'bg-primary' : 'bg-secondary' ?>">
                    <?= $item['type'] ?>
                  </span><br>
    
                  <?php if ($item['type'] === 'MEMBER'): ?>
                      <span class="badge <?= $data['member_type'] === 'REGULAR' ? 'bg-warning' : 'bg-danger' ?>">
                        <?= esc($data['member_type'] ?? '-') ?>
                      </span>
                  <?php elseif ($item['type'] === 'CASUAL'): ?>
                    <span class="badge <?= $data['target_type'] === 'REGULAR' ? 'bg-warning' : 'bg-danger' ?>">
                      <?= esc($data['target_type']) ?>
                    </span>
                  <?php endif; ?>
                </td>

                <td>
                  <?php
                    $status = $data['status'] ?? '-';
                    $badge = match ($status) {
                        'ACTIVE' => 'bg-success',
                        'UNUSED' => 'bg-warning text-dark',
                        'EXPIRED','SUSPENDED' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                  ?>
                  <span class="badge <?= $badge ?>">
                    <?= esc($status) ?>
                  </span>
                </td>

                <td>
                <?php if (!$isActive): ?>
                    <span class="text-danger small">Not Active</span>
                <?php elseif ($isExpired): ?>
                    <span class="text-danger small">Expired</span>
                <?php else: ?>
                    <?php if ($item['state'] === 'IN'): ?>
                        <button class="btn btn-success w-100 btn-sm"
                            onclick="confirmCheckIn(
                                '<?= esc($data['name']) ?>',
                                '<?= esc($data['phone']) ?>',
                                '<?= esc($data['access_token']) ?>',
                                'IN'
                            )">
                            ✅ Check-In
                        </button>
                    <?php elseif ($item['state'] === 'OUT'): ?>
                        <button class="btn btn-danger w-100 btn-sm"
                            onclick="confirmCheckIn(
                                '<?= esc($data['name']) ?>',
                                '<?= esc($data['phone']) ?>',
                                '<?= esc($data['access_token']) ?>',
                                'OUT'
                            )">
                            🔴 Check-Out
                        </button>
                    <?php else: ?>
                        <span class="text-muted small">✔ Done</span>
                    <?php endif; ?>
                <?php endif; ?>
                </td>

              </tr>
            <?php endforeach ?>
            </tbody>

          </table>
        </div>

      </div>
    </div>

    <?php endif; ?>

  </div>
</div>


<!-- ✅ MODAL -->
<?= view('pages/admin/check-in/modals/checkin-modal') ?>

<!-- ✅ INCLUDE QR LIB -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script src="<?= base_url('assets/js/checkin-qr.js') ?>"></script>

<script>
function confirmCheckIn(name, phone, token, state) {

  const nameEl   = document.getElementById('ciName');
  const phoneEl  = document.getElementById('ciPhone');
  const tokenEl  = document.getElementById('ciToken');
  const titleEl  = document.getElementById('ciTitle');
  const submitBtn = document.getElementById('ciSubmitBtn');

  nameEl.innerText  = name;
  phoneEl.innerText = phone;
  tokenEl.value     = token;

  // 🔥 HANDLE STATE
  if (state === 'OUT') {

    titleEl.innerText = "Confirm Check-Out";
    submitBtn.innerText = "Check-Out";
    submitBtn.classList.remove('btn-success');
    submitBtn.classList.add('btn-danger');

  } else {

    titleEl.innerText = "Confirm Check-In";
    submitBtn.innerText = "Check-In";
    submitBtn.classList.remove('btn-danger');
    submitBtn.classList.add('btn-success');
  }

  new bootstrap.Modal(document.getElementById('checkinModal')).show();
}
</script>

<?= $this->endSection() ?>