<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\CommonVariables;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;
use backend\models\UserProfileForm;
use backend\models\UnregisteredUserProfileForm;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [        		
        	'access' => [
        		'class' => AccessControl::className(),
        		'rules' => [
        			//not logged users do not have access to any action
        			/*[
        			 'actions' => ['login', 'error'],
        				'allow' => true,
        			],*/
        			//only logged users have access to actions
        			[
        				'actions' => 	[	
        									'index', 'view', 'create', 'update', 'delete', 'profile', 
        									'createunregisteredauthor', 'updateunregisteredauthor', 
        									'asynch-alert-duplicate-user', 'captcha',
        								],
        				'allow' => true,
        				'roles' => ['@'],
        			],
        		],
        	],
            'verbs' => [
                	'class' => VerbFilter::className(),
                	'actions' => [
                   			'delete' => ['POST'],
                			'asynch-alert-duplicate-user' => ['post'],
                	],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function actions()
    {
    	$this->layout = 'adminlayout';
    	return [
    			'error' => [
    					'class' => 'yii\web\ErrorAction',
    			],
    			'captcha' => [
    					'class' => 'yii\captcha\CaptchaAction',
    					'fixedVerifyCode' => YII_ENV_TEST ? 'testme2' : null,
    			],
    	];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {   	
    	$queryParams = Yii::$app->request->queryParams;
    	
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		if(!($queryParams != null && $queryParams['type'] != null && 
    		  ($queryParams['type'] == "author" || $queryParams['type'] == "unregisteredauthor"))){
    			return $this->redirect(['site/error']);
    		}    			
    	}   	 	
    	
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($queryParams);
        
        $post_msg = null;
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'post_msg' => $post_msg,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	//if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    	//	return $this->redirect(['site/error']);
    	//}
    	
    	$model = $this->findModel($id);
    	
    	$user_can_modify = (Yii::$app->session->get('user.is_admin'));
    	$user_can_modify = $user_can_modify || ($model->is_unregistered_author && $model->is_author && 
    					   $model->creator_user_id != null && $model->creator_user_id == Yii::$app->user->id);
    	
    	$common_vars = new CommonVariables();
    	
        return $this->render('view', [
            'model' => $model,
        	'common_vars' => $common_vars,
        	'user_can_modify' => $user_can_modify,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	if (Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->_createUserForm();
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	if (Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
   	
        return $this->_editUserForm($id);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	if (Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionProfile()
    {    	
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	//if (Yii::$app->user->isGuest) {
    	//	return $this->redirect(['site/error']);
    	//}
    
    	$currentId = Yii::$app->user->identity->attributes["id"];
    	
    	//make sure that is current user
    	if((isset(Yii::$app->user->identity) && isset(Yii::$app->user->identity->attributes["id"]) &&
    			isset($currentId) && (Yii::$app->user->identity->attributes["id"] == $currentId)))
    	{
    		return $this->_editUserForm($currentId);
    	}

    	return $this->redirect(['site/error']);
    }
    
    //called on actionCreate
    public function _createUserForm()
    {
    	$model = new UserProfileForm();
    	$post_msg = null;
    
    	if ($model->load(Yii::$app->request->post())) {
    		if ($user = $model->createUserProfile()) {
    			if(isset($user)) {
    				if(isset($user["duplicate_message"])) {
    					if($user["duplicate_message"] === "existing email and username error"){
    						$post_msg["type"] = "warning";
    						$post_msg["text"] = "The username and the email address have already been taken. Try with anothers.<br><br>";
    						$post_msg["text"] .= "If this was not you, click <a id='duplicatetrigger' onclick='userScript_clickDuplicateUser(\"".$user["duplicate_username_user"]->id."\",\"".$user["duplicate_email_user"]->id."\")'><b>here</b></a> to send an instructions via email!";
    					} else if($user["duplicate_message"] === "existing email error"){
    						$post_msg["type"] = "warning";
    						$post_msg["text"] = "This email address has already been taken. Try with another one.<br><br>";
    						$post_msg["text"] .= "If this was not you, click <a id='duplicatetrigger' onclick='userScript_clickDuplicateUser(\"".intval(0)."\",\"".$user["duplicate_email_user"]->id."\")'><b>here</b></a> to send an instructions via email!";
    					} else if($user["duplicate_message"] === "existing username error"){
    						$post_msg["type"] = "warning";
    						$post_msg["text"] = "This username has already been taken. Try with another one.";
    						//$post_msg["text"] .= "If this was not you, click <a id='duplicatetrigger' onclick='userScript_clickDuplicateUser(\"".$user["duplicate_username_user"]->id."\",\"".intval(0)."\")'><b>here</b></a> to send an instructions via email!";
    					}	
    				} else {
	    				$searchModel = new UserSearch();
	    				$queryParams = Yii::$app->request->queryParams;
	    				$dataProvider = $searchModel->search($queryParams);
	    
	    				$post_msg["type"] = "success";
	    				$post_msg["text"] = "The user data have been successfully created.";
	    					
	    				return $this->render('index', [
	    						'searchModel' => $searchModel,
	    						'dataProvider' => $dataProvider,
	    						'post_msg' => $post_msg,
	    				]);
	    			}
	    		}
    		}
    	}
    
    	if(isset($_POST[$model->formName()]["username"])){
    		$model->username = $_POST[$model->formName()]["username"];
    	}
    
    	if(isset($_POST[$model->formName()]["password"])){
    		$model->password = $_POST[$model->formName()]["password"];
    	}
    
    	if(isset($_POST[$model->formName()]["repeat_password"])){
    		$model->repeat_password = $_POST[$model->formName()]["repeat_password"];
    	}
    
    	if(isset($_POST[$model->formName()]["email"])){
    		$model->email = $_POST[$model->formName()]["email"];
    	}
    
    	if(isset($_POST[$model->formName()]["repeat_email"])){
    		$model->repeat_email = $_POST[$model->formName()]["repeat_email"];
    	}
    
    	if(isset($_POST[$model->formName()]["salutation"])){
    		$model->salutation = $_POST[$model->formName()]["salutation"];
    	}
    
    	if(isset($_POST[$model->formName()]["first_name"])){
    		$model->first_name = $_POST[$model->formName()]["first_name"];
    	}
    
    	if(isset($_POST[$model->formName()]["middle_name"])){
    		$model->middle_name = $_POST[$model->formName()]["middle_name"];
    	}
    
    	if(isset($_POST[$model->formName()]["last_name"])){
    		$model->last_name = $_POST[$model->formName()]["last_name"];
    	}
    
    	if(isset($_POST[$model->formName()]["initials"])){
    		$model->initials = $_POST[$model->formName()]["initials"];
    	}    
   
    	if(isset($_POST[$model->formName()]["info"])){
    		$model->info = $_POST[$model->formName()]["info"];
    	}
    
    	if(isset($_POST[$model->formName()]["bio_statement"])){
    		$model->bio_statement = $_POST[$model->formName()]["bio_statement"];
    	}
    
    	if(isset($_POST[$model->formName()]["city"])){
    		$model->city = $_POST[$model->formName()]["city"];
    	}
    
    	if(isset($_POST[$model->formName()]["url"])){
    		$model->url = $_POST[$model->formName()]["url"];
    	}
    
    	if(isset($_POST[$model->formName()]["phone"])){
    		$model->phone = $_POST[$model->formName()]["phone"];
    	}
    
    	if(isset($_POST[$model->formName()]["fax"])){
    		$model->fax = $_POST[$model->formName()]["fax"];
    	}
    
    	if(isset($_POST[$model->formName()]["mailing_address"])){
    		$model->mailing_address = $_POST[$model->formName()]["mailing_address"];
    	}
    
    	if(isset($_POST[$model->formName()]["reviewer_interests"])){
    		$model->reviewer_interests = $_POST[$model->formName()]["reviewer_interests"];
    	}
    
    	$common_vars = new CommonVariables();
    
    	if(isset($_POST[$model->formName()]["gender"])){
    		$additional_params["gender_opt"] = ['prompt' => '--- Select ---', 'options' => [$_POST[$model->formName()]["gender"] => ['Selected' => 'selected']]];
    	} else {
    		$additional_params["gender_opt"] = ['prompt' => '--- Select ---'];
    	}
    
    	if(isset($_POST[$model->formName()]["country"])){
    		$additional_params["country_opt"] = ['prompt' => '--- Select ---', 'options' => [$_POST[$model->formName()]["country"] => ['Selected' => 'selected']]];
    	} else {
    		$additional_params["country_opt"] = ['prompt' => '--- Select ---'];
    	}
    
    	$additional_vars = (object)$additional_params;
    
    	if(isset($_POST[$model->formName()]['send_confirmation'])){
    		$model->send_confirmation = $_POST[$model->formName()]['send_confirmation'];
    	} else {
    		$model->send_confirmation = true;
    	}
    
    	if(isset($_POST[$model->formName()]['is_reader'])){
    		$model->is_reader = $_POST[$model->formName()]['is_reader'];
    	} else {
    		$model->is_reader = true;
    	}
    
    	if(isset($_POST[$model->formName()]['is_author'])){
    		$model->is_author = $_POST[$model->formName()]['is_author'];
    	} else {
    		$model->is_author = true;
    	}
    
    	if(isset($_POST[$model->formName()]['is_reviewer'])){
    		$model->is_reviewer = $_POST[$model->formName()]['is_reviewer'];
    	}
    	 
    	if(isset($_POST[$model->formName()]['is_editor'])){
    		$model->is_editor = $_POST[$model->formName()]['is_editor'];
    	} else if (isset($currentUserModel) && isset($currentUserModel->is_editor)) {
    		$model->is_editor = $currentUserModel->is_editor;
    	}
    
    	if(isset($_POST[$model->formName()]['is_admin'])){
    		$model->is_admin = $_POST[$model->formName()]['is_admin'];
    	} else if (isset($currentUserModel) && isset($currentUserModel->is_admin)) {
    		$model->is_admin = $currentUserModel->is_admin;
    	}
    
    	return $this->render('create', [
    			'model' => $model,
    			'common_vars' => $common_vars,
    			'additional_vars' => $additional_vars,
    			'post_msg' => $post_msg,
    			'is_unregistered_author' => false
    	]);
    }
    
    //called on actionProfile & actionEdit
    public function _editUserForm($currentId)
    {    	
    	$model = new UserProfileForm();
    	$post_msg = null;
    	$is_get_command = false;
    	
    	if ($model->load(Yii::$app->request->post())) {
    		if ($user = $model->updateUserProfile($currentId)) {
    			if(isset($user)) { 
    				if(isset($user["duplicate_message"])) {
    					if($user["duplicate_message"] === "existing email and username error"){
    						$post_msg["type"] = "warning";
    						$post_msg["text"] = "The username and the email address have already been taken. Try with anothers.<br><br>";
    						$post_msg["text"] .= "If this was not you, click <a id='duplicatetrigger' onclick='userScript_clickDuplicateUser(\"".$user["duplicate_username_user"]->id."\",\"".$user["duplicate_email_user"]->id."\")'><b>here</b></a> to send an instructions via email!";
    					} else if($user["duplicate_message"] === "existing email error"){
    						$post_msg["type"] = "warning";
    						$post_msg["text"] = "This email address has already been taken. Try with another one.<br><br>";
    						$post_msg["text"] .= "If this was not you, click <a id='duplicatetrigger' onclick='userScript_clickDuplicateUser(\"".intval(0)."\",\"".$user["duplicate_email_user"]->id."\")'><b>here</b></a> to send an instructions via email!";
    					} else if($user["duplicate_message"] === "existing username error"){
    						$post_msg["type"] = "warning";
    						$post_msg["text"] = "This username has already been taken. Try with another one.";
    						//$post_msg["text"] .= "If this was not you, click <a id='duplicatetrigger' onclick='userScript_clickDuplicateUser(\"".$user["duplicate_username_user"]->id."\",\"".intval(0)."\")'><b>here</b></a> to send an instructions via email!";
    					}
    				} else {
	    				$searchModel = new UserSearch();
	    				$queryParams = Yii::$app->request->queryParams;
	    				$dataProvider = $searchModel->search($queryParams);
	    				
	    				$post_msg["type"] = "success";
	    				$post_msg["text"] = "The user data have been successfully updated.";
	
	    				return $this->render('index', [
	    						'searchModel' => $searchModel,
	    						'dataProvider' => $dataProvider,
	    						'post_msg' => $post_msg,
	    				]);
	    			}
	    		}
    		}
    	} else {
    		$is_get_command = true;    		
    	}
    	
    	$currentUserModel = $this->findModel($currentId);
    	if($is_get_command && $currentUserModel->is_unregistered_author){
    		$currentUserModel->is_reader = true;
    		$currentUserModel->send_confirmation = true;
    	}
   		$currentUser = [$model->formName() => $currentUserModel->attributes];
    	$model->load($currentUser);    	 
    	
    	if(isset($_POST[$model->formName()]["username"])){
    		$model->username = $_POST[$model->formName()]["username"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->username)){
    		$model->username = $currentUserModel->username;
    	}
    	
    	if(isset($_POST[$model->formName()]["password"])){
    		$model->password = $_POST[$model->formName()]["password"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->password_hash)){
    		$model->password = $currentUserModel->password_hash;
    	}
    	
    	if(isset($_POST[$model->formName()]["repeat_password"])){
    		$model->repeat_password = $_POST[$model->formName()]["repeat_password"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->password_hash)){
    		$model->repeat_password = $currentUserModel->password_hash;
    	}
    	
    	if(isset($_POST[$model->formName()]["email"])){
    		$model->email = $_POST[$model->formName()]["email"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->email)){
    		$model->email = $currentUserModel->email;
    	}
    	
    	if(isset($_POST[$model->formName()]["repeat_email"])){
    		$model->repeat_email = $_POST[$model->formName()]["repeat_email"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->email)){
    		$model->repeat_email = $currentUserModel->email;
    	}
    	
    	if(isset($_POST[$model->formName()]["salutation"])){
    		$model->salutation = $_POST[$model->formName()]["salutation"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->salutation)){
    		$model->salutation = $currentUserModel->salutation;
    	}
    	
    	if(isset($_POST[$model->formName()]["first_name"])){
    		$model->first_name = $_POST[$model->formName()]["first_name"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->first_name)){
    		$model->first_name = $currentUserModel->first_name;
    	}
    	
    	if(isset($_POST[$model->formName()]["middle_name"])){
    		$model->middle_name = $_POST[$model->formName()]["middle_name"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->middle_name)){
    		$model->middle_name = $currentUserModel->middle_name;
    	}
    	
    	if(isset($_POST[$model->formName()]["last_name"])){
    		$model->last_name = $_POST[$model->formName()]["last_name"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->last_name)){
    		$model->last_name = $currentUserModel->last_name;
    	}
    	
    	if(isset($_POST[$model->formName()]["initials"])){
    		$model->initials = $_POST[$model->formName()]["initials"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->initials)){
    		$model->initials = $currentUserModel->initials;
    	}    	
   	
    	if(isset($_POST[$model->formName()]["info"])){
    		$model->info = $_POST[$model->formName()]["info"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->info)){
    		$model->info = $currentUserModel->info;
    	}
    	
    	if(isset($_POST[$model->formName()]["bio_statement"])){
    		$model->bio_statement = $_POST[$model->formName()]["bio_statement"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->bio_statement)){
    		$model->bio_statement = $currentUserModel->bio_statement;
    	}
    	
    	if(isset($_POST[$model->formName()]["city"])){
    		$model->city = $_POST[$model->formName()]["city"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->city)){
    		$model->city = $currentUserModel->city;
    	}
    	
    	if(isset($_POST[$model->formName()]["url"])){
    		$model->url = $_POST[$model->formName()]["url"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->url)){
    		$model->url = $currentUserModel->url;
    	}
    	
    	if(isset($_POST[$model->formName()]["phone"])){
    		$model->phone = $_POST[$model->formName()]["phone"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->phone)){
    		$model->phone = $currentUserModel->phone;
    	}
    	
    	if(isset($_POST[$model->formName()]["fax"])){
    		$model->fax = $_POST[$model->formName()]["fax"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->fax)){
    		$model->fax = $currentUserModel->fax;
    	}
    	
    	if(isset($_POST[$model->formName()]["mailing_address"])){
    		$model->mailing_address = $_POST[$model->formName()]["mailing_address"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->mailing_address)){
    		$model->mailing_address = $currentUserModel->mailing_address;
    	}
    	
    	if(isset($_POST[$model->formName()]["reviewer_interests"])){
    		$model->reviewer_interests = $_POST[$model->formName()]["reviewer_interests"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->reviewer_interests)){
    		$model->reviewer_interests = $currentUserModel->reviewer_interests;
    	}
    	
    	$common_vars = new CommonVariables();
    	 
    	if(isset($_POST[$model->formName()]["gender"])){
    		$additional_params["gender_opt"] = ['prompt' => '--- Select ---', 'options' => [$_POST[$model->formName()]["gender"] => ['Selected' => 'selected']]];
    	} else if(isset($currentUserModel) && isset($currentUserModel->gender)){
    		$additional_params["gender_opt"] = ['prompt' => '--- Select ---', 'options' => [$currentUserModel->gender => ['Selected' => 'selected']]];
    	} else {
    		$additional_params["gender_opt"] = ['prompt' => '--- Select ---'];
    	}
    	 
    	if(isset($_POST[$model->formName()]["country"])){
    		$additional_params["country_opt"] = ['prompt' => '--- Select ---', 'options' => [$_POST[$model->formName()]["country"] => ['Selected' => 'selected']]];
    	} else if(isset($currentUserModel) && isset($currentUserModel->country)){
    		$additional_params["country_opt"] = ['prompt' => '--- Select ---', 'options' => [$currentUserModel->country => ['Selected' => 'selected']]];
    	} else {
    		$additional_params["country_opt"] = ['prompt' => '--- Select ---'];
    	}
    	 
    	$additional_vars = (object)$additional_params;
    	 
    	if(isset($_POST[$model->formName()]['send_confirmation'])){
    		$model->send_confirmation = $_POST[$model->formName()]['send_confirmation'];
    	} else if (isset($currentUserModel) && isset($currentUserModel->send_confirmation)) {
    		$model->send_confirmation = $currentUserModel->send_confirmation;
    	} else {
    		$model->send_confirmation = true;
    	}
    	 
    	if(isset($_POST[$model->formName()]['is_reader'])){
    		$model->is_reader = $_POST[$model->formName()]['is_reader'];
    	} else if (isset($currentUserModel) && isset($currentUserModel->is_reader)) {
    		$model->is_reader = $currentUserModel->is_reader;
    	} else {
    		$model->is_reader = true;
    	}
    	 
    	if(isset($_POST[$model->formName()]['is_author'])){
    		$model->is_author = $_POST[$model->formName()]['is_author'];
    	} else if (isset($currentUserModel) && isset($currentUserModel->is_author)) {
    		$model->is_author = $currentUserModel->is_author;
    	} else {
    		$model->is_author = true;
    	}
    	 
    	if(isset($_POST[$model->formName()]['is_reviewer'])){
    		$model->is_reviewer = $_POST[$model->formName()]['is_reviewer'];
    	} else if (isset($currentUserModel) && isset($currentUserModel->is_reviewer)) {
    		$model->is_reviewer = $currentUserModel->is_reviewer;
    	}
    	
    	if(isset($_POST[$model->formName()]['is_editor'])){
    		$model->is_editor = $_POST[$model->formName()]['is_editor'];
    	} else if (isset($currentUserModel) && isset($currentUserModel->is_editor)) {
    		$model->is_editor = $currentUserModel->is_editor;
    	}
    	
    	if(isset($_POST[$model->formName()]['is_admin'])){
    		$model->is_admin = $_POST[$model->formName()]['is_admin'];
    	} else if (isset($currentUserModel) && isset($currentUserModel->is_admin)) {
    		$model->is_admin = $currentUserModel->is_admin;
    	}
    	
    	return $this->render('update', [
    			'model' => $model,
    			'common_vars' => $common_vars,
    			'additional_vars' => $additional_vars,
    			'post_msg' => $post_msg,
    			'current_id' => $currentId,
    			'is_unregistered_author' => $currentUserModel->is_unregistered_author
    	]);
    }    
    
    /**
     * Creates a new UnregisteredUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateunregisteredauthor()
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	//if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    	//	return $this->redirect(['site/error']);
    	//}
    	 
    	$model = new User();
    
    	if ($model->load(Yii::$app->request->post()) && $model->save()) {
    		return $this->redirect(['view', 'id' => $model->id]);
    	} else {
    		return $this->_createUnregisteredUserForm();
    	}
    }
    
    /**
     * Updates an existing UnregisteredUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateunregisteredauthor($id)
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	//if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    	//	return $this->redirect(['site/error']);
    	//}
    	 
    	return $this->_editUnregisteredUserForm($id);
    }

    //called on actionCreate
    public function _createUnregisteredUserForm()
    {
    	$model = new UnregisteredUserProfileForm();
    	$post_msg = null;
    
    	if ($model->load(Yii::$app->request->post())) {
    		if ($user = $model->createUnregisteredUserProfile()) {
    			if(isset($user)) {
    				if(isset($user["duplicate_message"])) {
		    			if($user["duplicate_message"] === "existing email and username error"){
		    				$post_msg["type"] = "warning";
		    				$post_msg["text"] = "The username and the email address have already been taken. Try with anothers.<br><br>";
		    				$post_msg["text"] .= "If this was not you, click <a id='duplicatetrigger' onclick='userScript_clickDuplicateUser(\"".$user["duplicate_username_user"]->id."\",\"".$user["duplicate_email_user"]->id."\")'><b>here</b></a> to send an instructions via email!";
		    			} else if($user["duplicate_message"] === "existing email error"){
		    				$post_msg["type"] = "warning";
		    				$post_msg["text"] = "This email address has already been taken. Try with another one.<br><br>";
		    				$post_msg["text"] .= "If this was not you, click <a id='duplicatetrigger' onclick='userScript_clickDuplicateUser(\"".intval(0)."\",\"".$user["duplicate_email_user"]->id."\")'><b>here</b></a> to send an instructions via email!";
		    			} else if($user["duplicate_message"] === "existing username error"){
		    				$post_msg["type"] = "warning";
		    				$post_msg["text"] = "This username has already been taken. Try with another one.";
		    				//$post_msg["text"] .= "If this was not you, click <a id='duplicatetrigger' onclick='userScript_clickDuplicateUser(\"".$user["duplicate_username_user"]->id."\",\"".intval(0)."\")'><b>here</b></a> to send an instructions via email!";
		    			} 
    				} else {
	    				$searchModel = new UserSearch();
	    				$queryParams = Yii::$app->request->queryParams;
	    				$dataProvider = $searchModel->search($queryParams);
	    
	    				$post_msg["type"] = "success";
	    				$post_msg["text"] = "The unregistered user data have been successfully created.";
	    					
	    				return $this->render('index', [
	    						'searchModel' => $searchModel,
	    						'dataProvider' => $dataProvider,
	    						'post_msg' => $post_msg,
	    				]);
	    			}
	    		}
    		}
    	}
    	 
    	if(isset($_POST[$model->formName()]["username"])){
    		$model->username = $_POST[$model->formName()]["username"];
    	}
    	 
    	if(isset($_POST[$model->formName()]["email"])){
    		$model->email = $_POST[$model->formName()]["email"];
    	}
    
    	if(isset($_POST[$model->formName()]["repeat_email"])){
    		$model->repeat_email = $_POST[$model->formName()]["repeat_email"];
    	}
    	 
    	if(isset($_POST[$model->formName()]["first_name"])){
    		$model->first_name = $_POST[$model->formName()]["first_name"];
    	}
    
    	if(isset($_POST[$model->formName()]["middle_name"])){
    		$model->middle_name = $_POST[$model->formName()]["middle_name"];
    	}
    
    	if(isset($_POST[$model->formName()]["last_name"])){
    		$model->last_name = $_POST[$model->formName()]["last_name"];
    	}
    
    	if(isset($_POST[$model->formName()]["initials"])){
    		$model->initials = $_POST[$model->formName()]["initials"];
    	}    
   	 
    	if(isset($_POST[$model->formName()]["mailing_address"])){
    		$model->mailing_address = $_POST[$model->formName()]["mailing_address"];
    	}
    
    	$common_vars = new CommonVariables();
    
    	if(isset($_POST[$model->formName()]["gender"])){
    		$additional_params["gender_opt"] = ['prompt' => '--- Select ---', 'options' => [$_POST[$model->formName()]["gender"] => ['Selected' => 'selected']]];
    	} else {
    		$additional_params["gender_opt"] = ['prompt' => '--- Select ---'];
    	}
    
    	if(isset($_POST[$model->formName()]["country"])){
    		$additional_params["country_opt"] = ['prompt' => '--- Select ---', 'options' => [$_POST[$model->formName()]["country"] => ['Selected' => 'selected']]];
    	} else {
    		$additional_params["country_opt"] = ['prompt' => '--- Select ---'];
    	}
    
    	$additional_vars = (object)$additional_params;
    
    	return $this->render('create_unregisteredauthor', [
    			'model' => $model,
    			'common_vars' => $common_vars,
    			'additional_vars' => $additional_vars,
    			'post_msg' => $post_msg,
    	]);
    }
    
    //called on actionEdit
    public function _editUnregisteredUserForm($currentId)
    {
    	$model = new UnregisteredUserProfileForm();
    	$post_msg = null;
    
    	if ($model->load(Yii::$app->request->post())) {
    		if ($user = $model->updateUnregisteredUserProfile($currentId)) {
    			if(isset($user)) {
    				if(isset($user["duplicate_message"])) {
    					if($user["duplicate_message"] === "existing email and username error"){
    						$post_msg["type"] = "warning";
    						$post_msg["text"] = "The username and the email address have already been taken. Try with anothers.<br><br>";
    						$post_msg["text"] .= "If this was not you, click <a id='duplicatetrigger' onclick='userScript_clickDuplicateUser(\"".$user["duplicate_username_user"]->id."\",\"".$user["duplicate_email_user"]->id."\")'><b>here</b></a> to send an instructions via email!";
    					} else if($user["duplicate_message"] === "existing email error"){
    						$post_msg["type"] = "warning";
    						$post_msg["text"] = "This email address has already been taken. Try with another one.<br><br>";
    						$post_msg["text"] .= "If this was not you, click <a id='duplicatetrigger' onclick='userScript_clickDuplicateUser(\"".intval(0)."\",\"".$user["duplicate_email_user"]->id."\")'><b>here</b></a> to send an instructions via email!";
    					} else if($user["duplicate_message"] === "existing username error"){
    						$post_msg["type"] = "warning";
    						$post_msg["text"] = "This username has already been taken. Try with another one.";
    						//$post_msg["text"] .= "If this was not you, click <a id='duplicatetrigger' onclick='userScript_clickDuplicateUser(\"".$user["duplicate_username_user"]->id."\",\"".intval(0)."\")'><b>here</b></a> to send an instructions via email!";
    					}
    				} else {
	    				$searchModel = new UserSearch();
	    				$queryParams = Yii::$app->request->queryParams;
	    				$dataProvider = $searchModel->search($queryParams);
	    
	    				$post_msg["type"] = "success";
	    				$post_msg["text"] = "The user data have been successfully updated.";
	    
	    				return $this->render('index', [
	    						'searchModel' => $searchModel,
	    						'dataProvider' => $dataProvider,
	    						'post_msg' => $post_msg,
	    				]);
	    			}
	    		}
    		}
    	}
    
    	$currentUserModel = $this->findModel($currentId);
    	$currentUser = [$model->formName() => $currentUserModel->attributes];
    	$model->load($currentUser);
    
    	if(isset($_POST[$model->formName()]["username"])){
    		$model->username = $_POST[$model->formName()]["username"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->username)){
    		$model->username = $currentUserModel->username;
    	}
    
    	if(isset($_POST[$model->formName()]["email"])){
    		$model->email = $_POST[$model->formName()]["email"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->email)){
    		$model->email = $currentUserModel->email;
    	}
    
    	if(isset($_POST[$model->formName()]["repeat_email"])){
    		$model->repeat_email = $_POST[$model->formName()]["repeat_email"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->email)){
    		$model->repeat_email = $currentUserModel->email;
    	}
    
    	if(isset($_POST[$model->formName()]["first_name"])){
    		$model->first_name = $_POST[$model->formName()]["first_name"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->first_name)){
    		$model->first_name = $currentUserModel->first_name;
    	}
    
    	if(isset($_POST[$model->formName()]["middle_name"])){
    		$model->middle_name = $_POST[$model->formName()]["middle_name"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->middle_name)){
    		$model->middle_name = $currentUserModel->middle_name;
    	}
    
    	if(isset($_POST[$model->formName()]["last_name"])){
    		$model->last_name = $_POST[$model->formName()]["last_name"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->last_name)){
    		$model->last_name = $currentUserModel->last_name;
    	}
    
    	if(isset($_POST[$model->formName()]["initials"])){
    		$model->initials = $_POST[$model->formName()]["initials"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->initials)){
    		$model->initials = $currentUserModel->initials;
    	}
    
    	if(isset($_POST[$model->formName()]["mailing_address"])){
    		$model->mailing_address = $_POST[$model->formName()]["mailing_address"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->mailing_address)){
    		$model->mailing_address = $currentUserModel->mailing_address;
    	}
    
    	$common_vars = new CommonVariables();
    
    	if(isset($_POST[$model->formName()]["gender"])){
    		$additional_params["gender_opt"] = ['prompt' => '--- Select ---', 'options' => [$_POST[$model->formName()]["gender"] => ['Selected' => 'selected']]];
    	} else if(isset($currentUserModel) && isset($currentUserModel->gender)){
    		$additional_params["gender_opt"] = ['prompt' => '--- Select ---', 'options' => [$currentUserModel->gender => ['Selected' => 'selected']]];
    	} else {
    		$additional_params["gender_opt"] = ['prompt' => '--- Select ---'];
    	}
    
    	if(isset($_POST[$model->formName()]["country"])){
    		$additional_params["country_opt"] = ['prompt' => '--- Select ---', 'options' => [$_POST[$model->formName()]["country"] => ['Selected' => 'selected']]];
    	} else if(isset($currentUserModel) && isset($currentUserModel->country)){
    		$additional_params["country_opt"] = ['prompt' => '--- Select ---', 'options' => [$currentUserModel->country => ['Selected' => 'selected']]];
    	} else {
    		$additional_params["country_opt"] = ['prompt' => '--- Select ---'];
    	}
    
    	$additional_vars = (object)$additional_params;
    
    	return $this->render('update_unregisteredauthor', [
    			'model' => $model,
    			'common_vars' => $common_vars,
    			'additional_vars' => $additional_vars,
    			'post_msg' => $post_msg,
    			'current_id' => $currentId,
    	]);
    }
    
    /*
     * Asynch functions called with Ajax - User (click on link for notifying users about duplicate email)
     */
    public function actionAsynchAlertDuplicateUser()
    {
    	$usernameUserID = Yii::$app->getRequest()->post('usernameUserID');
    	$emailUserID = Yii::$app->getRequest()->post('emailUserID');
    	
    	if($emailUserID != 0) {
    		$type = "email";
    		if($emailUserID == $usernameUserID){
    			$type = "username (".$usernameUser->username.") and email";
    		}
    		$emailUser = User::findOne([
    				'id' => $emailUserID
    		]);  
    		if(isset($emailUser)){
    			$emailUser->helper_token = Yii::$app->security->generateRandomString() . '_' . time();
    			$emailUser->updated_at = date("Y-m-d H:i:s");
    			if ($emailUser->save()) {
    				return \Yii::$app->mailer->compose(['html' => 'duplicateUserReportToken-html', 'text' => 'duplicateUserReportToken-text'], ['user' => $emailUser, 'type' => $type])
						    				 ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'])
						    				 ->setTo($emailUser->email)
						    				 ->setSubject('Report for duplicate user!')
						    				 ->send();
    			} else {
    				Yii::error("UserController->actionAsynchAlertDuplicateUser(1): ".json_encode($emailUser->getErrors()), "custom_errors_users");
    				return "Failure! Report for duplicate user has not been sent.";
    			}
       		}
    	}

    	return "Report for duplicate user has been successfully sent.";
    }
    
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
