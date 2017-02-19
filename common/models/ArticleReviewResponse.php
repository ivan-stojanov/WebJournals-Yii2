<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_review_response".
 *
 * @property integer $article_review_response_id
 * @property integer $article_id
 * @property integer $reviewer_id
 * @property integer $response_creator_id
 * @property string $long_comment
 * @property string $created_on
 * @property string $updated_on
 * @property integer $is_deleted
 *
 * @property Article $article
 * @property User $responseCreator
 * @property User $reviewer
 */
class ArticleReviewResponse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_review_response';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'reviewer_id', 'response_creator_id'], 'required'],
            [['article_id', 'reviewer_id', 'response_creator_id', 'is_deleted'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['long_comment'], 'string', 'max' => 100],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'article_id']],
            [['response_creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['response_creator_id' => 'id']],
            [['reviewer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['reviewer_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_review_response_id' => 'Article Review Response ID',
            'article_id' => 'Article ID',
            'reviewer_id' => 'Reviewer ID',
            'response_creator_id' => 'User',
            'long_comment' => 'Comment',
            'created_on' => 'Created on',
            'updated_on' => 'Updated On',
            'is_deleted' => 'Is Deleted',
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
    public function getResponseCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'response_creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewer()
    {
        return $this->hasOne(User::className(), ['id' => 'reviewer_id']);
    }
}
