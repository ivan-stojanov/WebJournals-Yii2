<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "section".
 *
 * @property integer $section_id
 * @property integer $issue_id
 * @property string $title
 * @property integer $sort_in_issue
 * @property string $created_on
 * @property string $updated_on
 * @property integer $is_deleted
 *
 * @property Article[] $articles
 * @property Issue $issue
 */
class Section extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'section';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['issue_id', 'title'], 'required'],
            [['issue_id', 'sort_in_issue', 'is_deleted'], 'integer'],
            [['title'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['issue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Issue::className(), 'targetAttribute' => ['issue_id' => 'issue_id']],
        ];
    }

    public function scenarios()
    {
    	$scenarios = parent::scenarios();
    	$scenarios['issue_crud'] = ['title']; //Scenario Attributes that will be validated
    	return $scenarios;
    }    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'section_id' => 'Section ID',
            'issue_id' => 'Issue ID',
            'title' => 'Title',
            'sort_in_issue' => 'Sort In Issue',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'is_deleted' => 'Is Deleted',
        ];
    }
    
    public static function deleteByIDs($deletedIDs = []){
    	 
    	try {
    		foreach ($deletedIDs as $deletedID){
    			if (($currentModel = Section::findOne($deletedID)) !== null) {
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
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['section_id' => 'section_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssue()
    {
        return $this->hasOne(Issue::className(), ['issue_id' => 'issue_id']);
    }
}
