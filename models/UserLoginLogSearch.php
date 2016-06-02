<?php

namespace app\models;

use app\models\UserLoginLog;
use app\models\MTS;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * UserLoginLogSearch represents the model behind the search form about `common\models\UserLoginLog`.
 */
class UserLoginLogSearch extends UserLoginLog
{

    public $username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'login_at'], 'integer'],
            [['login_ip', 'username'], 'safe'],
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
        $query = UserLoginLog::find()->with(['user'])->asArray(true);
        $query->andWhere([
            'user_id' => (new Query)
                ->select('user_id')
                ->from('{{%tenant_user}}')
                ->where('tenant_id = :tenantId', [':tenantId' => MTS::getTenantId()])
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'login_at' => SORT_DESC
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'status' => $this->status,
            'login_at' => $this->login_at,
        ]);

        if (!empty($this->username)) {
            $userIds = Yii::$app->getDb()->createCommand("SELECT [[id]] FROM {{%user}} WHERE [[username]] LIKE '%" . $this->username . "%'")->queryColumn();
            $query->andWhere(['user_id' => $userIds]);
        }

        $query->andFilterWhere(['like', 'login_ip', $this->login_ip]);

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'username' => Yii::t('user', 'Username'),
        ]);
    }

}
