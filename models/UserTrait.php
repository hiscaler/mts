<?php

namespace app\models;

trait UserTrait
{

    /**
     * Creater relational
     * @return ActiveQueryInterface the relational query object.
     */
    public function getCreater()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'created_by'])->select(['id', 'nickname']);
    }

    /**
     * Updater relational
     * @return ActiveQueryInterface the relational query object.
     */
    public function getUpdater()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'updated_by'])->select(['id', 'nickname']);
    }

    /**
     * Deleter relational
     * @return ActiveQueryInterface the relational query object.
     */
    public function getDeleter()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'deleted_by'])->select(['id', 'nickname']);
    }

    public static function className2Id($className = null)
    {
        if ($className === null) {
            $className = static::className();
        }
        return str_replace('\\', '-', $className);
    }

}
