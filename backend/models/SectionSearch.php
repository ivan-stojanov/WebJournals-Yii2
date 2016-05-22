<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Section;

/**
 * SectionSearch represents the model behind the search form about `common\models\Section`.
 */
class SectionSearch extends Section
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section_id', 'issue_id', 'sort_in_issue', 'is_deleted'], 'integer'],
            [['title', 'created_on', 'updated_on'], 'safe'],
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
        $query = Section::find();

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
            'section_id' => $this->section_id,
            'issue_id' => $this->issue_id,
            'sort_in_issue' => $this->sort_in_issue,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
