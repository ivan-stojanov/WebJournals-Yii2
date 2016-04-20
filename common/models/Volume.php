<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "volume".
 *
 * @property integer $volume_id
 * @property string $title
 * @property string $year
 * @property string $created_on
 * @property string $updated_on
 * @property integer $is_deleted
 */
class Volume extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'volume';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
		return [
            [['title'], 'required'],
            [['title'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['is_deleted'], 'integer'],
            [['year'], 'string', 'max' => 10],
        ];
    }
    
    public function validateYear($attribute, $params)
    {
    	if (!preg_match('/^[0-9]{4}$/i', $this->$attribute)) {
    		$this->addError($attribute, 'Please enter a valid year');
    	}
    }
    
    public function getIssues()
    {
    	return $this->hasMany(\common\models\Issue::className(),  ['volume_id' => 'volume_id'])
    				->orderBy(['sort_in_volume' => SORT_ASC]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'volume_id' => 'Volume ID',
            'title' => 'Title',
            'year' => 'Year',
            'created_on' => 'Created on',
            'updated_on' => 'Updated on',
            'is_deleted' => 'Is deleted',
        ];
    }
}
