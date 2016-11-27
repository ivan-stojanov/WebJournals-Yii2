<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_author".
 *
 * @property integer $article_id
 * @property integer $author_id
 * @property integer $sort_order
 * @property integer $is_correspondent
 * @property string $created_on
 * @property string $updated_on
 *
 * @property Article $article
 * @property User $author
 */
class ArticleAuthor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_author';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'author_id', 'sort_order'], 'required'],
            [['article_id', 'author_id', 'sort_order', 'is_correspondent'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'article_id']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => 'Article ID',
            'author_id' => 'Author ID',
            'sort_order' => 'Sort Order',
            'is_correspondent' => 'Is Correspondent',
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
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorsForArticle($articleID)
    {
    	return ArticleAuthor::find()->where(['article_id' => $articleID, 'user.status' => User::STATUS_ACTIVE, 'user.is_author' => true])
							    	->innerJoinWith('author')
							    	->orderBy('sort_order ASC')
							    	->all();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorsForArticleString($articleID)
    {
    	$article_authors_string = null;
    	$articleAuthors_array = $this->getAuthorsForArticle($articleID);
    	if($articleAuthors_array != null && count($articleAuthors_array)>0 ){
    		$article_authors_string = "";
    		foreach ($articleAuthors_array as $articleAuthor){
    			$article_authors_string .= $articleAuthor->author->fullName.", ";
    		}
    		$article_authors_string = trim($article_authors_string, ", ");
    	}
    	
    	return $article_authors_string;
    }    
}
