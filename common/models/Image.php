<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property integer $image_id
 * @property string $path
 * @property string $type
 * @property string $name
 * @property integer $size
 * @property string $created_on
 * @property string $updated_on
 * @property integer $is_deleted
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name', 'size'], 'required'],
            [['type', 'name'], 'string'],
            [['size', 'is_deleted'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
            [['path'], 'string', 'max' => 1024],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image_id' => 'Image ID',
            'path' => 'Path',
            'type' => 'Type',
            'name' => 'Name',
            'size' => 'Size',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
