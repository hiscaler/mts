<?php

namespace app\models;

use app\models\WorkflowRuleDefinition;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WorkflowRuleDefinitionSearch represents the model behind the search form about `app\models\WorkflowRuleDefinition`.
 */
class WorkflowRuleDefinitionSearch extends WorkflowRuleDefinition
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rule_id', 'ordering', 'user_id', 'user_group_id', 'enabled'], 'integer'],
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
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = WorkflowRuleDefinition::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'ordering' => SORT_ASC,
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'rule_id' => $this->rule_id,
            'ordering' => $this->ordering,
            'user_id' => $this->user_id,
            'user_group_id' => $this->user_group_id,
            'enabled' => $this->enabled,
        ]);

        return $dataProvider;
    }

}
