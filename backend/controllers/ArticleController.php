<?php

namespace backend\controllers;

use Yii;
use common\models\Section;
use common\models\Article;
use backend\models\ArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
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
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['error']);
    	}
    	
    	$queryParams = Yii::$app->request->queryParams;
    	$queryParams['is_deleted'] = 0;
    	
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search($queryParams);
        $post_msg = null;
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'post_msg' => $post_msg,
        ]);
    }

    /**
     * Displays a single Article model.
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
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['error']);
    	}
    	
        $modelArticle = new Article();
        $post_msg = null;
        
        $modelArticle->created_on = date("Y-m-d H:i:s");

        if ($modelArticle->load(Yii::$app->request->post())) {
        	
        	$modelSection = Section::findOne($modelArticle->section_id);
        	 
        	$modelArticle->sort_in_section = count($modelSection->articles);
        	        	
        	// validate all models
        	$valid = $modelArticle->validate();
        	
        	if ($valid) {
        		$transaction = \Yii::$app->db->beginTransaction();
        		try {
        			if ($flag = $modelArticle->save(false)) {
        				
        			} else {
        				Yii::error("ArticleController->actionCreate(1): ".json_encode($modelArticle->getErrors()), "custom_errors_articles");
        			}
        			if ($flag) {
        				$transaction->commit();
        	
            			return $this->redirect(['view', 'id' => $modelArticle->article_id]);
        			}
        		} catch (Exception $e) {
        			$transaction->rollBack();
        		}
        	}        	
        }
        
        return $this->render('create', [
            'modelArticle' => $modelArticle,
            'post_msg' => $post_msg,
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['error']);
    	}
    	
        $modelArticle = $this->findModel($id);
        $modelArticle->updated_on = date("Y-m-d H:i:s");
        $update_sections_after_save = false; $section_id_old = 0; $section_id_new = 0;
        
        $post_msg = null;
        
        if ($modelArticle->load(Yii::$app->request->post())) {
        	
        	//if parent volume is changed, manage sorting of issues in both volumes
        	if(isset($modelArticle->attributes) && isset($modelArticle->attributes['section_id']) &&
        	   isset($modelArticle->oldAttributes) && isset($modelArticle->oldAttributes['section_id'])) {
        			$update_sections_after_save = true;
        			$section_id_old = $modelArticle->oldAttributes['section_id'];
        			$section_id_new = $modelArticle->attributes['section_id'];
        			if((string)$modelArticle->attributes['section_id'] != (string)$modelArticle->oldAttributes['section_id']){
        				$modelNewSection = Section::findOne(['section_id' => $modelArticle->attributes['section_id']]);
        				$modelArticle->sort_in_section = count($modelNewSection->articles);
        			}
        	}
        	
        	// validate all models
        	$valid = $modelArticle->validate();
        	
        	if ($valid) {
        		$transaction = \Yii::$app->db->beginTransaction();
        		try {
        			if ($flag = $modelArticle->save(false)) {
        	
        			} else {
        				Yii::error("ArticleController->actionUpdate(1): ".json_encode($modelArticle->getErrors()), "custom_errors_articles");
        			}
        			
        			if ($flag) {
        				$transaction->commit();
        	
        				if($update_sections_after_save == true && $section_id_old > 0 && $section_id_new > 0){
        					$modelOldSection = Section::findOne(['section_id' => $section_id_old]);
        					foreach ($modelOldSection->articles as $indexItem => $modelArticleItem) {
        						$modelArticleItem->sort_in_section = $indexItem;
        						if(!$modelArticleItem->save()){
        							Yii::error("ArticleController->actionUpdate(2): ".json_encode($modelArticleItem->getErrors()), "custom_errors_articles");
        						}
        					}
        	
        					$modelNewSection = Section::findOne(['section_id' => $section_id_new]);
        					foreach ($modelNewSection->articles as $indexItem => $modelArticleItem) {
        						$modelArticleItem->sort_in_section = $indexItem;
        						if(!$modelArticleItem->save()){
        							Yii::error("ArticleController->actionUpdate(3): ".json_encode($modelArticleItem->getErrors()), "custom_errors_articles");
        						}
        					}
        				}
        	
           				return $this->redirect(['view', 'id' => $modelArticle->article_id]);
              		}
        		} catch (Exception $e) {
        			$transaction->rollBack();
        		}
        	}        			
        }
        
        return $this->render('update', [
            'modelArticle' => $modelArticle,
            'post_msg' => $post_msg,
        ]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['error']);
    	}
    	
        $article_to_delete = $this->findModel($id);
        $parent_section = $article_to_delete->section;
        $article_to_delete->delete();
        
        foreach ($parent_section->articles as $index => $modelArticle) {
        	$modelArticle->sort_in_section = $index;
        	$modelArticle->save();
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    

    public function actionPdfview($id, $partial = null)
    {
    	$modelArticle = $this->findModel($id);
    	
    	// get your HTML raw content without any layouts or scripts
    	$content = $modelArticle->abstract."<br>".$modelArticle->content;
    	if($partial != null && $partial == "abstract"){
    		$content = $modelArticle->abstract;
    	} else if($partial != null && $partial == "content"){
    		$content = $modelArticle->content;
    	}    	
    	 
    	$pdf = Yii::$app->pdf;
    	//$pdf->content = $content."<br>".$content."<br>".$content."<br>".$content."<br>".$content."<br>".$content."<br>".$content."<br>".$content."<br>";
    	$pdf->content = $content;
    	// set mPDF properties on the fly
    	$pdf->options = [
    			'title' => $modelArticle->title,
    			//'subject' => 'PDF Document Subject',
    			'keywords' => 'krajee, grid, export, yii2-grid, pdf'
    	];
    	// call mPDF methods on the fly
    	$header = "||".$modelArticle->section->title;    
    	$pageno = "|{PAGENO}|";
    	$pdf->methods = [
    			'SetHeader'=>[$header],
    			'SetFooter'=>[$pageno],
    	];
    	return $pdf->render();
    	 
    	/*	    $mpdf = $pdf->api; // fetches mpdf api
    	 $mpdf->SetHeader('Krajee mpdf Header|f|p'); // call methods or set any properties
    	 $mpdf->WriteHtml($content); // call mpdf write html
    	 $mpdf->SetKeywords('Zoki, Smoki');
    	 echo $mpdf->Output('filename.pdf', $pdf->destination); // call the mpdf api output as needed
    	*/
    }
}
