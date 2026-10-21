<?php
/*
  $label
  $name
  $value
*/
?>
<div class="mb-4">
    <label class="form-label fw-semibold"><?= esc($label) ?></label>
    <input type="text"
           class="form-control"
           name="<?= esc($name) ?>"
           value="<?= esc($value ?? '') ?>">
</div>
