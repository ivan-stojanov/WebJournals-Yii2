<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Issue;

/**
 * IssueSearch represents the model behind the search form about `common\models\Issue`.
 */
class IssueSearch extends Issue
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['issue_id', 'volume_id', 'is_special_issue', 'special_editor', 'cover_image', 'sort_in_volume', 'is_deleted'], 'integer'],
            [['title', 'published_on', 'special_title', 'created_on', 'updated_on'], 'safe'],
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
    public function search($params, $editor_id = null)
    {
        $query = Issue::find();
        if($editor_id != null) {
        	$query = $query->where(['special_editor' => $editor_id]);
        }
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
            'issue_id' => $this->issue_id,
            'volume_id' => $this->volume_id,
            'published_on' => $this->published_on,
            'is_special_issue' => $this->is_special_issue,
        	'special_editor' => $this->special_editor,
            'cover_image' => $this->cover_image,
            'sort_in_volume' => $this->sort_in_volume,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'special_title', $this->special_title]);

        return $dataProvider;
    }
}
