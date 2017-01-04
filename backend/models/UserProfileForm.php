<?php
namespace backend\models;

use common\models\User;
use common\models\UnregisteredUser;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class UserProfileForm extends Model
{
    public $username;
    public $password;
    public $repeat_password;
    public $salutation;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $initials;
    public $gender;
    public $gender_opt;
    public $affiliation;
    public $signature;
    public $bio_statement;    
    public $email;
    public $repeat_email;
    public $orcid_id;
    public $url;
    public $phone;
    public $fax;
    public $mailing_address;
    public $country;
    public $country_opt;
    public $send_confirmation;
    public $is_reader;
    public $is_author;
    public $is_reviewer;
    public $is_editor;
    public $is_admin;
    public $verifyCode;
    public $reviewer_interests;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        	//['is_unregistered_author', 'boolean', 'trueValue' => true, 'falseValue' => false],
        		
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'match', 'pattern' => '/^[a-z0-9_\-\.]{6,20}$/', 'message' => 'The username must contain only lowercase letters, numbers and hyphens/underscores, between 6 and 20 characters.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],        	
        		
        	['repeat_password', 'required'],
        	['repeat_password', 'string', 'min' => 6],
        	['repeat_password', 'compare', 'compareAttribute'=>'password', 'message' => 'Passwords do not match.'],   
        		
       		['salutation', 'string', 'max' => 100],
        		
        	['first_name', 'required'],
        	['first_name', 'match', 'pattern' => '/^[a-zA-Z]{0,100}$/', 'message' => 'The first name must contain only letters.'],
         		
        	['middle_name', 'match', 'pattern' => '/^[a-zA-Z]{0,100}$/', 'message' => 'The middle name must contain only letters.'],
        		
        	['last_name', 'required'],
        	['last_name', 'match', 'pattern' => '/^[a-zA-Z]{0,100}$/', 'message' => 'The last name must contain only letters.'],
        		
        	['initials', 'match', 'pattern' => '/^[A-Z]{0,10}$/', 'message' => 'The initials must contain only uppercase letters.'],

        	['gender', 'required'],
        		
        	['affiliation', 'required'],
        	['affiliation', 'string', 'max' => 255],        		
        		
        	['signature', 'string', 'max' => 255],         		

        	['bio_statement', 'string', 'max' => 255],        		
        		
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
        	
        	['orcid_id', 'string', 'max' => 100],
        		
        	['url', 'url'],	
        		
        	['phone', 'match', 'pattern' => '/^[0-9]{0,30}$/', 'message' => 'The phone must contain only numbers.'],
        		
        	['fax', 'match', 'pattern' => '/^[0-9]{0,30}$/', 'message' => 'The fax must contain only numbers.'],
        		
        	['mailing_address', 'required'],
        	['mailing_address', 'string', 'max' => 255],
        		
        	['country', 'required'],
        		
        	['reviewer_interests', 'string', 'max' => 255],
        		
        	['verifyCode', 'captcha','captchaAction'=>'/user/captcha'],
        ];
    }
    
    
    /**
     * Update user's profile from Admin panel.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function updateUserProfile($userId)
    {
    	$user = new User();    	
    	
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
    					&& ($user->email != $this->email) && ($user->username != $this->username))
    			{
    				$result["duplicate_message"] = "existing email and username error";
    				$result["duplicate_username_user"] = $tmpUserUsername;
    				$result["duplicate_email_user"] = $tmpUserEmail;
    				return $result;
    			} else if(isset($tmpUserEmail) && count($tmpUserEmail) > 0 && ($user->email != $this->email)){
    				$result["duplicate_message"] = "existing email error";
    				$result["duplicate_username_user"] = null;
    				$result["duplicate_email_user"] = $tmpUserEmail;
    				return $result;
    			} else if(isset($tmpUserUsername) && count($tmpUserUsername) > 0 && ($user->username != $this->username)){
    				$result["duplicate_message"] = "existing username error";
    				$result["duplicate_username_user"] = $tmpUserUsername;
    				$result["duplicate_email_user"] = null;
    				return $result;
    			}
    			
    			/*$tmpUnregisteredUserEmail = UnregisteredUser::findOne([
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
    			}*/
    		}
		
    		$user->username = $this->username;
    		$user->email = $this->email;	
   			$user->setPassword($this->password);  		
    		$user->salutation = $this->salutation;
    		$user->first_name = $this->first_name;
    		$user->middle_name = $this->middle_name;
    		$user->last_name = $this->last_name;
    		$user->initials = $this->initials;
    		$user->affiliation = $this->affiliation;
    		$user->signature = $this->signature;
    		$user->orcid_id = $this->orcid_id;
    		$user->url = $this->url;
    		$user->phone = $this->phone;
    		$user->fax = $this->fax;
    		$user->mailing_address = $this->mailing_address;
    		$user->bio_statement = $this->bio_statement;
    		$user->reviewer_interests = $this->reviewer_interests;
    		$user->is_unregistered_author = false;
    
    		if(isset($_POST[$this->formName()]['gender'])){
    			$user->gender = $_POST[$this->formName()]['gender'];
    		}
    
    		if(isset($_POST[$this->formName()]['country'])){
    			$user->country = $_POST[$this->formName()]['country'];
    		}
    
    		if(isset($_POST[$this->formName()]['send_confirmation'])){
    			$user->send_confirmation = $_POST[$this->formName()]['send_confirmation'];
    		} else {
    			$user->send_confirmation = true;
    		}
    
    		if(isset($_POST[$this->formName()]['is_reader'])){
    			$user->is_reader = $_POST[$this->formName()]['is_reader'];
    		} else{
    			$user->is_reader = true;
    		}
    
    		if(isset($_POST[$this->formName()]['is_author'])){
    			$user->is_author = $_POST[$this->formName()]['is_author'];
    		} else{
    			$user->is_author = true;
    		}
    
    		if(isset($_POST[$this->formName()]['is_reviewer'])){
    			$user->is_reviewer = $_POST[$this->formName()]['is_reviewer'];
    		} else{
    			$user->is_reviewer = false;
    		}
    		
    		if (Yii::$app->session->get('user.is_editor') == true || Yii::$app->session->get('user.is_admin') == true){
	    		if(isset($_POST[$this->formName()]['is_editor'])){
	    			$user->is_editor = $_POST[$this->formName()]['is_editor'];
	    		} else{
	    			$user->is_editor = false;
	    		}   
    		} else {
    			$user->is_editor = false;
    		}
    		
    		if (Yii::$app->session->get('user.is_admin') == true){
	    		if(isset($_POST[$this->formName()]['is_admin'])){
	    			$user->is_admin = $_POST[$this->formName()]['is_admin'];
	    		} else{
	    			$user->is_admin = false;
	    		}
    		} else {
    			$user->is_admin = false;
    		}
    
    		$user->generateAuthKey();
    		if ($user->save()) {
    			
    			if((isset(Yii::$app->user->identity) && isset(Yii::$app->user->identity->attributes["id"]) &&
    					isset($user) && isset($user->id) && (Yii::$app->user->identity->attributes["id"] == $user->id)))
    			{
	    			Yii::$app->session->set('user.is_admin', $user->is_admin);
	    			Yii::$app->session->set('user.is_editor', $user->is_editor);
	    			Yii::$app->session->set('user.is_reader', $user->is_reader);
	    			Yii::$app->session->set('user.is_author', $user->is_author);
	    			Yii::$app->session->set('user.is_reviewer', $user->is_reviewer);
    			}
    			
    			return $user;
    		} else {
    			Yii::error("UserProfileForm->updateUserProfile(1): ".json_encode($user->getErrors()), "custom_errors_users");
    		}
    	} else { 
    		Yii::error("UserProfileForm->updateUserProfile(2): ".json_encode($this->getErrors()), "custom_errors_users");
    	}
    	
    	return null;
    }
    
    //create user profile
    public function createUserProfile()
    {
    	$user = new User(); 
    
    	if ($this->validate()) {
    
    		if(isset($this->email) || isset($this->username)){
    			 
    			$tmpUserEmail = User::findOne([
    					'email' => $this->email
    			]);    			 
    			$tmpUserUsername = User::findOne([
    					'username' => $this->username
    			]);    			 
    			if(isset($tmpUserEmail) && count($tmpUserEmail) > 0 && isset($tmpUserUsername) && count($tmpUserUsername) > 0
    					&& (isset($this->email)) && (isset($this->username)))
    			{
    				$result["duplicate_message"] = "existing email and username error";
    				$result["duplicate_username_user"] = $tmpUserUsername;
    				$result["duplicate_email_user"] = $tmpUserEmail;
    				return $result;
    			} else if(isset($tmpUserEmail) && count($tmpUserEmail) > 0 && (isset($this->email))){
    				$result["duplicate_message"] = "existing email error";
    				$result["duplicate_username_user"] = null;
    				$result["duplicate_email_user"] = $tmpUserEmail;
    				return $result;
    			} else if(isset($tmpUserUsername) && count($tmpUserUsername) > 0 && (isset($this->username))){
    				$result["duplicate_message"] = "existing username error";
    				$result["duplicate_username_user"] = $tmpUserUsername;
    				$result["duplicate_email_user"] = null;
    				return $result;
    			}
    			
    			/*$tmpUnregisteredUserEmail = UnregisteredUser::findOne([
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
    			}*/
    		}
    
    		$user->username = $this->username;
    		$user->email = $this->email;
    		$user->setPassword($this->password);
    		$user->salutation = $this->salutation;
    		$user->first_name = $this->first_name;
    		$user->middle_name = $this->middle_name;
    		$user->last_name = $this->last_name;
    		$user->initials = $this->initials;
    		$user->affiliation = $this->affiliation;
    		$user->signature = $this->signature;
    		$user->orcid_id = $this->orcid_id;
    		$user->url = $this->url;
    		$user->phone = $this->phone;
    		$user->fax = $this->fax;
    		$user->mailing_address = $this->mailing_address;
    		$user->bio_statement = $this->bio_statement;
    		$user->reviewer_interests = $this->reviewer_interests;
    		$user->is_unregistered_author = false;
    
    		if(isset($_POST[$this->formName()]['gender'])){
    			$user->gender = $_POST[$this->formName()]['gender'];
    		}
    
    		if(isset($_POST[$this->formName()]['country'])){
    			$user->country = $_POST[$this->formName()]['country'];
    		}
    
    		if(isset($_POST[$this->formName()]['send_confirmation'])){
    			$user->send_confirmation = $_POST[$this->formName()]['send_confirmation'];
    		} else {
    			$user->send_confirmation = true;
    		}
    
    		if(isset($_POST[$this->formName()]['is_reader'])){
    			$user->is_reader = $_POST[$this->formName()]['is_reader'];
    		} else{
    			$user->is_reader = true;
    		}
    
    		if(isset($_POST[$this->formName()]['is_author'])){
    			$user->is_author = $_POST[$this->formName()]['is_author'];
    		} else{
    			$user->is_author = true;
    		}
    
    		if(isset($_POST[$this->formName()]['is_reviewer'])){
    			$user->is_reviewer = $_POST[$this->formName()]['is_reviewer'];
    		} else{
    			$user->is_reviewer = false;
    		}
    		
    		if (Yii::$app->session->get('user.is_editor') == true || Yii::$app->session->get('user.is_admin') == true){
	    		if(isset($_POST[$this->formName()]['is_editor'])){
	    			$user->is_editor = $_POST[$this->formName()]['is_editor'];
	    		} else{
	    			$user->is_editor = false;
	    		}   
    		} else {
    			$user->is_editor = false;
    		}
    		
    		if (Yii::$app->session->get('user.is_admin') == true){
	    		if(isset($_POST[$this->formName()]['is_admin'])){
	    			$user->is_admin = $_POST[$this->formName()]['is_admin'];
	    		} else{
	    			$user->is_admin = false;
	    		}
    		} else {
    			$user->is_admin = false;
    		}
    
    		$user->generateAuthKey();
    		if ($user->save()) {
    			
    			return $user;
    		} else {
    			Yii::error("UserProfileForm->createUserProfile(1): ".json_encode($user->getErrors()), "custom_errors_users");
    		}
    	} else { 
    		Yii::error("UserProfileForm->createUserProfile(2): ".json_encode($this->getErrors()), "custom_errors_users");
    	}
    	
    	return null;
    }
    
    
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
    	return array(
    		'orcid_id' => 'ORCID iD',
    		'verifyCode' => 'Verification Code',
    		'send_confirmation' => 'Send me a confirmation email including my username and password',
    		'is_reader' => 'Reader: Notified by email on publication of an issue of the journal',
    		'is_author' => 'Author: Able to submit items to the journal',
    		'is_reviewer' => 'Reviewer: Willing to conduct peer review of submissions to the site. Identify reviewing interests (substantive areas and research methods):',  
    		'is_editor' => 'Editor: Able to edit items to the journal',
    		'is_admin' => 'Admin: Able to manage the system',
    		'reviewer_interests' => 'Reviewer Interests',
    		'mailing_address' => 'Mailing Address, City, Province',
    	);
    }
}
