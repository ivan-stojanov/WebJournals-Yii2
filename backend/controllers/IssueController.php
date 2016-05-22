<?php

namespace backend\controllers;

use Yii;
use common\models\Volume;
use common\models\Issue;
use common\models\Section;
use common\models\Image;
use common\models\DynamicForms;
use backend\models\IssueSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

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
    		return $this->redirect(['error']);
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
    		return $this->redirect(['error']);
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
    		return $this->redirect(['error']);
    	}
    	
        $modelIssue = new Issue();
        $modelsSection = [new Section()];
        $post_msg = null;
        
        $modelIssue->created_on = date("Y-m-d H:i:s");

        if ($modelIssue->load(Yii::$app->request->post())) {
     	
        	$modelVolume = Volume::findOne($modelIssue->volume_id);
        	
        	$modelIssue->sort_in_volume = count($modelVolume->issues) + 1;
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
        			}  else {
        				$modelIssue->cover_image = null;
        			}
        		}  else {
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
        						$transaction->rollBack();
        						break;
        					}
        				}
        			}
        			if ($flag) {
        				$transaction->commit();
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
    		return $this->redirect(['error']);
    	}
    	
        $modelIssue = $this->findModel($id);
        $modelIssue->updated_on = date("Y-m-d H:i:s");
        
        $initial_cover_image = null;
        if(isset($modelIssue->cover_image)){
        	$initial_cover_image = $modelIssue->cover_image;
        }
       
        $initial_volume_id = $modelIssue->volume_id;
       
        $modelsSection = $modelIssue->sections;
        $post_msg = null;
        
        if ($modelIssue->load(Yii::$app->request->post())) {
        	$oldIDs = ArrayHelper::map($modelsSection, 'section_id', 'section_id');
      	
        	if(isset($modelIssue->published_on)){
        		$year_int = intval(date("Y", strtotime($modelIssue->published_on)));
        		if($year_int > 2010){
        			$modelIssue->published_on = date("Y-m-d H:i:s", strtotime($modelIssue->published_on));
        		}
        	}
/*var_dump($modelIssue->is_special_issue);
return $this->render('update', [
		'modelIssue' => $modelIssue,
		'modelsSection' => (empty($modelsSection)) ? [new Section()] : $modelsSection,
		'post_msg' => $post_msg,
]);*/
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
	        			}  else {
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
        	$valid = $modelIssue->validate();//var_dump("302"); var_dump($modelIssue->getErrors());
        	$valid = DynamicForms::validateMultiple($modelsSection) && $valid;//var_dump("303"); var_dump($valid);
      	 
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
        						$modelSection->issue_id = $modelIssue->issue_id;
        						if($modelSection->title != Yii::$app->request->post()['Section'][$index]['title']){
        							$is_modified = true;
        							$modelSection->title = Yii::$app->request->post()['Section'][$index]['title'];
        						}
        						if($modelSection->sort_in_issue != $index){
        							$is_modified = true;
        							$modelSection->sort_in_issue = $index;
        						}
        	
        						if($is_modified){
        							$modelSection->updated_on = date("Y-m-d H:i:s");
        						}
        						 
        						if (($flag = $modelSection->save(false)) === false) {
        							$transaction->rollBack();
        							break;
        						}
        					}
        				}
        			}

        			if ($flag) {
        				$transaction->commit();
        				return $this->redirect(['view', 'id' => $modelIssue->issue_id]);
        			}
        			 
        		} catch (Exception $e) {
        			$transaction->rollBack();
        		}
        	}
        }        

        return $this->render('update', [
        		'modelIssue' => $modelIssue,
        		'modelsSection' => (empty($modelsSection)) ? [new Section()] : $modelsSection,
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
    		return $this->redirect(['error']);
    	}
    	
        $this->findModel($id)->delete();

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
