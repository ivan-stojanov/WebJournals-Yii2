<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\Keyword;
use backend\models\KeywordSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KeywordController implements the CRUD actions for Keyword model.
 */
class KeywordController extends Controller
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
                	'archive' => ['POST'],
                    'unarchive' => ['POST'],
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
     * Lists all Keyword models.
     * @return mixed
     */
    public function actionIndex()
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	//if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    	//	return $this->redirect(['site/error']);
    	//}
    	
        $searchModel = new KeywordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $post_msg = null;
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'post_msg' => $post_msg,
        ]);
    }

    /**
     * Displays a single Keyword model.
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
    	
    	$modelKeyword = $this->findModel($id);
    	
    	$user_can_modify = (Yii::$app->session->get('user.is_admin'));
    	    	
        return $this->render('view', [
            'modelKeyword' => $modelKeyword,
        	'user_can_modify' => $user_can_modify,
        ]);
    }

    /**
     * Creates a new Keyword model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	//if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    	//	return $this->redirect(['site/error']);
    	//}
    	
        $modelKeyword = new Keyword();
        $modelKeyword->created_on = date("Y-m-d H:i:s");
        $post_msg = null;  
        
        if ($modelKeyword->load(Yii::$app->request->post())) {
        	
        	if(Yii::$app->session->get('user.is_admin') != true) {
        		$tmpModelKeyword = Keyword::findOne([
        				'content' => $modelKeyword->content
        		]);
        		if($tmpModelKeyword != null) {
        			Yii::$app->session->setFlash('error', 'Keyword has not been created! Please contact our adminstrator for more details!');
        			return $this->redirect(['index']);
        		}
        	}      	
        	
        	if($modelKeyword->save()) {
        		if(Yii::$app->session->get('user.is_admin') != true) {        			
        			$user = User::findOne(intval(Yii::$app->user->id));        			
        			$email_sent = Yii::$app->mailer->compose(['html' => 'keywordCreated-html', 'text' => 'keywordCreated-text'], ['keywordCreator' => $user, 'keyword' => $modelKeyword])
				        			->setTo(Yii::$app->params['adminEmail'])
				        			->setFrom([$user->email => $user->fullName])
				        			->setSubject("Keyword Creation Report!")
				        			->send();
        			if(!$email_sent) {
        				Yii::error("KeywordController->actionCreate(1): Failure! Report for keyword creation has not been sent", "custom_errors_keywords");
        			}        			 
        		}
        		Yii::$app->session->setFlash('success', 'Keyword has been successfully created');
        	}
        	
        	return $this->redirect(['view', 'id' => $modelKeyword->keyword_id]);
        } else {
            return $this->render('create', [
                'modelKeyword' => $modelKeyword,
            	'post_msg' => $post_msg,
            ]);
        }
    }

    /**
     * Updates an existing Keyword model.
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
    	
        $modelKeyword = $this->findModel($id);
        $modelKeyword->updated_on = date("Y-m-d H:i:s");
        $post_msg = null;

        if ($modelKeyword->load(Yii::$app->request->post()) && $modelKeyword->save()) {
            return $this->redirect(['view', 'id' => $modelKeyword->keyword_id]);
        } else {
            return $this->render('update', [
                'modelKeyword' => $modelKeyword,
            	'post_msg' => $post_msg,
            ]);
        }
    }

    /**
     * Archive an existing Keyword model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionArchive($id)
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	if (Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
    	$modelKeyword = $this->findModel($id);
    	$modelKeyword->is_deleted = 1;
    	
    	if ($modelKeyword->save()) {
    		Yii::$app->session->setFlash('success', 'Keyword has been successfully archived.');
    	} else {
    		Yii::error("KeywordController->actionArchive(1): ".json_encode($modelKeyword->getErrors()), "custom_errors_keywords");
    		Yii::$app->session->setFlash('error', 'Some error occured! Keyword has not been archived!');
    	}
    	return $this->redirect(['index']);
    }
    
    /**
     * Unarchive an existing Keyword model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUnarchive($id)
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	if (Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	 
    	$modelKeyword = $this->findModel($id);
    	$modelKeyword->is_deleted = 0;
    	 
    	if ($modelKeyword->save()) {
    		Yii::$app->session->setFlash('success', 'Keyword has been successfully unarchived.');
    	} else {
    		Yii::error("KeywordController->actionUnrchive(1): ".json_encode($modelKeyword->getErrors()), "custom_errors_keywords");
    		Yii::$app->session->setFlash('error', 'Some error occured! Keyword has not been unarchived!');
    	}
    	return $this->redirect(['index']);
    }

    /**
     * Finds the Keyword model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Keyword the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Keyword::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
