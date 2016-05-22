<?php

namespace app\models;

use app\models\WorkflowRule;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WorkflowRuleSearch represents the model behind the search form about `common\models\WorkflowRule`.
 */
class WorkflowRuleSearch extends WorkflowRule
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enabled'], 'integer'],
            [['name'], 'safe'],
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
        $query = WorkflowRule::find();
        $query->where([
            'tenant_id' => MTS::getTenantId()
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'enabled' => $this->enabled,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

}
