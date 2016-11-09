<?php
$this->title = $data['category_name'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['news/index', 'category' => $data['category_id']]];
$this->params['breadcrumbs'][] = '正文';
?>

<div class="news-detail">
    <div class="title"><?= $data['title'] ?></div>
    <div class="misc">
        <span>发布时间：<?= date('Y-m-d', $data['published_at']) ?></span>
        <span>点击次数：<?= $data['clicks_count'] + 1 ?>&nbsp;次</span>
    </div>
    <div class="title"><?= $data['description'] ?></div>
    <div class="content"><?= $data['content'] ?></div>
</div>