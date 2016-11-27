<?php

namespace backend\controllers;

use Yii;
use common\models\Section;
use common\models\Article;
use common\models\ArticleAuthor;
use common\models\ArticleKeyword;
use common\models\ArticleReviewer;
use common\models\Keyword;
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
    	
    	$articleAuthorModel = new ArticleAuthor();
    	$article_authors_string = $articleAuthorModel->getAuthorsForArticleString($id);
    	
    	$articleKeywordModel = new ArticleKeyword();
    	$article_keywords_string = $articleKeywordModel->getKeywordsForArticleString($id);
    	 
    	$articleReviewerModel = new ArticleReviewer();
    	$article_reviewers_string = $articleReviewerModel->getReviewersForArticleString($id);
    	
        return $this->render('view', [
            'model' => $this->findModel($id),
        	'article_authors_string' => $article_authors_string,
        	'article_keywords_string' => $article_keywords_string,
        	'article_reviewers_string' => $article_reviewers_string,
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
        $modelKeyword = new Keyword();
        $arrayArticleKeyword = [];        
        
        $post_msg = null;        
        $modelArticle->created_on = date("Y-m-d H:i:s");
        $addKeywords = false;

        if ($modelArticle->load(Yii::$app->request->post())) {
        	if(Yii::$app->request->post()['Article'] != null &&
        	   Yii::$app->request->post()['Article']['post_keywords'] != null)
        	{
        		$modelArticle->post_keywords = Yii::$app->request->post()['Article']['post_keywords'];
        		$addKeywords = true;
        	}
        	
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
        				
        				ArticleKeyword::deleteAll([
        						'article_id' => intval($modelArticle->article_id)
        				]);
        				if($addKeywords){
        					foreach ($modelArticle->post_keywords as $indexOrder => $keywordId) {
        						$articleKeywordItem = new ArticleKeyword();
        						$articleKeywordItem->article_id = $modelArticle->article_id;
        						$articleKeywordItem->keyword_id = intval($keywordId);
        						$articleKeywordItem->sort_order = intval($indexOrder) + 1;
        						$articleKeywordItem->updated_on = date("Y-m-d H:i:s");
        						$articleKeywordItem->created_on = date("Y-m-d H:i:s");
        						if(!$articleKeywordItem->save()){
        							Yii::error("ArticleController->actionCreate(2): ".json_encode($articleKeywordItem->getErrors()), "custom_errors_articles");
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
        
        return $this->render('create', [
            'modelArticle' => $modelArticle,
        	'modelKeyword' => $modelKeyword,
        	'arrayArticleKeyword' => $arrayArticleKeyword,
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
        $modelKeyword = new Keyword();
        $modelArticleKeyword = new ArticleKeyword();
        $arrayArticleKeyword = [];
        $articleKeywords_array = $modelArticleKeyword->getKeywordsForArticle($id);
        if($articleKeywords_array != null && count($articleKeywords_array)>0 ){
        	foreach ($articleKeywords_array as $articleKeyword){
        		$arrayArticleKeyword[] = $articleKeyword->keyword->keyword_id;
        	}
        }

        $post_msg = null;
        $keywords_are_changed = true;
        if ($modelArticle->load(Yii::$app->request->post())) {
        	if(Yii::$app->request->post()['Article'] != null &&
        	   Yii::$app->request->post()['Article']['post_keywords'] != null)
        	{
        		$modelArticle->post_keywords = Yii::$app->request->post()['Article']['post_keywords'];
        		$current_keyword_array = [];
        		foreach ($modelArticle->keywords as $keywordObject){
        			$current_keyword_array[] = (string)$keywordObject->keyword_id;
        		}
        		$keywords_are_changed = !($modelArticle->post_keywords == $current_keyword_array);
        	}

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
        				
        				if($keywords_are_changed){
        					ArticleKeyword::deleteAll([
        							'article_id' => intval($id)
        					]);
        					foreach ($modelArticle->post_keywords as $indexOrder => $keywordId) {
        						$articleKeywordItem = new ArticleKeyword();        						
        						$articleKeywordItem->article_id = $id;
        						$articleKeywordItem->keyword_id = intval($keywordId);
        						$articleKeywordItem->sort_order = intval($indexOrder) + 1;
        						$articleKeywordItem->updated_on = date("Y-m-d H:i:s");
        						$articleKeywordItem->created_on = date("Y-m-d H:i:s");
        						if(!$articleKeywordItem->save()){
        							Yii::error("ArticleController->actionUpdate(2): ".json_encode($articleKeywordItem->getErrors()), "custom_errors_articles");
        						}
        					}
        				}
        	
        				if($update_sections_after_save == true && $section_id_old > 0 && $section_id_new > 0){
        					$modelOldSection = Section::findOne(['section_id' => $section_id_old]);
        					foreach ($modelOldSection->articles as $indexItem => $modelArticleItem) {
        						$modelArticleItem->sort_in_section = $indexItem;
        						if(!$modelArticleItem->save()){
        							Yii::error("ArticleController->actionUpdate(3): ".json_encode($modelArticleItem->getErrors()), "custom_errors_articles");
        						}
        					}
        	
        					$modelNewSection = Section::findOne(['section_id' => $section_id_new]);
        					foreach ($modelNewSection->articles as $indexItem => $modelArticleItem) {
        						$modelArticleItem->sort_in_section = $indexItem;
        						if(!$modelArticleItem->save()){
        							Yii::error("ArticleController->actionUpdate(4): ".json_encode($modelArticleItem->getErrors()), "custom_errors_articles");
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
        
        $modelArticle->post_keywords = $arrayArticleKeyword;
        
        return $this->render('update', [
            'modelArticle' => $modelArticle,
        	'modelKeyword' => $modelKeyword,
        	'arrayArticleKeyword' => $arrayArticleKeyword,
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
    	
    	$articleKeywordModel = new ArticleKeyword();
    	$article_keywords_string = $articleKeywordModel->getKeywordsForArticleString($modelArticle->article_id);
    	
    	$articleAuthorModel = new ArticleAuthor();
    	$article_authors_string = $articleAuthorModel->getAuthorsForArticleString($id);
    	
    	// get your HTML raw content without any layouts or scripts
    	$content = $modelArticle->abstract."<br>".$modelArticle->content;
    	if($partial != null && $partial == "abstract"){
    		$content = $modelArticle->abstract;
    	} else if($partial != null && $partial == "content"){
    		$content = $modelArticle->content;
    	} else {
    		$beforeArticleContent = "<h3 style='text-align: center;'>".$modelArticle->title."</h3>";
    		$afterArticleContent = "";
    		
    		if (strlen($article_authors_string)>0)
    			$beforeArticleContent = $beforeArticleContent."<p style='text-align: center;'><strong>Authors:&nbsp;</strong>".$article_authors_string."</p>";
    		if (strlen($article_keywords_string)>0)
				$afterArticleContent = "<p style='text-align: justify;'><strong>Keywords:&nbsp;</strong>".$article_keywords_string."</p>";
    		
    		$content = $beforeArticleContent.$content.$afterArticleContent;
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
