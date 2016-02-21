<?php

namespace backend\controllers;

use Yii;
use common\models\Volume;
use common\models\Issue;
use backend\models\VolumeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Object;

/**
 * VolumeController implements the CRUD actions for Volume model.
 */
class VolumeController extends Controller
{
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
     * Lists all Volume models.
     * @return mixed
     */
    public function actionIndex()
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['error']);
    	}
    	
    	$queryParams = Yii::$app->request->queryParams;
    	$queryParams['is_deleted'] = 0;
    	
        $searchModel = new VolumeSearch();
        $dataProvider = $searchModel->search($queryParams);
        $post_msg = null;
        
        return $this->render('index', [
        		'searchModel' => $searchModel,
        		'dataProvider' => $dataProvider,
        		'post_msg' => $post_msg,
        ]); 
    }

    /**
     * Displays a single Volume model.
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
     * Creates a new Volume model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['error']);
    	} 
    	
        $modelVolume = new Volume();
        $modelsIssue = [new Issue()];
        $post_msg = null;
        
        $modelVolume->created_on = date("Y-m-d H:i:s");

        if ($modelVolume->load(Yii::$app->request->post())) {
        	
        	$modelsIssue = Model::createMultiple(Issue::classname());
        	Model::loadMultiple($modelsIssue, Yii::$app->request->post());
        	foreach ($modelsIssue as $index => $modelIssue) {
        		//$modelIssue->sort_order = $index;
        		$modelIssue->cover_image = \yii\web\UploadedFile::getInstance($modelIssue, "[{$index}]cover_image");
        	}
        	
        	// ajax validation
        	if (Yii::$app->request->isAjax) {
        		Yii::$app->response->format = Response::FORMAT_JSON;
        		return ArrayHelper::merge(
        				ActiveForm::validateMultiple($modelsIssue),
        				ActiveForm::validate($modelVolume)
        		);
        	}
        	
        	// validate all models
        	$valid = $modelVolume->validate();
        	$valid = Model::validateMultiple($modelsIssue) && $valid;
        	
        	if ($valid) {
        		$transaction = \Yii::$app->db->beginTransaction();
        		try {
        			if ($flag = $modelVolume->save(false)) {
        				foreach ($modelsIssue as $modelIssue) {
        					$modelIssue->volume_id = $modelVolume->volume_id;
        	
        					if (($flag = $modelIssue->save(false)) === false) {
        						$transaction->rollBack();
        						break;
        					}
        				}
        			}
        			if ($flag) {
        				$transaction->commit();
        				return $this->redirect(['view', 'id' => $modelVolume->volume_id]);
        			}
        		} catch (Exception $e) {
        			$transaction->rollBack();
        		}
        	}       	

        } else {
            return $this->render('create', [
                'modelVolume' => $modelVolume,
            	'modelsIssue' => (empty($modelsIssue)) ? [new Issue()] : $modelsIssue,
            	'post_msg' => $post_msg,
            ]);
        }
    }

    /**
     * Updates an existing Volume model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['error']);
    	}
    	
        $modelVolume = $this->findModel($id);
        $modelVolume->updated_on = date("Y-m-d H:i:s");

        if ($modelVolume->load(Yii::$app->request->post()) && $modelVolume->save()) {
            return $this->redirect(['view', 'id' => $modelVolume->volume_id]);
        } else {
            return $this->render('update', [
                'modelVolume' => $modelVolume,
            ]);
        }
    }

    /**
     * Deletes an existing Volume model.
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

    /**
     * Finds the Volume model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Volume the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Volume::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
