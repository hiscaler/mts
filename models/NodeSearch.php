<?php

namespace app\models;

use yadjet\helpers\ArrayHelper;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\db\Query;

/**
 * NodeSearch represents the model behind the search form about `common\models\Node`.
 */
class NodeSearch extends Node
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'enabled'], 'integer'],
            [['alias', 'name', 'model_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ArrayDataProvider
     */
    public function search($params)
    {
        $query = (new Query())->select('t.*, cu.nickname AS creater_nickname, uu.nickname AS updater_nickname, du.nickname AS deleter_nickname')
            ->from('{{%node}} t')
            ->leftJoin('{{%user}} cu', '[[t.created_by]] = [[cu.id]]')
            ->leftJoin('{{%user}} uu', '[[t.updated_by]] = [[uu.id]]')
            ->leftJoin('{{%user}} du', '[[t.deleted_by]] = [[cu.id]]')
            ->where([
                'tenant_id' => MTS::getTenantId(),
            ])
            ->orderBy([
            't.parent_id' => SORT_ASC,
            't.ordering' => SORT_ASC
        ]);

        if ($this->load($params) && $this->validate()) {
            $nodeIdList = [];
            if ($this->parent_id) {
                $nodeIdList = Node::getChildrenIds($this->parent_id);
                $nodeIdList[] = $this->parent_id;
            }
            $query->andFilterWhere([
                't.id' => $nodeIdList,
                'enabled' => $this->enabled,
            ]);
            $query->andFilterWhere(['like', 'alias', $this->alias])
                ->andFilterWhere(['like', 'name', $this->name]);
        }

        $rawData = $query->all();
        if ($rawData) {
            $rawData = static::sortItems(['children' => ArrayHelper::toTree($rawData, 'id')]);
            unset($rawData[0]);
        }

        return new ArrayDataProvider([
            'allModels' => $rawData,
            'key' => 'id',
            'pagination' => [
                'pageSize' => count($rawData)
            ]
        ]);
    }

}
