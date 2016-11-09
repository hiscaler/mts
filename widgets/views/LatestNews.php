<div class="widget-common">
    <div class="hd">最近更新</div>
    <div class="bd">
        <ul class="data-list">
            <?php foreach ($items as $item): ?>
                <li>
                    <em>[ <?= $item['category_name'] ?> ]</em>
                    <a title="<?= $item['title'] ?>" href="<?= \yii\helpers\Url::toRoute(['news/view', 'category' => $item['category_id'], 'id' => $item['id']]) ?>"><?= $item['title'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
