<?php
namespace backend\models;

use common\models\User;
use common\models\UnregisteredUser;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class UnregisteredUserProfileForm extends Model
{
    public $username;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $initials;
    public $gender;
    public $gender_opt;
    public $email;
    public $repeat_email;
    public $mailing_address;
    public $country;
    public $country_opt;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            //['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'match', 'pattern' => '/^[a-z0-9_\-\.]{6,20}$/', 'message' => 'The username must contain only lowercase letters, numbers and hyphens/underscores, between 6 and 20 characters.'],

        	['first_name', 'required'],
        	['first_name', 'match', 'pattern' => '/^[a-zA-Z]{0,100}$/', 'message' => 'The first name must contain only letters.'],
         		
        	['middle_name', 'match', 'pattern' => '/^[a-zA-Z]{0,100}$/', 'message' => 'The middle name must contain only letters.'],
        		
        	['last_name', 'required'],
        	['last_name', 'match', 'pattern' => '/^[a-zA-Z]{0,100}$/', 'message' => 'The last name must contain only letters.'],
        		
        	['initials', 'match', 'pattern' => '/^[A-Z]{0,10}$/', 'message' => 'The initials must contain only uppercase letters.'],

        	['gender', 'required'],        		
       		
        	['email', 'filter', 'filter' => 'trim'],
        	['email', 'required'],
        	['email', 'email'],
        	['email', 'string', 'max' => 255],
        	//['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
        		
       		['repeat_email', 'filter', 'filter' => 'trim'],
       		['repeat_email', 'required'],
       		['repeat_email', 'email'],
       		['repeat_email', 'string', 'max' => 255],       		
        	['repeat_email', 'compare', 'compareAttribute'=>'email', 'message' => 'Emails do not match.'],
        	
        	['mailing_address', 'required'],
        	['mailing_address', 'string', 'max' => 255],
        		
        	['country', 'required'],        		
        ];
    }
    
    
    /**
     * Update user's profile from Admin panel.
     *
     * @return UnregisteredUser|null the saved model or null if saving fails
     */
    public function updateUnregisteredUserProfile($userId)
    {
    	$user = new UnregisteredUser();
    	
    	$user = $user->findIdentity($userId);

    	$tmpEmail = $this->email;
	
    	if ($this->validate()) {
    		
    		if($user->email != $this->email || $user->username != $this->username){
    			
    			$tmpUserEmail = User::findOne([
    					'email' => $this->email
    			]);    			
    			$tmpUserUsername = User::findOne([
    					'username' => $this->username
    			]);    			
    			if(isset($tmpUserEmail) && count($tmpUserEmail) > 0 && isset($tmpUserUsername) && count($tmpUserUsername) > 0
    					&& ($user->email != $this->email) && ($user->username != $this->username)){
    				return "existing email and username error";
    			} else if(isset($tmpUserEmail) && count($tmpUserEmail) > 0 && ($user->email != $this->email)){
    				return "existing email error";
    			} else if(isset($tmpUserUsername) && count($tmpUserUsername) > 0 && ($user->username != $this->username)){
    				return "existing username error";
    			}    			

    			$tmpUnregisteredUserEmail = UnregisteredUser::findOne([
    					'email' => $this->email
    			]);
    			$tmpUnregisteredUserUsername = UnregisteredUser::findOne([
    					'username' => $this->username
    			]);    			
    			if(isset($tmpUnregisteredUserEmail) && count($tmpUnregisteredUserEmail) > 0 && isset($tmpUnregisteredUserUsername) && count($tmpUnregisteredUserUsername) > 0
    					&& ($user->email != $this->email) && ($user->username != $this->username)){
    				return "existing email and username error";
    			} else if(isset($tmpUnregisteredUserEmail) && count($tmpUnregisteredUserEmail) > 0 && ($user->email != $this->email)){
    				return "existing email error";
    			} else if(isset($tmpUnregisteredUserUsername) && count($tmpUnregisteredUserUsername) > 0 && ($user->username != $this->username)){
    				return "existing username error";
    			}
    		}
		
    		$user->username = $this->username;
    		$user->email = $this->email;	
    		$user->first_name = $this->first_name;
    		$user->middle_name = $this->middle_name;
    		$user->last_name = $this->last_name;
    		$user->initials = $this->initials;
    		$user->mailing_address = $this->mailing_address;
    		$user->updated_on = date("Y-m-d H:i:s");
    
    		if(isset($_POST[$this->formName()]['gender'])){
    			$user->gender = $_POST[$this->formName()]['gender'];
    		}
    
    		if(isset($_POST[$this->formName()]['country'])){
    			$user->country = $_POST[$this->formName()]['country'];
    		}
    
    		if ($user->save()) {   			
    			
    			return $user;
    		} else {
    			Yii::error("UnregisteredUserProfileForm->updateUnregisteredUserProfile(1): ".json_encode($user->getErrors()), "custom_errors_users");
    		}
    	} else { 
    		Yii::error("UnregisteredUserProfileForm->updateUnregisteredUserProfile(2): ".json_encode($this->getErrors()), "custom_errors_users");
    	}
    			
    	return null;
    }
    
    //create user profile
    public function createUnregisteredUserProfile()
    {
    	$user = new UnregisteredUser(); 

    	if ($this->validate()) {

    		if(isset($this->email) || isset($this->username)){
    			 
    			$tmpUserEmail = UnregisteredUser::findOne([
    					'email' => $this->email
    			]);    			 
    			$tmpUserUsername = UnregisteredUser::findOne([
    					'username' => $this->username
    			]);    			 
    			if(isset($tmpUserEmail) && count($tmpUserEmail) > 0 && isset($tmpUserUsername) && count($tmpUserUsername) > 0
    					&& (isset($this->email)) && (isset($this->username))){
    				return "existing email and username error";
    			} else if(isset($tmpUserEmail) && count($tmpUserEmail) > 0 && (isset($this->email))){
    				return "existing email error";
    			} else if(isset($tmpUserUsername) && count($tmpUserUsername) > 0 && (isset($this->username))){
    				return "existing username error";
    			}
    			
    			$tmpUnregisteredUserEmail = UnregisteredUser::findOne([
    					'email' => $this->email
    			]);
    			$tmpUnregisteredUserUsername = UnregisteredUser::findOne([
    					'username' => $this->username
    			]);
    		    if(isset($tmpUnregisteredUserEmail) && count($tmpUnregisteredUserEmail) > 0 && isset($tmpUnregisteredUserUsername) && count($tmpUnregisteredUserUsername) > 0
    					&& (isset($this->email)) && (isset($this->username))){
    				return "existing email and username error";
    			} else if(isset($tmpUnregisteredUserEmail) && count($tmpUnregisteredUserEmail) > 0 && (isset($this->email))){
    				return "existing email error";
    			} else if(isset($tmpUnregisteredUserUsername) && count($tmpUnregisteredUserUsername) > 0 && (isset($this->username))){
    				return "existing username error";
    			}
    		}
    
    		$user->user_creator_id = Yii::$app->user->id;
    		$user->username = $this->username;
    		$user->email = $this->email;
    		$user->first_name = $this->first_name;
    		$user->middle_name = $this->middle_name;
    		$user->last_name = $this->last_name;
    		$user->initials = $this->initials;
    		$user->mailing_address = $this->mailing_address;
    		$user->created_on = date("Y-m-d H:i:s");
    
    		if(isset($_POST[$this->formName()]['gender'])){
    			$user->gender = $_POST[$this->formName()]['gender'];
    		}
    
    		if(isset($_POST[$this->formName()]['country'])){
    			$user->country = $_POST[$this->formName()]['country'];
    		}
    
     		if ($user->save()) {
    			
    			return $user;
    		} else {
    			Yii::error("UnregisteredUserProfileForm->createUnregisteredUserProfile(1): ".json_encode($user->getErrors()), "custom_errors_users");
    		}
    	} else { 
    		Yii::error("UnregisteredUserProfileForm->createUnregisteredUserProfile(2): ".json_encode($this->getErrors()), "custom_errors_users");
    	}
    	 
    	return null;
    }
    
    
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
    	return array(
    		'mailing_address' => 'Mailing Address, City, Province',
    	);
    }
}
