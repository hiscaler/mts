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
            [['group_id', 'type', 'url_open_target', 'enabled'], 'integer'],
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
        $query = FriendlyLink::find()->where(['tenant_id' => Yad::getTenantId()])->with(['creater', 'updater'])->asArray(true);

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
            'group_id' => $this->group_id,
            'type' => $this->type,
            'url_open_target' => $this->url_open_target,
            'enabled' => $this->enabled,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }

}
