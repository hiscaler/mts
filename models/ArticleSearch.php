<?php

namespace app\models;

use app\models\Article;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ArticleSearch represents the model behind the search form about `app\models\Article`.
 */
class ArticleSearch extends Article
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'title'], 'safe'],
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

    public function behaviors()
    {
        return [];
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
        $query = Article::find()->with([ 'creater', 'updater', 'deleter'])->asArray(true);
        $query->where('tenant_id = :tenantId', [
            ':tenantId' => MTS::getTenantId()
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'alias' => SORT_ASC,
                    'ordering' => SORT_ASC,
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([ 'like', 'alias', $this->alias])
            ->andFilterWhere([ 'like', 'title', $this->title]);

        return $dataProvider;
    }

}
