<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_keyword".
 *
 * @property integer $article_id
 * @property integer $keyword_id
 * @property integer $sort_order
 * @property string $created_on
 * @property string $updated_on
 *
 * @property Article $article
 * @property Keyword $keyword
 */
class ArticleKeyword extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_keyword';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'keyword_id', 'sort_order'], 'required'],
            [['article_id', 'keyword_id', 'sort_order'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'article_id']],
            [['keyword_id'], 'exist', 'skipOnError' => true, 'targetClass' => Keyword::className(), 'targetAttribute' => ['keyword_id' => 'keyword_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => 'Article ID',
            'keyword_id' => 'Keyword ID',
            'sort_order' => 'Sort Order',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['article_id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeyword()
    {
        return $this->hasOne(Keyword::className(), ['keyword_id' => 'keyword_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getKeywordsForArticle($articleID)
    {
    	return ArticleKeyword::find()->where(['article_id' => $articleID, 'keyword.is_deleted' => 0])
							    	 ->innerJoinWith('keyword')
							     	 ->orderBy('sort_order ASC')
							     	 ->all();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getPublishedArticlesForKeyword($keywordID)
    {
    	return ArticleKeyword::find()->where(['keyword_id' => $keywordID, 'article.is_deleted' => 0, 'article.status' => Article::STATUS_PUBLISHED])
							    	->innerJoinWith('article')
							    	->orderBy('article.title ASC, sort_order ASC')
							    	->all();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getKeywordsForArticleString($articleID)
    {
    	$article_keywords_string = null;
    	$articleKeywords_array = ArticleKeyword::getKeywordsForArticle($articleID);
    	
    	if($articleKeywords_array != null && count($articleKeywords_array)>0 ){
    		$article_keywords_string = "";
    		foreach ($articleKeywords_array as $articleKeyword){
    			$article_keywords_string .= $articleKeyword->keyword->content.", ";
    		}
    		$article_keywords_string = trim($article_keywords_string, ", ");
    	}
    	
    	return $article_keywords_string;
    } 
}
