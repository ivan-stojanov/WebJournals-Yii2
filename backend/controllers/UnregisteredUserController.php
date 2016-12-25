<?php

namespace backend\controllers;

use Yii;
use common\models\UnregisteredUser;
use backend\models\UnregisteredUserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\CommonVariables;
use backend\models\UnregisteredUserProfileForm;

/**
 * UnregisteredUserController implements the CRUD actions for UnregisteredUser model.
 */
class UnregisteredUserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
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
    	];
    }

    /**
     * Lists all UnregisteredUser models.
     * @return mixed
     */
    public function actionIndex()
    {
    	if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    		return $this->redirect(['site/error']);
    	}    	 
    	
        $searchModel = new UnregisteredUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $post_msg = null;
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'post_msg' => $post_msg,
        ]);
    }

    /**
     * Displays a single UnregisteredUser model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    		return $this->redirect(['site/error']);
    	}
    	
    	$model = $this->findModel($id);
    	
    	$user_can_modify = (Yii::$app->session->get('user.is_admin'));
    	$user_can_modify = $user_can_modify || (Yii::$app->user->id == $model->user_creator_id);
    	
    	$common_vars = new CommonVariables();
    	
        return $this->render('view', [
            'model' => $model,
        	'common_vars' => $common_vars,
        	'user_can_modify' => $user_can_modify,
        ]);
    }

    /**
     * Creates a new UnregisteredUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    		return $this->redirect(['site/error']);
    	}   	
    	
        $model = new UnregisteredUser();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->unregistered_user_id]);
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
    public function actionUpdate($id)
    {
    	if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    		return $this->redirect(['site/error']);
    	}  	
    	
        return $this->_editUnregisteredUserForm($id);
    }

    /**
     * Deletes an existing UnregisteredUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    		return $this->redirect(['site/error']);
    	}   	
    	
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    //called on actionEdit
    public function _editUnregisteredUserForm($currentId)
    {
    	$model = new UnregisteredUserProfileForm();
    	$post_msg = null;

    	if ($model->load(Yii::$app->request->post())) {
    		if ($user = $model->updateUnregisteredUserProfile($currentId)) {
    
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
    				$searchModel = new UnregisteredUserSearch();
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
    	 
    	if(isset($_POST[$model->formName()]["affiliation"])){
    		$model->affiliation = $_POST[$model->formName()]["affiliation"];
    	} else if(isset($currentUserModel) && isset($currentUserModel->affiliation)){
    		$model->affiliation = $currentUserModel->affiliation;
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

		return $this->render('update', [
    			'model' => $model,
    			'common_vars' => $common_vars,
    			'additional_vars' => $additional_vars,
    			'post_msg' => $post_msg,
    			'current_id' => $currentId,
    	]);
    }

    //called on actionCreate
    public function _createUnregisteredUserForm()
    {
    	$model = new UnregisteredUserProfileForm();
    	$post_msg = null;

    	if ($model->load(Yii::$app->request->post())) {
    		if ($user = $model->createUnregisteredUserProfile()) {
    			
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
    				$searchModel = new UnregisteredUserSearch();
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
    
    	if(isset($_POST[$model->formName()]["affiliation"])){
    		$model->affiliation = $_POST[$model->formName()]["affiliation"];
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

    	return $this->render('create', [
    			'model' => $model,
    			'common_vars' => $common_vars,
    			'additional_vars' => $additional_vars,
    			'post_msg' => $post_msg,
    	]);
    }      
    
    /**
     * Finds the UnregisteredUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UnregisteredUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UnregisteredUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
