<?= $this->include('pages/admin/partials/head') ?>
<?= $this->include('pages/admin/partials/sidebar') ?>

<!-- Konten kanan -->
<main class="admin-main">
  <div class="admin-content">
    <?php if (!empty($breadcrumb)): ?>
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
          <?php foreach ($breadcrumb as $i => $item): ?>
            <?php if (!empty($item['url']) && $i !== array_key_last($breadcrumb)): ?>
              <li class="breadcrumb-item">
                <a href="<?= esc($item['url']) ?>"><?= esc($item['label']) ?></a>
              </li>
            <?php else: ?>
              <li class="breadcrumb-item active" aria-current="page">
                <?= esc($item['label']) ?>
              </li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ol>
      </nav>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
  </div>
</main>

</div> <!-- tutup .admin-wrapper -->

<?= $this->include('pages/admin/partials/scripts') ?>