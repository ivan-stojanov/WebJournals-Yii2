<?php

namespace backend\controllers;

use Yii;
use common\models\Volume;
use common\models\Issue;
use common\models\Section;
use common\models\Image;
use common\models\User;
use common\models\DynamicForms;
use backend\models\IssueSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\base\Object;

/**
 * IssueController implements the CRUD actions for Issue model.
 */
class IssueController extends Controller
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
     * Lists all Issue models.
     * @return mixed
     */
    public function actionIndex()
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
    	$queryParams = Yii::$app->request->queryParams;
    	$queryParams['is_deleted'] = 0;
    	
        $searchModel = new IssueSearch();
        $dataProvider = $searchModel->search($queryParams);
        $post_msg = null;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'post_msg' => $post_msg,
        ]);
    }

    /**
     * Displays a single Issue model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Issue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
        $modelIssue = new Issue();
        $modelsSection = [new Section()];
        $modelUser = new User();
        $post_msg = null;
        
        $modelIssue->created_on = date("Y-m-d H:i:s");

        if ($modelIssue->load(Yii::$app->request->post())) {
        	if(Yii::$app->request->post()['Issue'] != null && Yii::$app->request->post()['Issue']['post_editors'] != null)
        	{
        		$modelIssue->post_editors = Yii::$app->request->post()['Issue']['post_editors'];
        	}
        	
        	$modelVolume = Volume::findOne($modelIssue->volume_id);
        	
        	$modelIssue->sort_in_volume = count($modelVolume->issues);
        	
        	if(isset($modelIssue->published_on)){
        		$year_int = intval(date("Y", strtotime($modelIssue->published_on)));
        		if($year_int > 2010){
        			$modelIssue->published_on = date("Y-m-d H:i:s", strtotime($modelIssue->published_on));
        		}
        	}
        	
        	if(isset($modelIssue->is_special_issue)){
        		if(($modelIssue->is_special_issue) || ($modelIssue->is_special_issue == "on")){
        			$modelIssue->is_special_issue = 1;
        		}
        	} else {
        		$modelIssue->is_special_issue = 0;
        	}
        	
        	$modelIssue->cover_image = \yii\web\UploadedFile::getInstance($modelIssue, "cover_image");        	
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
        				Yii::error("IssueController->actionCreate(1): ".json_encode($newImage->getErrors()), "custom_errors_issues");
        				$modelIssue->cover_image = null;
        			}
        		} else {
        			$modelIssue->cover_image = null;
        		}
        	}
        	 
        	// get Section data from POST
        	$modelsSection = DynamicForms::createMultiple(Section::classname(), 'section_id');
        	DynamicForms::loadMultiple($modelsSection, Yii::$app->request->post());
        	
        	foreach ($modelsSection as $index => $modelSection) {
        		$modelSection->scenario = 'issue_crud';
        		$modelSection->sort_in_issue = $index;
        		$modelSection->created_on = date("Y-m-d H:i:s");
        	}
        	
        	// ajax validation
        	if (Yii::$app->request->isAjax) {
        		Yii::$app->response->format = Response::FORMAT_JSON;
        		return ArrayHelper::merge(
        				ActiveForm::validateMultiple($modelsSection),
        				ActiveForm::validate($modelIssue)
        		);
        	}
        	
        	// validate all models
        	$valid = $modelIssue->validate();
        	$valid = DynamicForms::validateMultiple($modelsSection) && $valid;

        	if ($valid) {
        		$transaction = \Yii::$app->db->beginTransaction();
        		try {
        			if ($flag = $modelIssue->save(false)) {
        				 
        				 foreach ($modelsSection as $index => $modelSection) {
        					$modelSection->issue_id = $modelIssue->issue_id;
        					$modelSection->sort_in_issue = $index;
        					$modelSection->created_on = date("Y-m-d H:i:s");
        	
        					if (($flag = $modelSection->save(false)) === false) {
        						Yii::error("IssueController->actionCreate(2): ".json_encode($modelSection->getErrors()), "custom_errors_issues");
        						$transaction->rollBack();
        						break;
        					}
        				}
        			} else {
        				Yii::error("IssueController->actionCreate(3): ".json_encode($modelIssue->getErrors()), "custom_errors_issues");
          			}
        			
        			if ($flag) {
        				$transaction->commit();
        				
        				if(isset($modelIssue) && isset($modelIssue->is_current) && ($modelIssue->is_current)){
        					$previous_current_issues = Issue::updateAll(
        							['is_current' => 0], 
        							'is_current = 1 AND issue_id != '.$modelIssue->issue_id
        					);
        				}
        				
        				return $this->redirect(['view', 'id' => $modelIssue->issue_id]);
        			}
        		} catch (Exception $e) {
        			$transaction->rollBack();
        		}
        	}
        	
        }
        
        return $this->render('create', [
            'modelIssue' => $modelIssue,
        	'modelsSection' => (empty($modelsSection)) ? [new Section()] : $modelsSection,
        	'modelUser' => $modelUser,
        	'post_msg' => $post_msg,
        ]);
    }

    /**
     * Updates an existing Issue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
        $modelIssue = $this->findModel($id);
        $modelIssue->updated_on = date("Y-m-d H:i:s");
        $update_volumes_after_save = false; $volume_id_old = 0; $volume_id_new = 0;
        
        $modelUser = new User();
        $arrayIssueSpecialEditor = [];
        $arrayIssueSpecialEditor_int = [];
        $issueSpecialEditor = $modelIssue->getSpecialEditor();
        if($issueSpecialEditor != null && $issueSpecialEditor != null){ 
        	$arrayIssueSpecialEditor[] = (string)$issueSpecialEditor->id;
        	$arrayIssueSpecialEditor_int[] = $issueSpecialEditor->id;
        }
        
        $initial_cover_image = null;
        if(isset($modelIssue->cover_image)){
        	$initial_cover_image = $modelIssue->cover_image;
        }
       
        $initial_volume_id = $modelIssue->volume_id;
       
        $modelsSection = $modelIssue->sections;
        $post_msg = null;
        
        if ($modelIssue->load(Yii::$app->request->post())) {   
        	if(Yii::$app->request->post()['Issue'] != null && Yii::$app->request->post()['Issue']['post_editors'] != null)
        	{
          		$current_SpecialEditor_array = Yii::$app->request->post()['Issue']['post_editors'];
          		$specialEditor_is_changed = !($arrayIssueSpecialEditor == $current_SpecialEditor_array);
          		if($specialEditor_is_changed && count($current_SpecialEditor_array)>0)
          		{
          			$arrayIssueSpecialEditor_int = $current_SpecialEditor_array;
          			$modelIssue->special_editor = $arrayIssueSpecialEditor_int[0];         			         			
          		}
        	}
        	else 
        	{
        		$arrayIssueSpecialEditor_int = null;
        		$modelIssue->special_editor = null;
        	}
        	
        	$oldIDs = ArrayHelper::map($modelsSection, 'section_id', 'section_id');
      	
        	if(isset($modelIssue->published_on)){
        		$year_int = intval(date("Y", strtotime($modelIssue->published_on)));
        		if($year_int > 2010){
        			$modelIssue->published_on = date("Y-m-d H:i:s", strtotime($modelIssue->published_on));
        		}
        	}

            if(isset($modelIssue->is_special_issue)){
        		if(($modelIssue->is_special_issue) || ($modelIssue->is_special_issue == "on")){
        			$modelIssue->is_special_issue = 1;
        		} else {
        			$modelIssue->is_special_issue = 0;
        		}
        	} else {
        		$modelIssue->is_special_issue = 0;
        	}
     	
        	$new_cover_image = \yii\web\UploadedFile::getInstance($modelIssue, "cover_image");

        	if(isset($new_cover_image) && (count($new_cover_image) > 0)) { 
        		$modelIssue->cover_image = $new_cover_image;
       		
	        	if ($modelIssue->uploadIssueImage($modelIssue->volume_id)) {
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
	        				Yii::error("IssueController->actionUpdate(1): ".json_encode($newImage->getErrors()), "custom_errors_issues");
 	        				$modelIssue->cover_image = null;
	        			}
	        		}
	        	}   
        	} else {
        		$modelIssue->cover_image = $initial_cover_image;
        		
        		if((isset($modelIssue->cover_image)) && ($modelIssue->volume_id != $initial_volume_id)){
        			$image_path = $modelIssue->coverimage->path;
        			$issueImagesOldPathDIR = Yii::getAlias('@common') . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'issues' . DIRECTORY_SEPARATOR . $initial_volume_id . DIRECTORY_SEPARATOR;
        			$issueImagesNewPathDIR = Yii::getAlias('@common') . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'issues' . DIRECTORY_SEPARATOR . $modelIssue->volume_id . DIRECTORY_SEPARATOR;
        			
        			if (!file_exists($issueImagesNewPathDIR)) {
        				mkdir($issueImagesNewPathDIR, 0777, true);
        			}
        			
        			copy($issueImagesOldPathDIR.$image_path, $issueImagesNewPathDIR.$image_path);
        		}        		
        	}
        	
			//if parent volume is changed, manage sorting of issues in both volumes
			if(isset($modelIssue->attributes) && isset($modelIssue->attributes['volume_id']) &&
			   isset($modelIssue->oldAttributes) && isset($modelIssue->oldAttributes['volume_id'])) {			   	
			   		$update_volumes_after_save = true;
			   		$volume_id_old = $modelIssue->oldAttributes['volume_id']; 
			   		$volume_id_new = $modelIssue->attributes['volume_id'];
				   	if((string)$modelIssue->attributes['volume_id'] != (string)$modelIssue->oldAttributes['volume_id']){
				   		$modelNewVolume = Volume::findOne(['volume_id' => $modelIssue->attributes['volume_id']]);
				   		$modelIssue->sort_in_volume = count($modelNewVolume->issues);
				   	}			   	
			}
      	 
        	// get Section data from POST
        	$modelsSection = DynamicForms::createMultiple(Section::classname(), 'section_id', $modelsSection);
        	DynamicForms::loadMultiple($modelsSection, Yii::$app->request->post());
        	$deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsSection, 'section_id', 'section_id')));
      	 
        	foreach ($modelsSection as $index => $modelSection) {
        		$modelSection->scenario = 'issue_crud';
        		$modelSection->sort_in_issue = $index;
        	}
        	
        	// ajax validation
        	if (Yii::$app->request->isAjax) {
        		Yii::$app->response->format = Response::FORMAT_JSON;
        		return ArrayHelper::merge(
        				ActiveForm::validateMultiple($modelsSection),
        				ActiveForm::validate($modelIssue)
        		);
        	}
      	
        	// validate all models
        	$valid = $modelIssue->validate();
        	$valid = DynamicForms::validateMultiple($modelsSection) && $valid;
      	 
        	if ($valid) {
        		$transaction = \Yii::$app->db->beginTransaction();
        		try {
        			if ($flag = $modelIssue->save(false)) {
        				 
        				if (!empty($deletedIDs)) {
        					$flag = Section::deleteByIDs($deletedIDs);
        				}
        	
        				if ($flag) {
        					foreach ($modelsSection as $index => $modelSection) {        						 
        						$new_cover_image = \yii\web\UploadedFile::getInstance($modelIssue, "[{$index}]cover_image");
        	
        						$is_modified = false;
        						$modelSection = Section::findOne($modelSection->section_id);
        						if(!isset($modelSection)){
        							$modelSection = new Section();
        							$modelSection->title = Yii::$app->request->post()['Section'][$index]['title'];
        							$modelSection->sort_in_issue = $index;
        							$modelSection->created_on = date("Y-m-d H:i:s");
        						} else {
        							if($modelSection->title != Yii::$app->request->post()['Section'][$index]['title']){
        								$is_modified = true;
        								$modelSection->title = Yii::$app->request->post()['Section'][$index]['title'];
        							}
        							if($modelSection->sort_in_issue != $index){
        								$is_modified = true;
        								$modelSection->sort_in_issue = $index;
        							}        							
        						}
        						$modelSection->issue_id = $modelIssue->issue_id;
        						        	
        						if($is_modified){
        							$modelSection->updated_on = date("Y-m-d H:i:s");
        						}
        						 
        						if (($flag = $modelSection->save(false)) === false) {
        							Yii::error("IssueController->actionUpdate(2): ".json_encode($modelSection->getErrors()), "custom_errors_issues");
        							$transaction->rollBack();
        							break;
        						}
        					}
        				}
        			} else {
        				Yii::error("IssueController->actionUpdate(3): ".json_encode($modelIssue->getErrors()), "custom_errors_issues");
        			}

        			if ($flag) {
        				$transaction->commit();
        				
        				if(isset($modelIssue) && isset($modelIssue->is_current) && ($modelIssue->is_current)){
        					$previous_current_issues = Issue::updateAll(
        							['is_current' => 0],
        							'is_current = 1 AND issue_id != '.$modelIssue->issue_id
        					);
        				}        				
        				
        				if($update_volumes_after_save == true && $volume_id_old > 0 && $volume_id_new > 0){
        					$modelOldVolume = Volume::findOne(['volume_id' => $volume_id_old]);        					
        					foreach ($modelOldVolume->issues as $indexItem => $modelIssueItem) {
        						$modelIssueItem->sort_in_volume = $indexItem;
        						if(!$modelIssueItem->save()){
        							Yii::error("IssueController->actionUpdate(4): ".json_encode($modelIssueItem->getErrors()), "custom_errors_issues");
        						}
        					}
        					
        					$modelNewVolume = Volume::findOne(['volume_id' => $volume_id_new]);
        					foreach ($modelNewVolume->issues as $indexItem => $modelIssueItem) {
        						$modelIssueItem->sort_in_volume = $indexItem;
        					    if(!$modelIssueItem->save()){
        							Yii::error("IssueController->actionUpdate(5): ".json_encode($modelIssueItem->getErrors()), "custom_errors_issues");
        						}
        					}
        				}
        				
        				return $this->redirect(['view', 'id' => $modelIssue->issue_id]);
        			}        			 
        		} catch (Exception $e) {
        			$transaction->rollBack();
        		}
        	}
        }
        
        $modelIssue->post_editors = $arrayIssueSpecialEditor_int;
        //var_dump($arrayIssueSpecialEditor);

        return $this->render('update', [
        	'modelIssue' => $modelIssue,
        	'modelsSection' => (empty($modelsSection)) ? [new Section()] : $modelsSection,
        	'modelUser' => $modelUser,
        	'post_msg' => $post_msg,
        ]);
    }

    /**
     * Deletes an existing Issue model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
    	$issue_to_delete = $this->findModel($id);
    	$parent_volume = $issue_to_delete->volume;
        $issue_to_delete->delete();
        
    	foreach ($parent_volume->issues as $index => $modelIssue) {
        	$modelIssue->sort_in_volume = $index;
        	if(!$modelIssue->save()){
        		Yii::error("IssueController->actionDelete(1): ".json_encode($modelIssue->getErrors()), "custom_errors_sections");
        	}
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Issue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Issue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Issue::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
