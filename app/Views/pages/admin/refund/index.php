<?= $this->extend('pages/admin/layout/template') ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <h2 class="fw-bold mb-4">Refund Processing</h2>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a 
                class="nav-link <?= $activeTab === 'casual' ? 'active' : '' ?>"
                href="<?= base_url('admin/refund?tab=casual') ?>"
            >
                Casual
            </a>
        </li>
        <li class="nav-item">
            <a 
                class="nav-link <?= $activeTab === 'member' ? 'active' : '' ?>"
                href="<?= base_url('admin/refund?tab=member') ?>"
            >
                Member
            </a>
        </li>
    </ul>

    <?php if ($activeTab === 'casual'): ?>
        <?= view('pages/admin/refund/partials/casual-filter') ?>
        <?= view('pages/admin/refund/partials/casual-table', [
            'transactions' => $transactions,
            'pager' => $pager,
        ]) ?>

        <?= view('pages/admin/refund/modals/refund-transaction') ?>
        <?= view('pages/admin/refund/modals/refund-details') ?>

        <script>
            window.refundCasualBaseUrl = "<?= base_url('admin/refund/casual') ?>";
        </script>
        <script src="<?= base_url('assets/js/admin-refund/refund-casual.js') ?>"></script>
        <script src="<?= base_url('assets/js/admin-refund/refund-detail-casual.js') ?>"></script>

    <?php elseif ($activeTab === 'member'): ?>
      <?= view('pages/admin/refund/partials/member-filter') ?>
      <?= view('pages/admin/refund/partials/member-table', [
        'transactions' => $transactions,
        'pager' => $pager
      ]) ?>
        
      <?= view('pages/admin/refund/modals/member-refund-transaction') ?>
      <?= view('pages/admin/refund/modals/member-refund-details') ?>
        
    <script>
        window.refundMemberBaseUrl = "<?= base_url('admin/refund/member') ?>";
    </script>
    <script src="<?= base_url('assets/js/admin-refund/refund-member.js') ?>"></script>
    <script src="<?= base_url('assets/js/admin-refund/refund-detail-member.js') ?>"></script>

    <?php endif; ?>
</div>

<?= $this->endSection() ?>