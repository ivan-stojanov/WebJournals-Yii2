<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_reviewer".
 *
 * @property integer $article_id
 * @property integer $reviewer_id
 * @property integer $short_comment
 * @property string $long_comment
 * @property integer $is_submited
 * @property integer $is_editable
 * @property string $created_on
 * @property string $updated_on
 *
 * @property Article $article
 * @property User $reviewer
 */
class ArticleReviewer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_reviewer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'reviewer_id'], 'required'],
            [['article_id', 'reviewer_id', 'short_comment', 'is_submited', 'is_editable'], 'integer'],
            [['long_comment'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'article_id']],
            [['reviewer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['reviewer_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => 'Article ID',
            'reviewer_id' => 'Reviewer ID',
            'short_comment' => 'Short Comment',
            'long_comment' => 'Long Comment',
        	'is_submited' => 'Is Submited',
        	'is_editable' => 'Is Editable',
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
    public function getReviewer()
    {
        return $this->hasOne(User::className(), ['id' => 'reviewer_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getReviewersForArticle($articleID)
    {
    	return ArticleReviewer::find()->where(['article_id' => $articleID, 'user.status' => User::STATUS_ACTIVE, 'user.is_reviewer' => true])
							    	  ->innerJoinWith('reviewer')
							    	  ->orderBy('user.first_name ASC, user.last_name ASC')
							    	  ->all();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getReviewersForArticleString($articleID)
    {
    	$article_reviewers_ids = null;
    	$article_reviewers_string = null;
    	$articleReviewers_array = ArticleReviewer::getReviewersForArticle($articleID);
    	if($articleReviewers_array != null && count($articleReviewers_array)>0 ){
    		$article_reviewers_ids = ",";
    		$article_reviewers_string = "";
    		foreach ($articleReviewers_array as $article_reviewer){
    			$article_reviewers_ids .= $article_reviewer->reviewer->id.",";
    			$article_reviewers_string .= $article_reviewer->reviewer->fullName." <".$article_reviewer->reviewer->email.">, ";
    		}
    		$article_reviewers_string = trim($article_reviewers_string, ", ");
    	}
    	
    	$article_reviewers = null;
    	$article_reviewers['ids'] = $article_reviewers_ids;
    	$article_reviewers['string'] = $article_reviewers_string;
    	 
    	return $article_reviewers;
    }
}
