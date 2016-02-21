<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "issue".
 *
 * @property integer $issue_id
 * @property integer $volume_id
 * @property string $title
 * @property string $published_on
 * @property integer $is_special_issue
 * @property string $special_title
 * @property string $special_editor
 * @property string $cover_image
 * @property string $created_on
 * @property string $updated_on
 * @property integer $is_deleted
 */
class Issue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'issue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['volume_id', 'title'], 'required'],
            [['volume_id', 'is_special_issue', 'is_deleted'], 'integer'],
            [['title', 'special_title'], 'string'],
            [['published_on', 'created_on', 'updated_on'], 'safe'],
            [['special_editor', 'cover_image'], 'string', 'max' => 255],
            [['volume_id'], 'exist', 'skipOnError' => true, 'targetClass' => Volume::className(), 'targetAttribute' => ['volume_id' => 'volume_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'issue_id' => 'Issue ID',
            'volume_id' => 'Volume ID',
            'title' => 'Title',
            'published_on' => 'Published On',
            'is_special_issue' => 'Is Special Issue',
            'special_title' => 'Special Title',
            'special_editor' => 'Special Editor',
            'cover_image' => 'Cover Image',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
