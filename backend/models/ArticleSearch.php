<?php

namespace backend\models;

use Yii; 
use yii\base\Model; 
use yii\data\ActiveDataProvider; 
use common\models\Article; 

/** 
 * ArticleSearch represents the model behind the search form about `common\models\Article`. 
 */ 
class ArticleSearch extends Article 
{ 
    /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [ 
            [['article_id', 'section_id', 'sort_in_section', 'status', 'file_id', 'is_deleted'], 'integer'],
            [['title', 'abstract', 'content', 'pdf_content', 'page_from', 'page_to', 'created_on', 'updated_on', 'articleReviewers.is_editable'], 'safe'],
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
    
    public function attributes()
    {
    	// add related fields to searchable attributes
    	return array_merge(parent::attributes(), ['articleReviewers.is_editable']);
    }

    /** 
     * Creates data provider instance with search query applied 
     * 
     * @param array $params 
     * 
     * @return ActiveDataProvider 
     */ 
    public function search($params, $author_id = null, $reviewer_id = null) 
    { 
        $query = Article::find();        
        $query->joinWith(['articleReviewers' => function($query) { $query->from(['articleReviewers' => 'article_reviewer']); }]);
        
        // add conditions that should always apply here 
        if($author_id != null) {
        	$query = $query->joinWith('articleAuthors')
        	->where(['article_author.author_id' => $author_id]);        	 
        }
        if($reviewer_id != null) {
        	$conditionArray['article_reviewer.reviewer_id'] = $reviewer_id;
        	if(isset($params['ArticleSearch']['is_submited'])){
        		$conditionArray['article_reviewer.is_submited'] = $params['ArticleSearch']['is_submited'];
        	}
        	if(isset($params['ArticleSearch']['articleReviewers.is_editable']) && $params['ArticleSearch']['articleReviewers.is_editable'] != ''){
        		$conditionArray['article_reviewer.is_editable'] = $params['ArticleSearch']['articleReviewers.is_editable'];        		
        	}
        	$query = $query->joinWith('articleReviewers')
        	->where($conditionArray);
        }

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
            'article_id' => $this->article_id,
            'sort_in_section' => $this->sort_in_section,
            'status' => $this->status,
            'file_id' => $this->file_id,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'is_deleted' => $this->is_deleted,
        ]);
       
        if($this->section_id != '0'){
        	$query->andFilterWhere([
       			'section_id' => $this->section_id,
        	]);        	
        } else {
        	$query->andFilterWhere([
				'section_id' => null     		
        	]);        	
        }
       
        $query->andFilterWhere(['like','articleReviewers.is_editable',
        		$this->getAttribute('articleReviewers.is_editable')]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'abstract', $this->abstract])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'pdf_content', $this->pdf_content])
            ->andFilterWhere(['like', 'page_from', $this->page_from])
            ->andFilterWhere(['like', 'page_to', $this->page_to]);

        return $dataProvider; 
    } 
} 