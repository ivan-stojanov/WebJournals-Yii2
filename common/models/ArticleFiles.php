<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_files".
 *
 * @property integer $file_id
 * @property integer $user_id
 * @property integer $article_id
 * @property string $file_name
 * @property string $file_original_name
 * @property string $file_mime_type
 * @property string $created_on
 * @property integer $is_deleted
 *
 * @property Article[] $articles
 * @property User $user
 */
class ArticleFiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'article_id'], 'required'],
            [['user_id', 'article_id', 'is_deleted'], 'integer'],
            [['file_name', 'file_original_name', 'file_mime_type'], 'string'],
            [['created_on'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        	[['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'article_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file_id' => 'File ID',
            'user_id' => 'User ID',
        	'article_id' => 'Article ID',
            'file_name' => 'File Name',        	
        	'file_original_name' => 'File Original Name',
        	'file_attach' => 'File',
            'file_mime_type' => 'File Mime Type',
            'created_on' => 'Created On',
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
