<?php

namespace backend\controllers;

use Yii;
use common\models\Issue;
use common\models\Section;
use common\models\Article;
use common\models\DynamicForms;
use backend\models\SectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\base\Object;

/**
 * SectionController implements the CRUD actions for Section model.
 */
class SectionController extends Controller
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
     * Lists all Section models.
     * @return mixed
     */
    public function actionIndex()
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
    	$queryParams = Yii::$app->request->queryParams;
    	$queryParams['is_deleted'] = 0;
    	
        $searchModel = new SectionSearch();
        $dataProvider = $searchModel->search($queryParams);
        $post_msg = null;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'post_msg' => $post_msg,
        ]);
    }

    /**
     * Displays a single Section model.
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
     * Creates a new Section model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {    	 
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
        $modelSection = new Section();
        $modelsArticle = [new Article()];
        $post_msg = null;

        $modelSection->created_on = date("Y-m-d H:i:s");

        if ($modelSection->load(Yii::$app->request->post())) {
        	
        	$modelIssue = Issue::findOne($modelSection->issue_id);
      	
        	$modelSection->sort_in_issue = count($modelIssue->sections);
        	
        	// get Article data from POST
        	$modelsArticle = DynamicForms::createMultiple(Article::classname(), 'article_id');
        	DynamicForms::loadMultiple($modelsArticle, Yii::$app->request->post());
        	
        	foreach ($modelsArticle as $index => $modelArticle) {
        		$modelArticle->scenario = 'section_crud';
        		$modelArticle->sort_in_section = $index;
        		$modelArticle->created_on = date("Y-m-d H:i:s");
        	}
        	 
        	// ajax validation
        	if (Yii::$app->request->isAjax) {
        		Yii::$app->response->format = Response::FORMAT_JSON;
        		return ArrayHelper::merge(
        				ActiveForm::validateMultiple($modelsArticle),
        				ActiveForm::validate($modelSection)
        		);
        	}
        	
        	// validate all models
        	$valid = $modelSection->validate();
        	$valid = DynamicForms::validateMultiple($modelsArticle) && $valid;
        	if ($valid) {
        		$transaction = \Yii::$app->db->beginTransaction();
        		try {
        			if ($flag = $modelSection->save(false)) {
        				
        				foreach ($modelsArticle as $index => $modelArticle) {
        					$modelArticle->section_id = $modelSection->section_id;
        					$modelArticle->sort_in_section = $index;
        					$modelArticle->created_on = date("Y-m-d H:i:s");
        					 
        					if (($flag = $modelArticle->save(false)) === false) {
        						Yii::error("SectionController->actionCreate(1): ".json_encode($modelArticle->getErrors()), "custom_errors_sections");
        						$transaction->rollBack();
        						break;
        					}
        				}      				
        			} else {
        				Yii::error("SectionController->actionCreate(2): ".json_encode($modelSection->getErrors()), "custom_errors_sections");
        			}
        			
        			if ($flag) {
        				$transaction->commit();       				
      				
            			return $this->redirect(['view', 'id' => $modelSection->section_id]);
        			}
        		} catch (Exception $e) {
        			Yii::error("SectionController->actionCreate(3): ".json_encode($e), "custom_errors_sections");
        			$transaction->rollBack();
        		}
        	}        	
        }
        
        return $this->render('create', [
            'modelSection' => $modelSection,
          	'modelsArticle' => (empty($modelsArticle)) ? [new Article()] : $modelsArticle,
           	'post_msg' => $post_msg,
        ]);
    }

    /**
     * Updates an existing Section model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
        $modelSection = $this->findModel($id);
        $modelSection->updated_on = date("Y-m-d H:i:s");
        $update_issues_after_save = false; $issue_id_old = 0; $issue_id_new = 0;
                
        $modelsArticle = $modelSection->articles;
        $post_msg = null;

        if ($modelSection->load(Yii::$app->request->post())) {
        	
        	$oldIDs = ArrayHelper::map($modelsArticle, 'article_id', 'article_id');
        	         	
        	//if parent volume is changed, manage sorting of issues in both volumes
        	if(isset($modelSection->attributes) && isset($modelSection->attributes['issue_id']) &&
        	   isset($modelSection->oldAttributes) && isset($modelSection->oldAttributes['issue_id'])) {
        			$update_issues_after_save = true;
        			$issue_id_old = $modelSection->oldAttributes['issue_id'];
        			$issue_id_new = $modelSection->attributes['issue_id'];
        			if((string)$modelSection->attributes['issue_id'] != (string)$modelSection->oldAttributes['issue_id']){
        				$modelNewIssue = Issue::findOne(['issue_id' => $modelSection->attributes['issue_id']]);
        				$modelSection->sort_in_issue = count($modelNewIssue->sections);
        			}
        	}
        	
        	// get Article data from POST
        	$modelsArticle = DynamicForms::createMultiple(Article::classname(), 'article_id', $modelsArticle);
        	DynamicForms::loadMultiple($modelsArticle, Yii::$app->request->post());
        	$deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsArticle, 'article_id', 'article_id')));
        	
        	foreach ($modelsArticle as $index => $modelArticle) {
        		$modelArticle->scenario = 'section_crud';
        		$modelArticle->sort_in_section = $index;
        	}
        	 
        	// ajax validation
        	if (Yii::$app->request->isAjax) {
        		Yii::$app->response->format = Response::FORMAT_JSON;
        		return ArrayHelper::merge(
        				ActiveForm::validateMultiple($modelsArticle),
        				ActiveForm::validate($modelSection)
        		);
        	}        	
        	
        	// validate all models
        	$valid = $modelSection->validate();
        	$valid = DynamicForms::validateMultiple($modelsArticle) && $valid;
        	
        	if ($valid) {
        		$transaction = \Yii::$app->db->beginTransaction();
        		try {
        			if ($flag = $modelSection->save(false)) {
        				
        				if (!empty($deletedIDs)) {
        					$flag = Article::deleteByIDs($deletedIDs);
        				}
        				
        				if ($flag) {
        					foreach ($modelsArticle as $index => $modelArticle) {
        						//$new_cover_image = \yii\web\UploadedFile::getInstance($modelIssue, "[{$index}]cover_image");
        						 
        						$is_modified = false;
        						$modelArticle = Article::findOne($modelArticle->article_id);
        						if(!isset($modelArticle)){
        							$modelArticle = new Article();
        							$modelArticle->title = Yii::$app->request->post()['Article'][$index]['title'];
        							$modelArticle->sort_in_section = $index;
        							$modelArticle->created_on = date("Y-m-d H:i:s");
        						} else {
        							if($modelArticle->title != Yii::$app->request->post()['Article'][$index]['title']){
        								$is_modified = true;
        								$modelArticle->title = Yii::$app->request->post()['Article'][$index]['title'];
        							}
        							if($modelArticle->sort_in_section != $index){
        								$is_modified = true;
        								$modelArticle->sort_in_section = $index;
        							}
        						}
        						$modelArticle->section_id = $modelSection->section_id;
        							
        						if($is_modified){
        							$modelArticle->updated_on = date("Y-m-d H:i:s");
        						}        						 
        						
        						if (($flag = $modelArticle->save(false)) === false) {
        							Yii::error("SectionController->actionUpdate(1): ".json_encode($modelArticle->getErrors()), "custom_errors_sections");
        							$transaction->rollBack();
        							break;
        						}
        					}
        				}        				
        			} else {
        				Yii::error("SectionController->actionUpdate(2): ".json_encode($modelSection->getErrors()), "custom_errors_sections");
        			}
        			
        			if ($flag) {
        				$transaction->commit();
        				
        				if($update_issues_after_save == true && $issue_id_old > 0 && $issue_id_new > 0){
        					$modelOldIssue = Issue::findOne(['issue_id' => $issue_id_old]);
        					foreach ($modelOldIssue->sections as $indexItem => $modelSectionItem) {
        						$modelSectionItem->sort_in_issue = $indexItem;
        						if(!$modelSectionItem->save()){
        							Yii::error("SectionController->actionUpdate(3): ".json_encode($modelSectionItem->getErrors()), "custom_errors_sections");
        						}
        					}
        					 
        					$modelNewIssue = Issue::findOne(['issue_id' => $issue_id_new]);
        					foreach ($modelNewIssue->sections as $indexItem => $modelSectionItem) {
        						$modelSectionItem->sort_in_issue = $indexItem;
        						if(!$modelSectionItem->save()){
        							Yii::error("SectionController->actionUpdate(4): ".json_encode($modelSectionItem->getErrors()), "custom_errors_sections");
        						}
        					}
        				}
        				
        				return $this->redirect(['view', 'id' => $modelSection->section_id]);
        			}      		
        		} catch (Exception $e) {
        				Yii::error("SectionController->actionUpdate(5): ".json_encode($e), "custom_errors_sections");
        				$transaction->rollBack();
        		}
        	}
        }
        
        return $this->render('update', [
            'modelSection' => $modelSection,
            'modelsArticle' => (empty($modelsArticle)) ? [new Article()] : $modelsArticle,
            'post_msg' => $post_msg,
        ]);        
    }

    /**
     * Deletes an existing Section model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}    	
     
        $section_to_delete = $this->findModel($id);
        $parent_issue = $section_to_delete->issue;
        $section_to_delete->delete();
        
        foreach ($parent_issue->sections as $index => $modelSection) {
        	$modelSection->sort_in_issue = $index;
        	if(!$modelSection->save()){
        		Yii::error("SectionController->actionDelete(1): ".json_encode($modelSection->getErrors()), "custom_errors_sections");
        	}
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the Section model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Section the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Section::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
