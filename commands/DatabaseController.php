<?php

namespace app\commands;

use Yii;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\db\Connection;
use yii\db\Query;

/**
 * 数据库处理脚本
 *
 * @package app\commands
 * @author hiscaler <hiscaler@gmail.com>
 */
class DatabaseController extends Controller
{

    const PAGE_SIZE = 100;

    /**
     * 来源数据库
     *
     * @var Connection
     */
    protected $fromDb;

    /**
     * 目标数据库
     *
     * @var Connection
     */
    protected $toDb;

    protected $tables = [];

    public function init()
    {
        $this->fromDb = Yii::$app->getDb();
        $this->toDb = Yii::$app->toDb;
        $this->tables = $this->fromDb->getTableSchema();
    }

    /**
     * 同步数据库数据
     *
     * @throws InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionSync()
    {
        if (!$this->toDb) {
            throw new InvalidConfigException('无效的数据库配置（toDb）。');
        }
        $fromTablePrefix = $toTablePrefix = '';
        $this->fromDb->tablePrefix && $fromTablePrefix = $this->fromDb->tablePrefix;
        $this->toDb->tablePrefix && $toTablePrefix = $this->toDb->tablePrefix;
        echo "Begin ..." . PHP_EOL;
        foreach ($this->tables as $table) {
            echo "Process $table table ..." . PHP_EOL;
            $fromTable = $fromTablePrefix . $table;
            $toTable = $toTablePrefix . $table;
            $this->toDb->createCommand()->truncateTable($toTable);
            $query = (new Query())->from($fromTable);
            $count = $query->count($this->fromDb);
            $totalPages = (int) (($count + self::PAGE_SIZE - 1) / self::PAGE_SIZE);
            for ($page = 1; $page <= $totalPages; $page++) {
                echo "Process $fromTable table #$page page ..." . PHP_EOL;
                $items = $query->offset(($page - 1) * self::PAGE_SIZE)
                    ->limit(self::PAGE_SIZE)
                    ->all($this->fromDb);
                if ($items) {
                    $this->toDb->createCommand()->batchInsert($toTable, array_keys($items[0]), $items)->execute();
                }
            }
        }
        echo "Done ..." . PHP_EOL;
    }

}