<?php
/*
  $src   (relative to base_url)
  $alt
*/
?>

<div class="mb-4">
    <!-- <label class="form-label fw-semibold d-block">
        <i class="bi bi-eye-fill me-1"></i> Page Preview
    </label> -->

    <a href="<?= base_url($src) ?>" target="_blank">
        <img src="<?= base_url($src) ?>"
             class="img-fluid border rounded shadow-sm"
             alt="<?= esc($alt ?? 'Page preview') ?>"
             style="max-height: 420px; width: 100%; object-fit: contain;">
    </a>

    <!-- <small class="text-muted d-block mt-1">
        Click image to view full size
    </small> -->
</div>