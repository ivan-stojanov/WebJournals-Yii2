<?php

namespace backend\controllers;

use Yii;
use common\models\Section;
use common\models\Article;
use common\models\ArticleAuthor;
use common\models\ArticleKeyword;
use common\models\ArticleReviewer;
use common\models\ArticleFile;
use common\models\Keyword;
use common\models\User;
use backend\models\ArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Object;
use yii\web\UploadedFile;

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
    
    /*public function beforeAction($action) {
    	if($action->id == "create" || $action->id == "update"){
    		$this->enableCsrfValidation = false;
    	}
    	return parent::beforeAction($action);
    }*/

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
    	if (Yii::$app->user->isGuest || Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
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
        	'title_msg' => "Article List"
        ]);
    }
    
    /**
     * Lists my Articles models.
     * @return mixed
     */
    public function actionMyarticles()
    {
    	if (Yii::$app->user->isGuest){
    		return $this->redirect(['site/error']);
    	}
    	 
    	$queryParams = Yii::$app->request->queryParams;
    	$queryParams['is_deleted'] = 0;
    	 
    	$searchModel = new ArticleSearch();
    	$dataProvider = $searchModel->search($queryParams, Yii::$app->user->id);
    	$post_msg = null;
    
    	return $this->render('index', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'post_msg' => $post_msg,
    			'title_msg' => "My Article List"
    	]);
    }

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    		return $this->redirect(['site/error']);
    	}
    	
    	$articleAuthorModel = new ArticleAuthor();
    	$article_authors = $articleAuthorModel->getAuthorsForArticleString($id);
    	
    	$articleKeywordModel = new ArticleKeyword();
    	$article_keywords_string = $articleKeywordModel->getKeywordsForArticleString($id);
    	 
    	$articleReviewerModel = new ArticleReviewer();
    	$article_reviewers_string = $articleReviewerModel->getReviewersForArticleString($id);
    	
    	$current_user_id = ','.Yii::$app->user->id.',';
    	$user_can_modify = (strpos($article_authors['ids'], $current_user_id) !== false);
    	$user_can_modify = ($user_can_modify || Yii::$app->session->get('user.is_admin'));
    	
        return $this->render('view', [
            'model' => $this->findModel($id),
        	'article_authors' => $article_authors,
        	'article_keywords_string' => $article_keywords_string,
        	'article_reviewers_string' => $article_reviewers_string,
        	'user_can_modify' => $user_can_modify,
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	if (Yii::$app->user->isGuest){
    		return $this->redirect(['site/error']);
    	}

    	$modelArticle = new Article();
    	$modelKeyword = new Keyword();
    	$arrayArticleKeyword = [];
    	$modelUser = new User();
    	$arrayArticleAuthor = [];
    	$arrayArticleReviewer = [];
    		
    	$post_msg = null;
    	$modelArticle->created_on = date("Y-m-d H:i:s");
    	$addKeywords = false;
    	$addAuthors = false;
    	$addReviewers = false;
    		
    	if ($modelArticle->load(Yii::$app->request->post())) {
    		if(Yii::$app->request->post()['Article'] != null)
    		{
    			$file_attach = UploadedFile::getInstance($modelArticle, "file_attach");
    			if($file_attach != null)
    			{
    				$modelArticle->file_attach = $file_attach;
    				$modelArticle->file_id = $modelArticle->uploadFile($file_attach);
    			}
    		
    			if(Yii::$app->request->post()['Article']['post_keywords'] != null)
    			{
    				$modelArticle->post_keywords = Yii::$app->request->post()['Article']['post_keywords'];
    				$addKeywords = true;
    			}
    			if(Yii::$app->request->post()['Article']['post_authors'] != null)
    			{
    				$modelArticle->post_authors = Yii::$app->request->post()['Article']['post_authors'];
    				$addAuthors = true;
    			}
    			if(Yii::$app->request->post()['Article']['post_reviewers'] != null)
    			{
    				$modelArticle->post_reviewers = Yii::$app->request->post()['Article']['post_reviewers'];
    				$addReviewers = true;
    			}
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
    					if($addKeywords && $modelArticle != null && $modelArticle->post_keywords != null && count($modelArticle->post_keywords)>0) {
    						foreach ($modelArticle->post_keywords as $indexOrder => $keywordId) {
    							$articleKeywordItem = new ArticleKeyword();
    							$articleKeywordItem->article_id = $modelArticle->article_id;
    							$articleKeywordItem->keyword_id = intval($keywordId);
    							$articleKeywordItem->sort_order = intval($indexOrder) + 1;
    							$articleKeywordItem->created_on = date("Y-m-d H:i:s");
    							if(!$articleKeywordItem->save()){
    								Yii::error("ArticleController->actionCreate(2): ".json_encode($articleKeywordItem->getErrors()), "custom_errors_articles");
    							}
    						}
    					}
    					ArticleAuthor::deleteAll([
    							'article_id' => intval($modelArticle->article_id)
    					]);
    					if($addKeywords && $modelArticle != null && $modelArticle->post_authors != null && count($modelArticle->post_authors)>0) {
    						foreach ($modelArticle->post_authors as $indexAuthorOrder => $authorId) {
    							$articleAuthorItem = new ArticleAuthor();
    							$articleAuthorItem->article_id = $modelArticle->article_id;
    							$articleAuthorItem->author_id = intval($authorId);
    							$articleAuthorItem->sort_order = intval($indexAuthorOrder) + 1;
    							$articleAuthorItem->created_on = date("Y-m-d H:i:s");
    							if(!$articleAuthorItem->save()){
    								Yii::error("ArticleController->actionCreate(3): ".json_encode($articleAuthorItem->getErrors()), "custom_errors_articles");
    							}
   							}
   						}
   						ArticleReviewer::deleteAll([
    							'article_id' => intval($modelArticle->article_id)
    					]);
    					if($addReviewers && $modelArticle != null && $modelArticle->post_reviewers != null && count($modelArticle->post_reviewers)>0){
    						foreach ($modelArticle->post_reviewers as $indexReviewerOrder => $reviewerId) {
    							$articleReviewerItem = new ArticleReviewer();
    							$articleReviewerItem->article_id = $modelArticle->article_id;
   								$articleReviewerItem->reviewer_id = intval($reviewerId);
   								$articleReviewerItem->created_on = date("Y-m-d H:i:s");
   								if(!$articleReviewerItem->save()){
    								Yii::error("ArticleController->actionCreate(4): ".json_encode($articleReviewerItem->getErrors()), "custom_errors_articles");
    							}
    						}
    					}
    	
    					return $this->redirect(['view', 'id' => $modelArticle->article_id]);
    				}
    			} catch (Exception $e) {
    				Yii::error("ArticleController->actionCreate(5): ".json_encode($e), "custom_errors_articles");
    				$transaction->rollBack();
   				}
   			}
    	}
    		
    	return $this->render('create_admin', [
   				'modelArticle' => $modelArticle,
    			'modelKeyword' => $modelKeyword,
    			'modelUser' => $modelUser,
    			'arrayArticleKeyword' => $arrayArticleKeyword,
   				'arrayArticleAuthor' => $arrayArticleAuthor,
    			'arrayArticleReviewer' => $arrayArticleReviewer,
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
    	if (Yii::$app->user->isGuest){
    		return $this->redirect(['site/error']);
    	}
    	
    	$articleAuthorModel = new ArticleAuthor();
    	$article_authors = $articleAuthorModel->getAuthorsForArticleString($id);
    	$current_user_id = ','.Yii::$app->user->id.',';
    	$user_can_modify = (strpos($article_authors['ids'], $current_user_id) !== false);
    	$user_can_modify = ($user_can_modify || Yii::$app->session->get('user.is_admin'));
    	if ($user_can_modify != true){
    		return $this->redirect(['site/error']);
    	}    	
    	
        $modelArticle = $this->findModel($id);
        $modelArticle->updated_on = date("Y-m-d H:i:s");
        $update_sections_after_save = false; $section_id_old = 0; $section_id_new = 0;
        $modelKeyword = new Keyword();
        $modelArticleKeyword = new ArticleKeyword();
        $arrayArticleKeyword = [];
        $articleKeywords_array = $modelArticleKeyword->getKeywordsForArticle($id);
        if($articleKeywords_array != null && count($articleKeywords_array)>0){
        	foreach ($articleKeywords_array as $articleKeyword){
        		$arrayArticleKeyword[] = $articleKeyword->keyword->keyword_id;
        	}
        }
        $modelUser = new User();
        $modelArticleAuthor = new ArticleAuthor();
        $arrayArticleAuthor = [];
        $articleAuthors_array = $modelArticleAuthor->getAuthorsForArticle($id);
        if($articleAuthors_array != null && count($articleAuthors_array)>0){
        	foreach ($articleAuthors_array as $articleAuthor){
        		$arrayArticleAuthor[] = $articleAuthor->author->id;
        	}
        }
        $modelArticleReviewer = new ArticleReviewer();
        $arrayArticleReviewer = [];
        $articleReviewers_array = $modelArticleReviewer->getReviewersForArticle($id);
        if($articleReviewers_array != null && count($articleReviewers_array)>0){
        	foreach ($articleReviewers_array as $articleReviewer){
        		$arrayArticleReviewer[] = $articleReviewer->reviewer->id;
        	}
        }
        
        $post_msg = null;
        $keywords_are_changed = true;
        $authors_are_changed = true;
        $reviewers_are_changed = true;
        if ($modelArticle->load(Yii::$app->request->post())) 
        {
        	//to do = load is not loading uploaded file
        	if(Yii::$app->request->post()['Article'] != null)
        	{
        		$file_attach = UploadedFile::getInstance($modelArticle, "file_attach");
             	if($file_attach != null)
             	{
             		$modelArticle->file_attach = $file_attach;             		 
             		$modelArticle->file_id = $modelArticle->uploadFile($file_attach);             		 
             	}            	
        	}

        	if(Yii::$app->request->post()['Article'] != null)        	   
        	{
        		if(Yii::$app->request->post()['Article']['post_keywords'] != null)
        		{
        			$modelArticle->post_keywords = Yii::$app->request->post()['Article']['post_keywords'];
        			$current_keyword_array = [];
        			$current_keyword_array_int = [];
        			foreach ($modelArticle->articleKeywords as $keywordObject){
        				$current_keyword_array[] = (string)$keywordObject->keyword_id;
        				$current_keyword_array_int[] = $keywordObject->keyword_id;
        			}
        			$keywords_are_changed = !($modelArticle->post_keywords == $current_keyword_array);
        			if($keywords_are_changed)
        			{
        				$arrayArticleKeyword = $current_keyword_array_int;
        			}
        		}
        		if(Yii::$app->request->post()['Article']['post_authors'] != null)
        		{
        			$modelArticle->post_authors = Yii::$app->request->post()['Article']['post_authors'];
        			$current_author_array = [];
        			$current_author_array_int = [];
        			foreach ($modelArticle->articleAuthors as $authorObject){
        				$current_author_array[] = (string)$authorObject->author_id;
        				$current_author_array_int[] = $authorObject->author_id;
        			}
        			$authors_are_changed = !($modelArticle->post_authors == $current_author_array);
        			if($authors_are_changed)
        			{
        				$arrayArticleAuthor = $current_author_array_int;        				
        			}        			
        		}
        		if(Yii::$app->request->post()['Article']['post_reviewers'] != null)
        		{
        			$modelArticle->post_reviewers = Yii::$app->request->post()['Article']['post_reviewers'];
        			$current_reviewer_array = [];
        			foreach ($modelArticle->articleReviewers as $reviewerObject){
        				$current_reviewer_array[] = (string)$reviewerObject->reviewer_id;
        			}
        			$reviewers_are_changed = !($modelArticle->post_reviewers == $current_reviewer_array);
        		}
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
        				
        				if($keywords_are_changed) {
        					ArticleKeyword::deleteAll([
        							'article_id' => intval($id)
        					]);
        					if($modelArticle != null && $modelArticle->post_keywords != null && count($modelArticle->post_keywords)>0) {
        						foreach ($modelArticle->post_keywords as $indexKeywordOrder => $keywordId) {
        							$articleKeywordItem = new ArticleKeyword();
        							$articleKeywordItem->article_id = $id;
        							$articleKeywordItem->keyword_id = intval($keywordId);
        							$articleKeywordItem->sort_order = intval($indexKeywordOrder) + 1;
        							$articleKeywordItem->updated_on = date("Y-m-d H:i:s");
        							$articleKeywordItem->created_on = date("Y-m-d H:i:s");
        							if(!$articleKeywordItem->save()){
        								Yii::error("ArticleController->actionUpdate(2): ".json_encode($articleKeywordItem->getErrors()), "custom_errors_articles");
        							}
        						}       						
        					}
        				}
        				if($authors_are_changed) {
        					ArticleAuthor::deleteAll([
        							'article_id' => intval($id)
        					]);
        					if($modelArticle != null && $modelArticle->post_authors != null && count($modelArticle->post_authors)>0) {
	        					foreach ($modelArticle->post_authors as $indexAuthorOrder => $authorId) {
	        						$articleAuthorItem = new ArticleAuthor();
	        						$articleAuthorItem->article_id = $id;
	        						$articleAuthorItem->author_id = intval($authorId);
	        						$articleAuthorItem->sort_order = intval($indexAuthorOrder) + 1;
	        						$articleAuthorItem->updated_on = date("Y-m-d H:i:s");
	        						$articleAuthorItem->created_on = date("Y-m-d H:i:s");
	        						if(!$articleAuthorItem->save()){
	        							Yii::error("ArticleController->actionUpdate(3): ".json_encode($articleAuthorItem->getErrors()), "custom_errors_articles");
	        						}
	        					}
        					}
        				}
        				if($reviewers_are_changed) {
        					ArticleReviewer::deleteAll([
					    			'article_id' => intval($id)
					    	]);
        					if($modelArticle != null && $modelArticle->post_reviewers != null && count($modelArticle->post_reviewers)>0) {
	        					foreach ($modelArticle->post_reviewers as $indexReviewerOrder => $reviewerId) {
	        						$articleReviewerItem = new ArticleReviewer();
	        						$articleReviewerItem->article_id = $id;
	        						$articleReviewerItem->reviewer_id = intval($reviewerId);
	        						$articleReviewerItem->updated_on = date("Y-m-d H:i:s");
	        						$articleReviewerItem->created_on = date("Y-m-d H:i:s");
	        						if(!$articleReviewerItem->save()){
	        							Yii::error("ArticleController->actionUpdate(4): ".json_encode($articleReviewerItem->getErrors()), "custom_errors_articles");
	        						}
	        					}
        					}
        				}
        	
        				if($update_sections_after_save == true && $section_id_old > 0 && $section_id_new > 0){
        					$modelOldSection = Section::findOne(['section_id' => $section_id_old]);
        					foreach ($modelOldSection->articles as $indexItem => $modelArticleItem) {
        						$modelArticleItem->sort_in_section = $indexItem;
        						if(!$modelArticleItem->save()){
        							Yii::error("ArticleController->actionUpdate(5): ".json_encode($modelArticleItem->getErrors()), "custom_errors_articles");
        						}
        					}
        	
        					$modelNewSection = Section::findOne(['section_id' => $section_id_new]);
        					foreach ($modelNewSection->articles as $indexItem => $modelArticleItem) {
        						$modelArticleItem->sort_in_section = $indexItem;
        						if(!$modelArticleItem->save()){
        							Yii::error("ArticleController->actionUpdate(6): ".json_encode($modelArticleItem->getErrors()), "custom_errors_articles");
        						}
        					}
        				}
        	
          				return $this->redirect(['view', 'id' => $modelArticle->article_id]);
              		}
        		} catch (Exception $e) {
        			Yii::error("ArticleController->actionUpdate(7): ".json_encode($e), "custom_errors_articles");
        			$transaction->rollBack();
        		}
        	}        			
        }
        
        $modelArticle->post_keywords = $arrayArticleKeyword;
        $modelArticle->post_authors = $arrayArticleAuthor;
        $modelArticle->post_reviewers = $arrayArticleReviewer;
        
        return $this->render('update', [
            'modelArticle' => $modelArticle,
        	'modelKeyword' => $modelKeyword,
        	'modelUser' => $modelUser,
        	'arrayArticleKeyword' => $arrayArticleKeyword,
        	'arrayArticleAuthor' => $arrayArticleAuthor,
        	'arrayArticleReviewer' => $arrayArticleReviewer,
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
    	if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    		return $this->redirect(['site/error']);
    	}
    	
    	$articleAuthorModel = new ArticleAuthor();
    	$article_authors = $articleAuthorModel->getAuthorsForArticleString($id);
    	$current_user_id = ','.Yii::$app->user->id.',';
    	$user_can_modify = (strpos($article_authors['ids'], $current_user_id) !== false);
    	$user_can_modify = ($user_can_modify || Yii::$app->session->get('user.is_admin'));
    	if ($user_can_modify != true){
    		return $this->redirect(['site/error']);
    	}
    	
    	ArticleKeyword::deleteAll([
    			'article_id' => intval($id)
    	]);
    	ArticleAuthor::deleteAll([
    			'article_id' => intval($id)
    	]);
    	ArticleReviewer::deleteAll([
    			'article_id' => intval($id)
    	]);
    	
        $article_to_delete = $this->findModel($id);
        $file_id_to_delete = $article_to_delete->file_id;
        $parent_section = $article_to_delete->section;
        if(!$article_to_delete->delete()){
        	Yii::error("ArticleController->actionDelete(1): ".json_encode($article_to_delete->getErrors()), "custom_errors_articles");
        }
        
        foreach ($parent_section->articles as $index => $modelArticle) {
        	$modelArticle->sort_in_section = $index;      	
        	if(!$modelArticle->save()){
        		Yii::error("ArticleController->actionDelete(2): ".json_encode($modelArticle->getErrors()), "custom_errors_articles");
        	}        	 
        }
        
        if($file_id_to_delete != null) {
        	$modelFile = ArticleFile::findOne(['file_id' => $file_id_to_delete]);
        	$modelFile->is_deleted = true;
        	if(!$modelFile->save()){
        		Yii::error("ArticleController->actionDelete(3): ".json_encode($modelFile->getErrors()), "custom_errors_articles");
        	}
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
    
    /*
     * Asynch functions called with Ajax - Article (form of articles - remove file)
     */
    public function actionAsynchRemoveArticleFile()
    {
    	$articleReceivedID = Yii::$app->getRequest()->post('clickedElementID');
    
    	$articleID = json_decode($articleReceivedID);
    	
    	$modelArticle = $this->findModel($articleID);
    	
    	if ($modelArticle != null && $modelArticle->file_id != null) {
    		$modelArticle->file_id = null;
    		$modelArticle->updated_on = date("Y-m-d H:i:s");
    		if(!$modelArticle->save()){
    			throw new \Exception('Data not saved: '.print_r($modelArticle->errors, true), 500);
    		} else {
    			return "Article file has been successfully removed.";
    		}
    	}
    	 
    	/*header('HTTP/1.1 404');
    	 header('Content-type: application/json');
    	 $response = new Response();
    	 $response->format = Response::FORMAT_JSON;
    	 //$response->statusText = "Article file has been successfully removed.";
    	 $response->statusCode = 200;
    	 $response->data = [
    	 'message' => "Article file has been successfully removed.",
    	 ];
    	 Yii::$app->end();*/
    	 
    	return "Empty message!";
    }
    

    public function actionPdfview($id, $partial = null)
    {
    	$modelArticle = $this->findModel($id);
    	
    	$articleKeywordModel = new ArticleKeyword();
    	$article_keywords_string = $articleKeywordModel->getKeywordsForArticleString($modelArticle->article_id);
    	
    	$articleAuthorModel = new ArticleAuthor();
    	$article_authors_string = $articleAuthorModel->getAuthorsForArticleString($id)['string'];
    	
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
