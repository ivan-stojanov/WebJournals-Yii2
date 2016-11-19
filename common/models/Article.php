<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "article".
 *
 * @property integer $article_id
 * @property integer $section_id
 * @property string $title
 * @property string $abstract
 * @property string $content
 * @property string $pdf_content
 * @property string $page_from
 * @property string $page_to
 * @property integer $sort_in_section
 * @property string $created_on
 * @property string $updated_on
 * @property integer $is_deleted
 *
 * @property Section $section
 * @property ArticleAuthor[] $articleAuthors
 * @property User[] $authors
 * @property ArticleKeyword[] $articleKeywords
 * @property Keyword[] $keywords
 * @property ArticleReviewer[] $articleReviewers
 * @property User[] $reviewers
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section_id', 'title', 'abstract', 'content'], 'required'],
            [['section_id', 'sort_in_section', 'is_deleted'], 'integer'],
            [['title', 'abstract', 'content', 'pdf_content'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['page_from', 'page_to'], 'string', 'max' => 6],
            [['section_id'], 'exist', 'skipOnError' => true, 'targetClass' => Section::className(), 'targetAttribute' => ['section_id' => 'section_id']],
        ];
    }
    
    public function scenarios()
    {
    	$scenarios = parent::scenarios();
    	$scenarios['section_crud'] = ['title']; //Scenario Attributes that will be validated
    	return $scenarios;
    }
    
    public static function deleteByIDs($deletedIDs = []){
    
    	try {
    		foreach ($deletedIDs as $deletedID){
    			if (($currentModel = Article::findOne($deletedID)) !== null) {
    				$currentModel->delete();
    			}
    		}
    	} catch (Exception $e) {
    		return false;
    	}
    
    	return true;
    }    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(Section::className(), ['section_id' => 'section_id']);
    }
    
    public static function  get_sections(){
    	$sections = Section::find()->all();
    	$sections = ArrayHelper::map($sections, 'section_id', 'volumeissuesectiontitle');
    	return $sections;
    }    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(User::className(), ['id' => 'author_id'])->viaTable('article_author', ['article_id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleKeywords()
    {
        return $this->hasMany(ArticleKeyword::className(), ['article_id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeywords()
    {
        return $this->hasMany(Keyword::className(), ['keyword_id' => 'keyword_id'])->viaTable('article_keyword', ['article_id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleReviewers()
    {
        return $this->hasMany(ArticleReviewer::className(), ['article_id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviewers()
    {
        return $this->hasMany(User::className(), ['id' => 'reviewer_id'])->viaTable('article_reviewer', ['article_id' => 'article_id']);
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
    	return [
    			'article_id' => 'Article ID',
    			'section_id' => 'Section name',
    			'title' => 'Article title',
    			'abstract' => 'Abstract',
    			'content' => 'Content',
    			'pdf_content' => 'Pdf file',
    			'page_from' => 'Page from',
    			'page_to' => 'Page to',
    			'sort_in_section' => 'Sort in section',
            	'created_on' => 'Created on',
            	'updated_on' => 'Updated on',
            	'is_deleted' => 'Is deleted',
    	];
    }
}
