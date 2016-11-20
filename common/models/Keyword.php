<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "keyword".
 *
 * @property integer $keyword_id
 * @property string $content
 * @property string $created_on
 * @property string $updated_on
 * @property integer $is_deleted
 *
 * @property ArticleKeyword[] $articleKeywords
 * @property Article[] $articles
 */
class Keyword extends \yii\db\ActiveRecord
{	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'keyword';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['is_deleted'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'keyword_id' => 'Keyword ID',
            'content' => 'Content',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleKeywords()
    {
        return $this->hasMany(ArticleKeyword::className(), ['keyword_id' => 'keyword_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['article_id' => 'article_id'])->viaTable('article_keyword', ['keyword_id' => 'keyword_id']);
    }
    
    public function getKeywordsInAssociativeArray()
    {
    	$keywordModel_array = Keyword::find()->where(['is_deleted' => false])->all();
    	$dataKeywords = [];
    	
    	if($keywordModel_array != null && count($keywordModel_array)>0 ){    		
    		foreach ($keywordModel_array as $keywordModel){
    			$dataKeywords[$keywordModel->keyword_id] = $keywordModel->content;
    		}
    	}
    	return $dataKeywords;
    }
}
