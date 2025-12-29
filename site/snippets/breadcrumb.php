<?php if($site->breadcrumb()->count() > 1): ?>
<nav aria-label="breadcrumb">
  <ol>
    <?php foreach($site->breadcrumb() as $crumb): ?>
    <li>
      <?php if($crumb->isActive()): ?>
        <span aria-current="page">
          <?= html($crumb->breadcrumbTitle()->or($crumb->title())) ?>
        </span>
      <?php else: ?>
        <a href="<?= $crumb->url() ?>">
          <?= html($crumb->breadcrumbTitle()->or($crumb->title())) ?>
        </a>
      <?php endif ?>
    </li>
    <?php endforeach ?>
  </ol>
</nav>
<?php endif ?>
