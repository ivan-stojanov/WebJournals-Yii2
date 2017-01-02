<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UnregisteredUser;

/**
 * UnregisteredUserSearch represents the model behind the search form about `common\models\UnregisteredUser`.
 */
class NOTUSEDUnregisteredUserSearch extends UnregisteredUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unregistered_user_id', 'user_creator_id', 'is_deleted'], 'integer'],
            [['username', 'email', 'first_name', 'last_name', 'middle_name', 'gender', 'initials', 'affiliation', 'mailing_address', 'country', 'created_on', 'updated_on'], 'safe'],
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
        $query = UnregisteredUser::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        /*$query->andFilterWhere([
            'unregistered_user_id' => $this->unregistered_user_id,
            'user_creator_id' => $this->user_creator_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'is_deleted' => $this->is_deleted,
        ]);*/

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'initials', $this->initials])
            ->andFilterWhere(['like', 'affiliation', $this->affiliation])
            ->andFilterWhere(['like', 'mailing_address', $this->mailing_address])
            ->andFilterWhere(['like', 'country', $this->country]);

        return $dataProvider;
    }
}
