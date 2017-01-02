<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "unregistered_user".
 *
 * @property integer $unregistered_user_id
 * @property integer $user_creator_id
 * @property string $username
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $gender
 * @property string $initials
 * @property string $affiliation
 * @property string $mailing_address
 * @property string $country
 * @property string $created_on
 * @property string $updated_on
 * @property integer $is_deleted
 *
 * @property User $userCreator
 */
class NOTUSEDUnregisteredUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unregistered_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_creator_id', 'username', 'email'], 'required'],
            [['user_creator_id', 'is_deleted'], 'integer'],
            [['gender', 'affiliation', 'mailing_address'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['username', 'email'], 'string', 'max' => 255],
            [['first_name', 'last_name', 'middle_name', 'country'], 'string', 'max' => 100],
            [['initials'], 'string', 'max' => 10],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['user_creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_creator_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'unregistered_user_id' => 'Unregistered User ID',
            'user_creator_id' => 'User Creator',
            'username' => 'Username',
            'email' => 'Email',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'middle_name' => 'Middle Name',
            'gender' => 'Gender',
            'initials' => 'Initials',
            'affiliation' => 'Affiliation',
            'mailing_address' => 'Mailing Address',
            'country' => 'Country',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
    	return static::findOne(['unregistered_user_id' => $id, 'is_deleted' => false]);
    }
    
    /**
     * @inheritdoc
     */
    public function getFullName()
    {
    	return $this->first_name." ".$this->last_name;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'user_creator_id']);
    }
}
