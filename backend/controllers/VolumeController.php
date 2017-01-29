<?php

namespace backend\controllers;

use Yii;
use common\models\Volume;
use common\models\Issue;
use common\models\Section;
use common\models\Image;
use common\models\DynamicForms;
use backend\models\VolumeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\base\Object;
use yii\helpers\ArrayHelper;

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
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	if (Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
    	$queryParams = Yii::$app->request->queryParams;
    	$queryParams['VolumeSearch']['is_deleted'] = 0;
    	
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
     * Creates a new Volume model.
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
    	
        $modelVolume = new Volume();
        $modelsIssue = [new Issue()];
        $post_msg = null;
        
        $modelVolume->created_on = date("Y-m-d H:i:s");

        if ($modelVolume->load(Yii::$app->request->post())) {        	

        	// get Issue data from POST
        	$modelsIssue = DynamicForms::createMultiple(Issue::classname(), 'issue_id');
        	DynamicForms::loadMultiple($modelsIssue, Yii::$app->request->post());        	

        	foreach ($modelsIssue as $index => $modelIssue) {
        		$modelIssue->scenario = 'volume_crud';        		
        		$modelIssue->sort_in_volume = $index;
        		$modelIssue->created_on = date("Y-m-d H:i:s");
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
        	$valid = DynamicForms::validateMultiple($modelsIssue) && $valid;

        	if ($valid) {
        		$transaction = \Yii::$app->db->beginTransaction();
        		try {
        			if ($flag = $modelVolume->save(false)) {
        				foreach ($modelsIssue as $index => $modelIssue) {
        					$modelIssue->volume_id = $modelVolume->volume_id;  
        					$modelIssue->sort_in_volume = $index;
        					$modelIssue->created_on = date("Y-m-d H:i:s");
        					
        					$modelIssue->cover_image = \yii\web\UploadedFile::getInstance($modelIssue, "[{$index}]cover_image");

        					if ($modelIssue->uploadIssueImage($modelVolume->volume_id)) {
        						// file is uploaded successfully

        						if(isset($modelIssue->cover_image) && isset($modelIssue->cover_image->baseName) && isset($modelIssue->cover_image->extension)){
        						
	        						$newImage = new Image();
	        						$newImage->path = $modelIssue->cover_image->baseName . '.' . $modelIssue->cover_image->extension;
	        						$newImage->type = 'file';
	        						$newImage->name = $modelIssue->cover_image->baseName;
	        						$newImage->size = 100;
	        						if($newImage->save()){
	        							$modelIssue->cover_image = $newImage->image_id;
	        						}  else {
	        							Yii::error("VolumeController->actionCreate(1): ".json_encode($newImage->getErrors()), "custom_errors_volumes");
		        					}
        						}  else {
	        						$modelIssue->cover_image = null;
	        					}
        					}        	 
        					
        					if (($flag = $modelIssue->save(false)) === false) {
        						Yii::error("VolumeController->actionCreate(2): ".json_encode($modelIssue->getErrors()), "custom_errors_volumes");
        						$transaction->rollBack();
        						break;
        					} else {
        						$modelSection = new Section();
        						$modelSection->title = 'Original/research articles';
        						$modelSection->sort_in_issue = 0;
        						$modelSection->issue_id = $modelIssue->issue_id;
        						$modelSection->created_on = date("Y-m-d H:i:s");
        						 
        						if (($flag = $modelSection->save(false)) === false) {
        							Yii::error("VolumeController->actionCreate(3): ".json_encode($modelSection->getErrors()), "custom_errors_volumes");
        							$transaction->rollBack();
        							break;
        						}
        					}
        				}
        			} else {
        				Yii::error("VolumeController->actionCreate(4): ".json_encode($modelVolume->getErrors()), "custom_errors_volumes");
        			}
        			
        			if ($flag) {
        				$transaction->commit();
        				return $this->redirect(['view', 'id' => $modelVolume->volume_id]);
        			}
        		} catch (Exception $e) {
        			$transaction->rollBack();
        		}
        	}      	

        } 
        return $this->render('create', [
        		'modelVolume' => $modelVolume,
        		'modelsIssue' => (empty($modelsIssue)) ? [new Issue()] : $modelsIssue,
        		'post_msg' => $post_msg,
        ]);
    }

    /**
     * Updates an existing Volume model.
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
    	
        $modelVolume = $this->findModel($id);
        $modelVolume->updated_on = date("Y-m-d H:i:s");
        
        $modelsIssue = $modelVolume->issues;
        $post_msg = null;

        if ($modelVolume->load(Yii::$app->request->post())) {
        	$oldIDs = ArrayHelper::map($modelsIssue, 'issue_id', 'issue_id');
        	
        	// get Issue data from POST
        	$modelsIssue = DynamicForms::createMultiple(Issue::classname(), 'issue_id', $modelsIssue);
        	DynamicForms::loadMultiple($modelsIssue, Yii::$app->request->post());
        	$deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsIssue, 'issue_id', 'issue_id')));
        	
        	foreach ($modelsIssue as $index => $modelIssue) {
        		$modelIssue->scenario = 'volume_crud';
        		$modelIssue->sort_in_volume = $index;
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
        	$valid = DynamicForms::validateMultiple($modelsIssue) && $valid;
      	
        	if ($valid) {
        		$transaction = \Yii::$app->db->beginTransaction();
        		try {
        			if ($flag = $modelVolume->save(false)) {
        	
        				if (!empty($deletedIDs)) {
        					$flag = Issue::deleteByIDs($deletedIDs);
        				}
       	
        				if ($flag) {
        					foreach ($modelsIssue as $index => $modelIssue) {       						
								$new_cover_image = \yii\web\UploadedFile::getInstance($modelIssue, "[{$index}]cover_image");
								
								$is_modified = false;
								$can_modify = true;
								$modelIssue = Issue::findOne($modelIssue->issue_id);
								if(!isset($modelIssue)){
									$modelIssue = new Issue();
									$modelIssue->title = Yii::$app->request->post()['Issue'][$index]['title'];
									$modelIssue->sort_in_volume = $index;
									$modelIssue->created_on = date("Y-m-d H:i:s");
									$can_modify = false;
								} else {									
									if($modelIssue->title != Yii::$app->request->post()['Issue'][$index]['title']){
										$is_modified = true;
										$modelIssue->title = Yii::$app->request->post()['Issue'][$index]['title'];
									}
									if($modelIssue->sort_in_volume != $index){
										$is_modified = true;
										$modelIssue->sort_in_volume = $index;
									}
								}
								$modelIssue->volume_id = $modelVolume->volume_id;
							
        						if(isset($new_cover_image) && (count($new_cover_image) > 0)) {
	        						$modelIssue->cover_image = $new_cover_image;

	        						if ($modelIssue->uploadIssueImage($modelVolume->volume_id)) {
	        							// file is uploaded successfully

	        							if(isset($modelIssue->cover_image) && isset($modelIssue->cover_image->baseName) && isset($modelIssue->cover_image->extension)){
      								
	        								$newImage = new Image();
	        								$newImage->path = $modelIssue->cover_image->baseName . '.' . $modelIssue->cover_image->extension;
	        								$newImage->type = 'file';
	        								$newImage->name = $modelIssue->cover_image->baseName;
	        								$newImage->size = 100;
	        								if($newImage->save()){
	        									$modelIssue->cover_image = $newImage->image_id;
	        								} else {
	        									Yii::error("VolumeController->actionUpdate(1): ".json_encode($newImage->getErrors()), "custom_errors_volumes");
	        									$modelIssue->cover_image = null;
	        								}
	        								
	        								if($can_modify == true){
	        									$is_modified = true;
	        								}	        								
	        							}
	        						
	        						}
        						}
        						
        						if($is_modified){
        							$modelIssue->updated_on = date("Y-m-d H:i:s");
        						}
        						 
        						if (($flag = $modelIssue->save(false)) === false) {
        							Yii::error("VolumeController->actionUpdate(2): ".json_encode($modelIssue->getErrors()), "custom_errors_volumes");
        							break;
        						} else {
        							$modelSection = new Section();
        							$modelSection->title = 'Original/research articles';
        							$modelSection->sort_in_issue = 0;
        							$modelSection->issue_id = $modelIssue->issue_id;
        							$modelSection->created_on = date("Y-m-d H:i:s");
        							
        							if (($flag = $modelSection->save(false)) === false) {
        								Yii::error("VolumeController->actionUpdate(3): ".json_encode($modelSection->getErrors()), "custom_errors_volumes");
        								$transaction->rollBack();
        								break;
        							}
        						}
        					}
        				}
        			} else {
        				Yii::error("VolumeController->actionUpdate(4): ".json_encode($modelVolume->getErrors()), "custom_errors_volumes");
        			}
        	
        			if ($flag) {
        				$transaction->commit();
        				return $this->redirect(['view', 'id' => $modelVolume->volume_id]);
        			}
        	
        		} catch (Exception $e) {
        			$transaction->rollBack();
        		}
        	}
        }
        
        return $this->render('update', [
        		'modelVolume' => $modelVolume,
        		'modelsIssue' => (empty($modelsIssue)) ? [new Issue()] : $modelsIssue,
        		'post_msg' => $post_msg,
        ]);
    }

    /**
     * Deletes an existing Volume model.
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
