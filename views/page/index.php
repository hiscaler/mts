<?php
$this->title = $data['title'];
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="page-article" class="white-bg">
    <?= $data['content'] ?>
</div>