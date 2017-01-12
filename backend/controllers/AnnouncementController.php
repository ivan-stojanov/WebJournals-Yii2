<?php

namespace backend\controllers;

use Yii;
use common\models\Announcement;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * AnnouncementController implements the CRUD actions for Announcement model.
 */
class AnnouncementController extends Controller
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
        								'actions' => [	'index', 'view', 'create', 'update', 'delete',
        												'asynch-announcement-change-visibility',
        												'asynch-announcement-change-sorting',
        											 ],
        								'allow' => true,
        								'roles' => ['@'],
        						],
        				],
        		],
	            'verbs' => [
	                	'class' => VerbFilter::className(),
						'actions' => [
								'asynch-announcement-change-visibility' => ['post'],
								'asynch-announcement-change-sorting' => ['post'],
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
     * Lists all Announcement models.
     * @return mixed
     */
    public function actionIndex()
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}    	 
    	if (Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
        $announcements = Announcement::find()        
				        ->where(['is_deleted' => false])
				        ->orderBy([
					        		'sort_order' => SORT_ASC,
					        		'announcement_id' => SORT_DESC
				        		])
				        ->all();
        
        return $this->render('index', [
        		'model' => $announcements,
        ]);
    }

    /**
     * Displays a single Announcement model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}    	 
    	if (Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Announcement model.
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
    	
        $model = new Announcement();
        
        $model->created_on = date("Y-m-d H:i:s");
        $model->sort_order = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->announcement_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Announcement model.
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
    	
        $model = $this->findModel($id);
        $model->updated_on = date("Y-m-d H:i:s");

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->announcement_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Announcement model.
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
    	
        $model = $this->findModel($id);
        $model->is_deleted = true;
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Announcement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Announcement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Announcement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /*
     * Asynch functions called with Ajax - Announcement (list of announcements - change visibility)
     */
    public function actionAsynchAnnouncementChangeVisibility()
    {
    	$rowId = Yii::$app->getRequest()->post('rowId');
    	$isChecked = Yii::$app->getRequest()->post('isChecked');
    
    	$announcement = Announcement::findOne([
    			'announcement_id' => $rowId
    	]);
    	 
    	if($isChecked == true)	{ $isChecked = 1; }	else { $isChecked = 0; }
    	 
    	if(!isset($announcement)){
    		throw new \Exception('Announcement with id: '.$rowId.' not found.', 500);
    	}
    	 
    	$announcement->is_visible = $isChecked;
    	$announcement->updated_on = date("Y-m-d H:i:s");
    	 
    	if(!$announcement->save()){
    		throw new \Exception('Data not saved: '.print_r($announcement->errors, true), 500);
    	}
    	 
    	return "Announcement visibility has been successfully changed.";
    }
    
    /*
     * Asynch functions called with Ajax - Announcement (list of announcements - change sorting)
     */
    public function actionAsynchAnnouncementChangeSorting()
    {
    	$announcementReceivedIds = Yii::$app->getRequest()->post('sortedEntityIds');
    	 
    	$announcementIds = json_decode($announcementReceivedIds);
    	for ($i = 0; $i < count($announcementIds); $i++)
    	{
    		$rowId = intval(str_replace("row-number-", "", $announcementIds[$i]));
    		$announcementItem = Announcement::findOne([
    				'announcement_id' => $rowId
    		]);
    
    		if(!isset($announcementItem)){
    			throw new \Exception('Announcement with id: '.$rowId.' not found.', 500);
    		}
    
    		$announcementItem->sort_order = $i + 1;
    		$announcementItem->updated_on = date("Y-m-d H:i:s");
    
    		if(!$announcementItem->save()){
    			throw new \Exception('Data not saved: '.print_r($announcementItem->errors, true), 500);
    		}
    	}
    	
    	/*header('HTTP/1.1 404');
    	header('Content-type: application/json');
    	$response = new Response();
    	$response->format = Response::FORMAT_JSON;
    	//$response->statusText = "Announcements sorting has been successfully changed.";
    	$response->statusCode = 200;    	
    	$response->data = [
    			'message' => "Announcements sorting has been successfully changed.",
    	];    	
    	Yii::$app->end();*/
    	
    	return "Announcements sorting has been successfully changed.";
    }
}
