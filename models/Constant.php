<?php

namespace app\models;

/**
 * 常量定义
 *
 * @author hiscaler
 */
class Constant
{
    /**
     * 布尔值定义
     */

    /** 假 */
    const BOOLEAN_FALSE = 0;

    /** 真 */
    const BOOLEAN_TRUE = 1;
    const DEFAULT_ORDERING_VALUE = 10000;

    /**
     * 分类类型值定义
     */

    /** 文章分类 */
    const CATEGORY_ARTICLE = 0;

    /** 资讯分类 */
    const CATEGORY_NEWS = 1;

    /** 商品分类 */
    const CATEGORY_ITEM = 2;

    /**
     * 状态值定义
     */
    const STATUS_DRAFT = 0;
    const STATUS_PENDING = 1;
    const STATUS_PUBLISHED = 2;
    const STATUS_DELETED = 3;
    const STATUS_ARCHIVED = 4;

}
