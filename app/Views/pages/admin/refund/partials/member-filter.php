<form method="get" class="mb-4">
    <input type="hidden" name="tab" value="casual">

    <div class="card shadow-sm bg-light mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label fw-bold">Search</label>
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Customer name"
                        value="<?= esc($_GET['search'] ?? '') ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">Method</label>
                    <select name="method" class="form-select">
                        <option value="">All</option>
                        <option value="CASH" <?= ($_GET['method'] ?? '') === 'CASH' ? 'selected' : '' ?>>Cash</option>
                        <option value="QRIS" <?= ($_GET['method'] ?? '') === 'QRIS' ? 'selected' : '' ?>>QRIS</option>
                        <option value="TRANSFER" <?= ($_GET['method'] ?? '') === 'TRANSFER' ? 'selected' : '' ?>>Transfer</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">From</label>
                    <input
                        type="text"
                        name="from"
                        class="form-control"
                        placeholder="Start Date"
                        onfocus="this.type='date'"
                        onblur="if(!this.value)this.type='text'"
                        value="<?= esc($_GET['from'] ?? '') ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold">To</label>
                    <input
                        type="text"
                        name="to"
                        class="form-control"
                        placeholder="End Date"
                        onfocus="this.type='date'"
                        onblur="if(!this.value)this.type='text'"
                        value="<?= esc($_GET['to'] ?? '') ?>">
                </div>

                <div class="col-md-1 d-grid">
                    <button class="btn btn-primary">Filter</button>
                </div>
                <div class="col-md-1 d-grid">
                    <a class="btn btn-danger"
                       href="<?= site_url('admin/refund?tab=member') ?>">Reset</a>
                </div>

            </div>
        </div>
    </div>
</form>