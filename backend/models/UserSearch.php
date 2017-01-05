<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'send_confirmation', 'is_admin', 'is_editor', 'is_reader', 'is_author', 'is_reviewer', 'is_unregistered_author', 'creator_user_id', 'last_login'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'first_name', 'last_name', 'gender', 'salutation', 'middle_name', 'initials', 'affiliation', 'signature', 'orcid_id', 'url', 'phone', 'fax', 'mailing_address', 'bio_statement', 'reviewer_interests', 'user_image', 'last_login', 'country'], 'safe'],
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
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        	'pagination' => [
		        'pageSize' => 10,
		    ],
        ]);
        
        if(!isset($params['UserSearch']['status'])){
        	$params['UserSearch']['status'] = User::STATUS_ACTIVE;
        } else if($params['UserSearch']['status'] === 'all'){
        	$params['UserSearch']['status'] = null;
        }
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }        
       
        if(isset($params) && isset($params['type'])){
        	if($params['type'] == 'admin'){
        		$this->is_admin = true;
        	} else if($params['type'] == 'author'){
        		$this->is_author = true;
        		$this->is_unregistered_author = false;
        	} else if($params['type'] == 'editor'){
        		$this->is_editor = true;
        	} else if($params['type'] == 'reviewer'){
        		$this->is_reviewer = true;
        	} else if($params['type'] == 'unregisteredauthor'){
        		$this->is_author = true;
        		$this->is_unregistered_author = true;
        	}
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'send_confirmation' => $this->send_confirmation,
            'is_admin' => $this->is_admin,
            'is_editor' => $this->is_editor,
            'is_reader' => $this->is_reader,
            'is_author' => $this->is_author,
            'is_reviewer' => $this->is_reviewer,
        	'is_unregistered_author' => $this->is_unregistered_author,
        	'creator_user_id' => $this->creator_user_id,
            'last_login' => $this->last_login,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'salutation', $this->salutation])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'initials', $this->initials])
            ->andFilterWhere(['like', 'affiliation', $this->affiliation])
            ->andFilterWhere(['like', 'signature', $this->signature])
            ->andFilterWhere(['like', 'orcid_id', $this->orcid_id])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'mailing_address', $this->mailing_address])
            ->andFilterWhere(['like', 'bio_statement', $this->bio_statement])
            ->andFilterWhere(['like', 'reviewer_interests', $this->reviewer_interests])
            ->andFilterWhere(['like', 'user_image', $this->user_image])
            ->andFilterWhere(['like', 'country', $this->country]);

        return $dataProvider;
    }
}
