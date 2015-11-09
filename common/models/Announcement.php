<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "announcement".
 *
 * @property integer $announcement_id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property integer $sort_order
 * @property string $created_on
 * @property string $updated_on
 * @property integer $is_deleted
 */
class Announcement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'announcement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['sort_order'], 'required'],
            [['sort_order', 'is_deleted'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['title', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'announcement_id' => 'Announcement ID',
            'title' => 'Title',
            'description' => 'Description',
            'content' => 'Content',
            'sort_order' => 'Sort Order',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
