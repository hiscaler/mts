<?php

namespace app\models;

use app\models\FriendlyLink;
use yii\data\ActiveDataProvider;

/**
 * FriendlyLinkSearch represents the model behind the search form about `app\models\FriendlyLink`.
 */
class FriendlyLinkSearch extends FriendlyLink
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'type', 'url_open_target', 'status'], 'integer'],
            [['title', 'url'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['isPictureLink'] = ['logo_path'];

        return $scenarios;
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
        $query = FriendlyLink::find()->with([ 'creater', 'updater', 'deleter'])->asArray(true);
        $query->where('[[tenant_id]] = :tenantId', [
            ':tenantId' => MTS::getTenantId(),
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'group_id' => $this->group_id,
            'type' => $this->type,
            'url_open_target' => $this->url_open_target,
            'status' => $this->status,
        ]);

        $query->andFilterWhere([ 'like', 'title', $this->title])
            ->andFilterWhere([ 'like', 'url', $this->url]);

        return $dataProvider;
    }

}
