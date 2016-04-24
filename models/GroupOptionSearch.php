<?php

namespace app\models;

use app\models\GroupOption;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GroupOptionSearch represents the model behind the search form about `common\models\GroupOption`.
 */
class GroupOptionSearch extends GroupOption
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enabled', 'defaulted'], 'integer'],
            [['group_name', 'alias'], 'safe'],
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
        $query = GroupOption::find()->with(['creater', 'updater', 'deleter'])->asArray(true);
        $query->where('[[tenant_id]] = :tenantId', [
            ':tenantId' => MTS::getTenantId(),
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'group_name' => SORT_ASC,
                    'value' => SORT_ASC
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([

            'enabled' => $this->enabled,
            'defaulted' => $this->defaulted,
        ]);

        $query->andFilterWhere(['like', 'group_name', $this->group_name])
            ->andFilterWhere(['like', 'alias', $this->alias]);

        return $dataProvider;
    }

}
