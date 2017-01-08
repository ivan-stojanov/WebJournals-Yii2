<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_editor".
 *
 * @property integer $article_id
 * @property integer $editor_id
 * @property string $created_on
 * @property string $updated_on
 *
 * @property User $editor
 * @property Article $article
 */
class ArticleEditor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_editor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'editor_id'], 'required'],
            [['article_id', 'editor_id'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['editor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['editor_id' => 'id']],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'article_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => 'Article ID',
            'editor_id' => 'Editor ID',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEditor()
    {
        return $this->hasOne(User::className(), ['id' => 'editor_id']);
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
    public static function getEditorsForArticle($articleID)
    {
    	return ArticleEditor::find()->where(['article_id' => $articleID, 'user.status' => User::STATUS_ACTIVE, 'user.is_editor' => true])
				    				->innerJoinWith('editor')
				    				->orderBy('user.first_name ASC, user.last_name ASC')
				    				->all();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getEditorsForArticleString($articleID)
    {
    	$article_editors_ids = null;
    	$article_editors_string = null;
    	$articleEditors_array = ArticleEditor::getEditorsForArticle($articleID);
    	if($articleEditors_array != null && count($articleEditors_array)>0 ){
    		$article_editors_ids = ",";
    		$article_editors_string = "";
    		foreach ($articleEditors_array as $article_editor){
    			$article_editors_ids .= $article_editor->editor->id.",";
    			$article_editors_string .= $article_editor->editor->fullName." <".$article_editor->editor->email.">, ";
    		}
    		$article_editors_string = trim($article_editors_string, ", ");
    	}
    	 
    	$article_editors = null;
    	$article_editors['ids'] = $article_editors_ids;
    	$article_editors['string'] = $article_editors_string;
    	
    	return $article_editors;
    }
}
