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
            [['title', 'year'], 'required'],
            [['title'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['is_deleted'], 'integer'],
            [['year'], 'validateYear'],
        ];
    }
    
    public function validateYear($attribute, $params)
    {
    	if (!preg_match('/^[0-9]{4}$/i', $this->$attribute)) {
    		$this->addError($attribute, 'Please enter a valid year');
    	}
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
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
