<?php

namespace app\models;

use app\models\Slide;
use yii\data\ActiveDataProvider;

/**
 * SlideSearch represents the model behind the search form about `app\models\Slide`.
 */
class SlideSearch extends Slide
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'enabled', 'status'], 'integer'],
            [['title'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['insert'] = ['picture_path'];

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
        $query = Slide::find()->with(['creater', 'updater', 'deleter'])->asArray(true);
        $query->andWhere('[[tenant_id]]  = :tenantId', [
            ':tenantId' => MTS::getTenantId()
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'group_id' => $this->group_id,
            'enabled' => $this->enabled,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }

}
