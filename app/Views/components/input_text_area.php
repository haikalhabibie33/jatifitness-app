<?php
/*
  $label
  $name
  $value
*/
?>
<div class="mb-4">
    <label class="form-label fw-semibold"><?= esc($label) ?></label>
    <textarea class="form-control"
              name="<?= esc($name) ?>"
              rows="4"><?= esc($value ?? '') ?></textarea>
</div>
