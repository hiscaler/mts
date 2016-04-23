<?php

namespace app\modules\admin\widgets;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * 记录操作按钮部件
 */
class MenuButtons extends Widget {

    public $outerTag = 'ul';
    public $outerHtmlOptions = ['class' => 'tasks'];
    public $innerTag = 'li';
    public $items = [];

    public function run() {
        $output = Html::beginTag('div', ['id' => 'menu-buttons']);
        $output .= Html::beginTag($this->outerTag, $this->outerHtmlOptions);
        foreach ($this->items as $i => $item) {
            if (isset($item['visible']) && $item['visible'] === false) {
                unset($this->items[$i]);
                continue;
            }
        }
        $max = count($this->items) - 1;
        foreach ($this->items as $i => $item) {
            $linkHtmlOptions = isset($item['htmlOptions']) ? $item['htmlOptions'] : [];
            $innerHtmlOptions = [];
            if ($max == 0) {
                $innerHtmlOptions['class'] = 'first-last';
            } else {
                if ($i == 0) {
                    $innerHtmlOptions['class'] = 'first';
                } else if ($i == $max) {
                    $innerHtmlOptions['class'] = 'last';
                }
            }
            if ($item['url'] == '#') {
                $item['url'] = 'javascript:;';
                if (isset($innerHtmlOptions['class'])) {
                    $innerHtmlOptions['class'] .= ' btn-search-form';
                } else {
                    $innerHtmlOptions['class'] = 'btn-search-form';
                }
            }
            $output .= Html::beginTag($this->innerTag, $innerHtmlOptions);
            $output .= Html::a($item['label'], $item['url'], $linkHtmlOptions);
            $output .= Html::endTag($this->innerTag);
        }
        $output .= Html::endTag($this->outerTag);
        $output .= Html::endTag('div');
        $this->getView()->registerJs('jQuery(document).on("click", ".btn-search-form", function(){$(this).toggleClass("active");$(".search-form").toggle(); return false;});');

        return $output;
    }

}
