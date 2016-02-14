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
use backend\models\UserProfileForm;

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
        				'actions' => [	'index', 'view', 'create', 'update', 'delete', 'profile', 'captcha',
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
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['error']);
    	}
    	
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
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
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['error']);
    	}
    	
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['error']);
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
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['error']);
    	}
    	
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(['error']);
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
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['error']);
    	}
    	
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    

    public function actionProfile()
    {   	
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(['error']);
    	}
    
    	$currentId = Yii::$app->user->identity->attributes["id"];
    	
    	//make sure that is current user
    	if((isset(Yii::$app->user->identity) && isset(Yii::$app->user->identity->attributes["id"]) &&
    			isset($currentId) && (Yii::$app->user->identity->attributes["id"] == $currentId)))
    	{
    		return $this->_editUserForm($currentId);
    	}

    	return $this->redirect(['error']);
    }
    
    //called on actionProfile & actionEdit
    public function _editUserForm($currentId)
    {    	
    	$model = new UserProfileForm();
    	$post_msg = null;
    	
    	if ($model->load(Yii::$app->request->post())) {
    		if ($user = $model->updateUserProfile($currentId)) {
    			 
    			if($user === "existing email and username error"){
    				$post_msg["type"] = "warning";
    				$post_msg["text"] = "The username and the email address have already been taken. Try with anothers.";
    			} else if($user === "existing email error"){
    				$post_msg["type"] = "warning";
    				$post_msg["text"] = "This email address has already been taken. Try with another one.";
    			} else if($user === "existing username error"){
    				$post_msg["type"] = "warning";
    				$post_msg["text"] = "This username has already been taken. Try with another one.";
    			} else {
    				$searchModel = new UserSearch();
    				$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    				
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

   		$currentUserModel = $this->findModel($currentId);
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
    	
    	if(isset($_POST[$model->formName()]["affiliation"])){
    		$model->affiliation = $_POST[$model->formName()]["affiliation"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->affiliation)){
    		$model->affiliation = $currentUserModel->affiliation;
    	}
    	
    	if(isset($_POST[$model->formName()]["signature"])){
    		$model->signature = $_POST[$model->formName()]["signature"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->signature)){
    		$model->signature = $currentUserModel->signature;
    	}
    	
    	if(isset($_POST[$model->formName()]["bio_statement"])){
    		$model->bio_statement = $_POST[$model->formName()]["bio_statement"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->bio_statement)){
    		$model->bio_statement = $currentUserModel->bio_statement;
    	}
    	
    	if(isset($_POST[$model->formName()]["orcid_id"])){
    		$model->orcid_id = $_POST[$model->formName()]["orcid_id"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->orcid_id)){
    		$model->orcid_id = $currentUserModel->orcid_id;
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
    	]);
    }    
    
    //called on actionCreate
    public function _createUserForm()
    {
    	$model = new UserProfileForm();
    	$post_msg = null;
    	 
    	if ($model->load(Yii::$app->request->post())) {
    		if ($user = $model->createUserProfile()) {
    
    			if($user === "existing email and username error"){
    				$post_msg["type"] = "warning";
    				$post_msg["text"] = "The username and the email address have already been taken. Try with anothers.";
    			} else if($user === "existing email error"){
    				$post_msg["type"] = "warning";
    				$post_msg["text"] = "This email address has already been taken. Try with another one.";
    			} else if($user === "existing username error"){
    				$post_msg["type"] = "warning";
    				$post_msg["text"] = "This username has already been taken. Try with another one.";
    			} else {
    				$searchModel = new UserSearch();
    				$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
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
    	 
    	if(isset($_POST[$model->formName()]["affiliation"])){
    		$model->affiliation = $_POST[$model->formName()]["affiliation"];
    	}
    	 
    	if(isset($_POST[$model->formName()]["signature"])){
    		$model->signature = $_POST[$model->formName()]["signature"];
    	}
    	 
    	if(isset($_POST[$model->formName()]["bio_statement"])){
    		$model->bio_statement = $_POST[$model->formName()]["bio_statement"];
    	}
    	 
    	if(isset($_POST[$model->formName()]["orcid_id"])){
    		$model->orcid_id = $_POST[$model->formName()]["orcid_id"];
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
    	]);
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
