<?php
$links = $pager->links();
$totalPages = count($links);

$currentPage = 1;
foreach ($links as $l) {
    if ($l['active']) {
        $currentPage = (int) $l['title'];
        break;
    }
}

$maxLinks = 5;
$half = floor($maxLinks / 2);

$start = max(1, $currentPage - $half);
$end = min($totalPages, $start + $maxLinks - 1);

if ($end - $start < $maxLinks - 1) {
    $start = max(1, $end - $maxLinks + 1);
}
?>

<nav>
<ul class="pagination pagination-sm">

<?php if ($pager->hasPrevious()) : ?>
    <li class="page-item">
        <a class="page-link" href="<?= $pager->getPrevious() ?>">Prev</a>
    </li>
<?php endif ?>

<?php for ($i = $start; $i <= $end; $i++): ?>
    <?php if (isset($links[$i - 1])): ?>
        <?php $link = $links[$i - 1]; ?>
        <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
            <a class="page-link" href="<?= $link['uri'] ?>">
                <?= $i ?>
            </a>
        </li>
    <?php endif ?>
<?php endfor ?>

<?php if ($pager->hasNext()) : ?>
    <li class="page-item">
        <a class="page-link" href="<?= $pager->getNext() ?>">Next</a>
    </li>
<?php endif ?>

</ul>
</nav>
