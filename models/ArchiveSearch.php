<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Archive;

/**
 * ArchiveSearch represents the model behind the search form about `app\models\Archive`.
 */
class ArchiveSearch extends Archive
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'node_id', 'has_thumbnail', 'status', 'enabled', 'published_datetime', 'enabled_comment', 'tenant_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['model_name', 'title', 'keywords', 'description', 'tags', 'author', 'source'], 'safe'],
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
        $query = Archive::find()->where(['tenant_id' => MTS::getTenantId()]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'node_id' => $this->node_id,
            'has_thumbnail' => $this->has_thumbnail,
            'status' => $this->status,
            'enabled' => $this->enabled,
            'published_datetime' => $this->published_datetime,
            'enabled_comment' => $this->enabled_comment,
            'ordering' => $this->ordering,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'model_name', $this->model_name])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'tags', $this->tags])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'source', $this->source]);

        return $dataProvider;
    }

}
