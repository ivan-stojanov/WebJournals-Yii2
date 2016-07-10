<?php

namespace backend\controllers;

use Yii;
use common\models\Issue;
use common\models\Section;
use backend\models\SectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
    		return $this->redirect(['error']);
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
    		return $this->redirect(['error']);
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
    		return $this->redirect(['error']);
    	}
    	
        $modelSection = new Section();
        //$modelsArticle = [new Article()];
        $post_msg = null;
        
        $modelSection->created_on = date("Y-m-d H:i:s");

        if ($modelSection->load(Yii::$app->request->post())) {
        	
        	$modelIssue = Issue::findOne($modelSection->issue_id);
        	
        	$modelSection->sort_in_issue = count($modelIssue->sections);
        	
        	// validate all models
        	$valid = $modelSection->validate();
        	
        	if ($valid) {
        		$transaction = \Yii::$app->db->beginTransaction();
        		try {
        			if ($flag = $modelSection->save(false)) {
        			}
        			if ($flag) {
        				$transaction->commit();
        				
            			return $this->redirect(['view', 'id' => $modelSection->section_id]);
        			}
        		} catch (Exception $e) {
        			$transaction->rollBack();
        		}
        	}        	
        }
        
        return $this->render('create', [
            'modelSection' => $modelSection,
          	//'modelsArticle' => (empty($modelsArticle)) ? [new Article()] : $modelsArticle,
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
    		return $this->redirect(['error']);
    	}
    	
        $modelSection = $this->findModel($id);
        $modelSection->updated_on = date("Y-m-d H:i:s");
        $update_issues_after_save = false; $issue_id_old = 0; $issue_id_new = 0;
                
        //$modelsArticle = $modelSection->articles;
        $post_msg = null;

        if ($modelSection->load(Yii::$app->request->post())) {
        	
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
        	
        	// validate all models
        	$valid = $modelSection->validate();
        	
        	if ($valid) {
        		$transaction = \Yii::$app->db->beginTransaction();
        		try {
        			if ($flag = $modelSection->save(false)) {
        				
        			}
        			if ($flag) {
        				$transaction->commit();
        				
        				if($update_issues_after_save == true && $issue_id_old > 0 && $issue_id_new > 0){
        					$modelOldIssue = Issue::findOne(['issue_id' => $issue_id_old]);
        					foreach ($modelOldIssue->sections as $indexItem => $modelSectionItem) {
        						$modelSectionItem->sort_in_issue = $indexItem;
        						$modelSectionItem->save();
        					}
        					 
        					$modelNewIssue = Issue::findOne(['issue_id' => $issue_id_new]);
        					foreach ($modelNewIssue->sections as $indexItem => $modelSectionItem) {
        						$modelSectionItem->sort_in_issue = $indexItem;
        						$modelSectionItem->save();
        					}
        				}
        				
        				return $this->redirect(['view', 'id' => $modelSection->section_id]);
        			}      		
        		} catch (Exception $e) {
        				$transaction->rollBack();
        		}
        	}
        }
        
        return $this->render('update', [
            'modelSection' => $modelSection,
            //'modelsArticle' => (empty($modelsArticle)) ? [new Article()] : $modelsArticle,
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
    		return $this->redirect(['error']);
    	}    	
     
        $section_to_delete = $this->findModel($id);
        $parent_issue = $section_to_delete->issue;
        $section_to_delete->delete();
        
        foreach ($parent_issue->sections as $index => $modelSection) {
        	$modelSection->sort_in_issue = $index;
        	$modelSection->save();
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
