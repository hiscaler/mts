<?php
$this->title = $categoryName;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="news-list">
    <?php foreach ($items as $item): ?>
        <div class="news-item">
            <div class="title">
                <span><?= date('Y-m-d', $item['published_at']) ?></span>
                <a href="<?= yii\helpers\Url::toRoute(['news/view', 'category' => $item['category_id'], 'id' => $item['id']]) ?>"><?= $item['title'] ?></a>
            </div>
            <div class="summary"><?= $item['description'] ?></div>
        </div>
    <?php endforeach; ?>

    <?=
    yii\widgets\LinkPager::widget([
        'pagination' => $pagination,
    ]);
    ?>

</div>