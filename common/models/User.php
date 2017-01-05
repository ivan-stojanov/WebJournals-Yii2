<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/** 
 * This is the model class for table "user". 
 * 
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $salutation
 * @property string $middle_name
 * @property string $initials
 * @property string $affiliation
 * @property string $signature
 * @property string $orcid_id
 * @property string $url
 * @property string $phone
 * @property string $fax
 * @property string $mailing_address
 * @property string $bio_statement
 * @property integer $send_confirmation
 * @property integer $is_admin
 * @property integer $is_editor
 * @property integer $is_reader
 * @property integer $is_author
 * @property integer $is_reviewer
 * @property integer $is_unregistered_author
 * @property integer $creator_user_id
 * @property string $reviewer_interests
 * @property string $user_image
 * @property integer $last_login
 * @property string $country
 * 
 * @property ArticleAuthor[] $articleAuthors
 * @property Article[] $articles
 * @property ArticleFile[] $articleFiles
 * @property ArticleReviewer[] $articleReviewers
 * @property Article[] $articles0
 * @property Issue[] $issues
 * @property User $creatorUser
 * @property User[] $users
 */ 
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_PENDING = 01;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_PENDING, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
    	return [
    			'creator_user_id' => 'User Creator',
    	];
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isTokenValid($token, "PasswordReset")) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }
    
    /**
     * Finds user by helper token
     *
     * @param string $token helper token for report vioalation
     * @return static|null
     */
    public static function findByHelperTokenForViolationReport($token)
    {
    	if (!static::isTokenValid($token, "Helper")) {
    		return null;
    	}
    
    	return static::findOne([
    			'helper_token' => $token,
    			//'status' => self::STATUS_ACTIVE,
    	]);
    }
    
    /**
     * Finds user by helper token for upgrade limited to regular user
     *
     * @param string $token helper token
     * @return static|null
     */
    public static function findByHelperTokenForUpgrade($token)
    {
    	if (!static::isTokenValid($token, "Helper")) {
    		return null;
    	}
    
    	return static::findOne([
    			'helper_token' => $token,
    			//'status' => self::STATUS_ACTIVE,
    	]);
    }
    
    /**
     * Finds user by registration token
     *
     * @param string $token registration token
     * @return static|null
     */
    public static function findByRegistrationToken($token)
    {   	
    	return static::findOne([
    			'registration_token' => $token,
    			'status' => self::STATUS_PENDING,
    	]);
    }

    /**
     * Finds out if token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isTokenValid($token, $type)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = 0;
        if($type === "PasswordReset") {
        	$expire = Yii::$app->params['user.passwordResetTokenExpire'];
        } else if($type === "Helper") {
        	$expire = Yii::$app->params['user.helperTokenExpire'];
        }
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }    
    
    /**
     * @inheritdoc
     */
    public function getFullName()
    {
    	return $this->first_name." ".$this->last_name;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
    	if(!isset($this->password_hash) || (isset($this->password_hash) && ($password != $this->password_hash))){
        	$this->password_hash = Yii::$app->security->generatePasswordHash($password);
    	}
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
   /* public static function getAdminRole()
    {
    	return $this->attributes["is_admin"];
    }*/
    
    public function getUsersInAssociativeArray($conditionRoles)
    {
    	$userModel_array = User::find()->where($conditionRoles)->all();
    	$dataUsers = [];
    	 
    	if($userModel_array != null && count($userModel_array)>0){
    		foreach ($userModel_array as $userModel){
    			$dataUsers[$userModel->id] = $userModel->fullName." <".$userModel->email.">";
    		}
    	}
    	return $dataUsers;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleAuthors()
    {
    	return $this->hasMany(ArticleAuthor::className(), ['author_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleFiles()
    {
    	return $this->hasMany(ArticleFile::className(), ['user_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleReviewers()
    {
    	return $this->hasMany(ArticleReviewer::className(), ['reviewer_id' => 'id']);
    }
    
    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getCreatorUser() 
    { 
        return $this->hasOne(User::className(), ['id' => 'creator_user_id']);
    } 

    /** 
     * @return \yii\db\ActiveQuery 
     */ 
    public function getUsers() 
    { 
        return $this->hasMany(User::className(), ['creator_user_id' => 'id']);
    }
}
