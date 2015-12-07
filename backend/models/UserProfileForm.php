<?php
namespace backend\models;

use common\models\User;
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
    public $verifyCode;
    public $reviewer_interests;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'match', 'pattern' => '/^[a-z0-9_\-]{6,20}$/', 'message' => 'The username must contain only lowercase letters, numbers and hyphens/underscores, between 6 and 20 characters.'],

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

        	['affiliation', 'string', 'max' => 255],        		
        		
        	['signature', 'string', 'max' => 255],         		

        	['bio_statement', 'string', 'max' => 255],        		
        		
        	['email', 'filter', 'filter' => 'trim'],
        	['email', 'required'],
        	['email', 'email'],
        	['email', 'string', 'max' => 255],
        	['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
        		
       		['repeat_email', 'filter', 'filter' => 'trim'],
       		['repeat_email', 'required'],
       		['repeat_email', 'email'],
       		['repeat_email', 'string', 'max' => 255],       		
        	['repeat_email', 'compare', 'compareAttribute'=>'email', 'message' => 'Emails do not match.'],
        	
        	['orcid_id', 'string', 'max' => 100],
        		
        	['url', 'url'],	
        		
        	['phone', 'match', 'pattern' => '/^[0-9]{0,30}$/', 'message' => 'The phone must contain only numbers.'],
        		
        	['fax', 'match', 'pattern' => '/^[0-9]{0,30}$/', 'message' => 'The fax must contain only numbers.'],
        		
        	['mailing_address', 'string', 'max' => 255],
        		
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
    	if($this->email == $user->email && $this->email == $this->repeat_email){
    		$this->email = "test12@google.com";
    		$this->repeat_email = $this->email;
    	}    	
    	if ($this->validate()) {    	
    		$this->email = $tmpEmail;
    		$this->repeat_email = $this->email;    		
    		$user->username = $this->username;
    		$user->email = $this->email;		
    		if($this->password != $user->password){
    			$user->setPassword($this->password);
    		}    		
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
    
    		/* $user->gender = $this->gender;
    		 $user->country = $this->country;
    		 $user->send_confirmation = $this->send_confirmation;
    		 $user->is_reader = $this->is_reader;
    		 $user->is_author = $this->is_author;
    		 $user->is_reviewer = $this->is_reviewer;
    		 */
    
    		if(isset($_POST['UserProfileForm']['gender'])){
    			$user->gender = $_POST['UserProfileForm']['gender'];
    		}
    
    		if(isset($_POST['UserProfileForm']['country'])){
    			$user->country = $_POST['UserProfileForm']['country'];
    		}
    
    		if(isset($_POST['UserProfileForm']['send_confirmation'])){
    			$user->send_confirmation = $_POST['UserProfileForm']['send_confirmation'];
    		} else {
    			$user->send_confirmation = true;
    		}
    
    		if(isset($_POST['UserProfileForm']['is_reader'])){
    			$user->is_reader = $_POST['UserProfileForm']['is_reader'];
    		} else{
    			$user->is_reader = true;
    		}
    
    		if(isset($_POST['UserProfileForm']['is_author'])){
    			$user->is_author = $_POST['UserProfileForm']['is_author'];
    		} else{
    			$user->is_author = false;
    		}
    
    		if(isset($_POST['UserProfileForm']['is_reviewer'])){
    			$user->is_reviewer = $_POST['UserProfileForm']['is_reviewer'];
    		} else{
    			$user->is_reviewer = false;
    		}
    
    		$user->generateAuthKey();
    		if ($user->save()) {
    			return $user;
    		}
    	}
    
    	return null;
    }    

    /**
     * Signs user up from Admin panel.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function createNewUserFromAdmin()
    {
        if ($this->validate()) {
            $user = new User();
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
            
           /* $user->gender = $this->gender;
            $user->country = $this->country;
            $user->send_confirmation = $this->send_confirmation;
            $user->is_reader = $this->is_reader;
            $user->is_author = $this->is_author;
           	$user->is_reviewer = $this->is_reviewer;          	
           	*/
           	
           	if(isset($_POST['UserProfileForm']['gender'])){
           		$user->gender = $_POST['UserProfileForm']['gender'];
           	}
           	
           	if(isset($_POST['UserProfileForm']['country'])){
           		$user->country = $_POST['UserProfileForm']['country'];
           	}
           	
           	if(isset($_POST['UserProfileForm']['send_confirmation'])){
           		$user->send_confirmation = $_POST['UserProfileForm']['send_confirmation'];
           	} else {
           		$user->send_confirmation = true;
           	}
           	
           	if(isset($_POST['UserProfileForm']['is_reader'])){
           		$user->is_reader = $_POST['UserProfileForm']['is_reader'];
           	} else{
           		$user->is_reader = true;
           	}
           	
           	if(isset($_POST['UserProfileForm']['is_author'])){
           		$user->is_author = $_POST['UserProfileForm']['is_author'];
           	} else{
           		$user->is_author = false;
           	}
           	
           	if(isset($_POST['UserProfileForm']['is_reviewer'])){
           		$user->is_reviewer = $_POST['UserProfileForm']['is_reviewer'];
           	} else{
           		$user->is_reviewer = false;
           	}
           	
            $user->generateAuthKey();
            if ($user->save()) {
                return $user;
            }    		
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
    	);
    }
}
