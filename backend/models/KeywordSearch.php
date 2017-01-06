<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Keyword;

/**
 * KeywordSearch represents the model behind the search form about `common\models\Keyword`.
 */
class KeywordSearch extends Keyword
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyword_id', 'is_deleted'], 'integer'],
            [['content', 'created_on', 'updated_on'], 'safe'],
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
        $query = Keyword::find();

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
        $query->andFilterWhere([
            'keyword_id' => $this->keyword_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
