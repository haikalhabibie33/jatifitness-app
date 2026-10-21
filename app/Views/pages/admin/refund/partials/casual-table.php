<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Plan</th>
                        <th>Payment</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Time</th>
                        <th width="140">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="8" class="text-center p-4 text-muted">
                                No refundable casual transaction data found
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $i => $t): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($t['name'] ?? '-') ?></td>
                                <td><?= esc($t['plan_name_snapshot']) ?></td>
                                <td><?= esc($t['payment_method']) ?></td>
                                <td><?= esc($t['quantity']) ?> pcs</td>
                                <td>IDR <?= esc($t['total_amount']) ?></td>
                                <td><?= esc($t['paid_at']) ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#refundDetailModal"
                                            data-id="<?= esc($t['id'], 'attr') ?>"
                                            data-name="<?= esc($t['name'] ?? '-', 'attr') ?>"
                                            data-phone="<?= esc($t['phone'] ?? '-', 'attr') ?>"
                                            data-email="<?= esc($t['email'] ?? '-', 'attr') ?>"
                                            data-plan="<?= esc($t['plan_name_snapshot'] ?? '-', 'attr') ?>"
                                            data-method="<?= esc($t['payment_method'] ?? '-', 'attr') ?>"
                                            data-total="<?= number_format((float) ($t['total_amount'] ?? 0), 0, ',', '.') ?>"
                                            data-paid-at="<?= esc($t['paid_at'] ?? '-', 'attr') ?>"
                                            data-type="<?= esc($t['purchase_type'] ?? '-', 'attr') ?>"
                                            data-target="<?= esc($t['target_member_type_snapshot'] ?? '-', 'attr') ?>"
                                            data-status="<?= esc($t['payment_status'] ?? '-', 'attr') ?>"
                                            title="View Details"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#refundModal"
                                            data-id="<?= esc($t['id'], 'attr') ?>"
                                            data-name="<?= esc($t['name'] ?? '-', 'attr') ?>"
                                            data-phone="<?= esc($t['phone'] ?? '-', 'attr') ?>"
                                            data-plan="<?= esc($t['plan_name_snapshot'] ?? '-', 'attr') ?>"
                                            data-total="<?= number_format((float) ($t['total_amount'] ?? 0), 0, ',', '.') ?>"
                                            title="refund"
                                        >
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($pager): ?>
    <div class="d-flex align-items-baseline mt-3 gap-3">
        <div class="text-muted small">
            Showing page <?= $pager->getCurrentPage() ?>
        </div>
        <div>
            <?= $pager->only(['tab', 'search', 'method', 'from', 'to'])->links('default', 'bootstrap_custom') ?>
        </div>
    </div>
<?php endif; ?>
