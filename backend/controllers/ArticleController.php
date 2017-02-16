<?php

namespace backend\controllers;

use Yii;
use common\models\Section;
use common\models\Article;
use common\models\ArticleAuthor;
use common\models\ArticleKeyword;
use common\models\ArticleReviewer;
use common\models\ArticleEditor;
use common\models\ArticleFile;
use common\models\Keyword;
use common\models\User;
use backend\models\ArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Object;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

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
                	'moveforreview' => ['POST'],
                	'moveforreviewrequired' => ['POST'],
                	'moveforpublish' => ['POST'],                		
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
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	if (Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
    	$queryParams = Yii::$app->request->queryParams;
    	$queryParams['ArticleSearch']['is_deleted'] = 0;

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
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	//if (Yii::$app->user->isGuest){
    	//	return $this->redirect(['site/error'] /*|| Yii::$app->session->get('user.is_admin') != true*/);
    	//}
    	 
    	$queryParams = Yii::$app->request->queryParams;
    	$queryParams['ArticleSearch']['is_deleted'] = 0;
    	 
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
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	//if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    	//	return $this->redirect(['site/error']);
    	//}

    	$article_authors = ArticleAuthor::getAuthorsForArticleString($id);    	
    	$article_correspondent_author = null;
    	if(isset($article_authors['correspondent_author'])){
    		$article_correspondent_author = User::findOne(intval($article_authors['correspondent_author']));
    	}    	
    	$article_keywords_string = ArticleKeyword::getKeywordsForArticleString($id);    	 
    	$article_reviewers = ArticleReviewer::getReviewersForArticleString($id);
    	$article_editors = ArticleEditor::getEditorsForArticleString($id);    	
    	$current_user_id = ','.Yii::$app->user->id.',';
    	 
    	$isEditor = ((strpos($article_editors['ids'], $current_user_id) !== false) && Yii::$app->session->get('user.is_editor'));
    	$isAdminOrEditor = ($isEditor || Yii::$app->session->get('user.is_admin'));
    	
    	$user_can_modify = (strpos($article_authors['ids'], $current_user_id) !== false);
    	$user_can_modify = ($user_can_modify || ((strpos($article_editors['ids'], $current_user_id) !== false) && Yii::$app->session->get('user.is_editor')));
    	$user_can_modify = ($user_can_modify || Yii::$app->session->get('user.is_admin'));

    	$modelArticle = $this->findModel($id);
    	$modelsArticleReviewer = null;
    	if($isAdminOrEditor || $user_can_modify) {
    		if($modelArticle->status != Article::STATUS_SUBMITTED && $modelArticle->status != Article::STATUS_UNDER_REVIEW) {
	    		$modelsArticleReviewer = ArticleReviewer::findAll([
	    			'article_id' => $id,
	    			'is_submited' => 1,
	    		]);
    		}
    	}
    	
    	$modelCurrentUserAsReviewer = null; 
    	if($isAdminOrEditor && $modelArticle->status == Article::STATUS_REVIEW_REQUIRED){
    		$modelCurrentUserAsReviewer = ArticleReviewer::findOne([
	    		'article_id' => $id,
	    		'reviewer_id' => Yii::$app->user->id,
	    	]);
    		if($modelCurrentUserAsReviewer == null){
    			$modelCurrentUserAsReviewer = new ArticleReviewer();
    			$modelCurrentUserAsReviewer->article_id = $id;
    			$modelCurrentUserAsReviewer->reviewer_id = Yii::$app->user->id;
    			$modelCurrentUserAsReviewer->scenario = 'article_reviewer_init';
    			$modelCurrentUserAsReviewer->created_on = date("Y-m-d H:i:s");
    			if(!$modelCurrentUserAsReviewer->save()){
    				Yii::error("ArticleController->actionView(1): ".json_encode($modelCurrentUserAsReviewer->getErrors()), "custom_errors_articles");
    			}
    		}
    	}

        return $this->render('view', [
            'model' => $modelArticle,
        	'modelsArticleReviewer' => $modelsArticleReviewer,
        	'modelCurrentUserAsReviewer' => $modelCurrentUserAsReviewer,
        	'article_authors' => $article_authors,
        	'article_keywords_string' => $article_keywords_string,
        	'article_reviewers' => $article_reviewers,
        	'article_editors' => $article_editors,        		
        	'article_correspondent_author' => $article_correspondent_author,
        	'user_can_modify' => $user_can_modify,        		
        	'isEditor' => $isEditor,
        	'isAdminOrEditor' => $isAdminOrEditor,        		
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	//if (Yii::$app->user->isGuest){
    	//	return $this->redirect(['site/error']);
    	//}
    	
    	$canEditForm = true;
    	$isAdminOrEditor = (Yii::$app->session->get('user.is_admin') == true);
    	$isAdminOrEditor = $isAdminOrEditor || (Yii::$app->session->get('user.is_editor') == true);
    	$modelArticle = new Article();
    	$modelKeyword = new Keyword();
    	$arrayArticleKeyword = [];
    	$modelUser = new User();
    	$arrayArticleAuthor = [];
    	$arrayArticleReviewer = [];
    	$arrayArticleEditor = [];
    	$correspondent_author = null;
    		
    	$post_msg = null;
    	$modelArticle->created_on = date("Y-m-d H:i:s");
    	$addKeywords = false;
    	$addAuthors = false;
    	$addReviewers = false;
    	$addEditors = false;
    	$file_attach = null;
    		
    	if ($modelArticle->load(Yii::$app->request->post())) {
    		if(isset(Yii::$app->request->post()['Article']) && Yii::$app->request->post()['Article'] != null)
    		{
    			$file_attach = UploadedFile::getInstance($modelArticle, "file_attach");
    			if($file_attach != null)
    			{
    				$modelArticle->file_attach = $file_attach;
    			}
    		
    			if(isset(Yii::$app->request->post()['Article']['post_keywords']) && Yii::$app->request->post()['Article']['post_keywords'] != null)
    			{
    				$modelArticle->post_keywords = Yii::$app->request->post()['Article']['post_keywords'];
    				$addKeywords = true;
    			}
    			if(isset(Yii::$app->request->post()['Article']['post_authors']) && Yii::$app->request->post()['Article']['post_authors'] != null)
    			{
    				$modelArticle->post_authors = Yii::$app->request->post()['Article']['post_authors'];
    				$addAuthors = true;
    			}   			
    			if(isset(Yii::$app->request->post()['Article']['post_reviewers']) && Yii::$app->request->post()['Article']['post_reviewers'] != null)
    			{
    				$modelArticle->post_reviewers = Yii::$app->request->post()['Article']['post_reviewers'];
    				$addReviewers = true;
    			}
    			if(isset(Yii::$app->request->post()['Article']['post_editors']) && Yii::$app->request->post()['Article']['post_editors'] != null)
    			{
    				$modelArticle->post_editors = Yii::$app->request->post()['Article']['post_editors'];
    				$addEditors = true;
    			}    			
    			if(isset(Yii::$app->request->post()['Article']['post_correspondent_author']) && Yii::$app->request->post()['Article']['post_correspondent_author'] != null)
    			{
    				$correspondent_author = Yii::$app->request->post()['Article']['post_correspondent_author'][0];
    			}
    		}
    			 
    		if($isAdminOrEditor == true) {
    			$modelSection = Section::findOne($modelArticle->section_id);
    			$modelArticle->sort_in_section = count($modelSection->articles);    			 
    		}
    		
    		// validate all models
    		$valid = $modelArticle->validate();
    			 
    		if ($valid) {    			
    			if($file_attach != null)
    			{
    				$modelArticle->file_id = $modelArticle->uploadFile($file_attach);
    			}    			
    			$transaction = \Yii::$app->db->beginTransaction();
    			try {
    				if ($flag = $modelArticle->save(false)) { 
    		
    				} else {
    					Yii::error("ArticleController->actionCreate(1): ".json_encode($modelArticle->getErrors()), "custom_errors_articles");
    				}
    				if ($flag) {
    					$transaction->commit();
    					
    					if($addAuthors && $modelArticle != null && $modelArticle->post_authors != null && count($modelArticle->post_authors)>0) {
    						$correspondent_author_is_regular_author = false;
    						foreach ($modelArticle->post_authors as $indexAuthorOrder => $authorId) {
    							if(isset($correspondent_author) && (intval($correspondent_author) == intval($authorId))){
    								$correspondent_author_is_regular_author = true;
    							}
    						}
    						
    						$reviewer_is_editor = false;
    						if($modelArticle != null && $modelArticle->post_reviewers != null && count($modelArticle->post_reviewers)>0) {
	    						foreach ($modelArticle->post_reviewers as $reviewerItem) {
	    							if(ArrayHelper::isIn($reviewerItem, $modelArticle->post_editors)){
	    								$reviewer_is_editor = true;
	    							}
	    						}
    						}
    						
    						$current_is_author = ArrayHelper::isIn(Yii::$app->user->id, $modelArticle->post_authors);
    						$current_is_author = $current_is_author || (Yii::$app->session->get('user.is_admin') == true);
    						
    						if($correspondent_author_is_regular_author == false || $current_is_author == false || $reviewer_is_editor == true) {
    							//Yii::$app->session->setFlash('error', 'Authors are not correctly updated! Correspondent author is not in the list for regular authors!');
    							$post_msg["type"] = "danger";
    							$post_msg["text"] = "Users are not correctly updated!<br><br>";
    							
    							if($current_is_author == false){
    								$post_msg["text"] .= "You are not listed as an author and You have to be!<br>";
    							}
    							if($correspondent_author_is_regular_author == false){
    								$post_msg["text"] .= "Correspondent author is not in the list for regular authors!<br>";
    							}
    							if($reviewer_is_editor == true){
    								$post_msg["text"] .= "Editor can not be set as an reviewer!<br>";
    							}

    							return $this->render('create_admin', [
						   				'modelArticle' => $modelArticle,
						    			'modelKeyword' => $modelKeyword,
						    			'modelUser' => $modelUser,
						    			'arrayArticleKeyword' => $arrayArticleKeyword,
						   				'arrayArticleAuthor' => $arrayArticleAuthor,
						    			'arrayArticleReviewer' => $arrayArticleReviewer,
    									'arrayArticleEditor' => $arrayArticleEditor,
						    			'post_msg' => $post_msg,
    									'isAdminOrEditor' => $isAdminOrEditor,
    									'canEditForm' => $canEditForm,
						    	]);
    						}    						
    					}    						
    		
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
    					if($addAuthors && $modelArticle != null && $modelArticle->post_authors != null && count($modelArticle->post_authors)>0) {
    						foreach ($modelArticle->post_authors as $indexAuthorOrder => $authorId) {
    							$articleAuthorItem = new ArticleAuthor();
    							$articleAuthorItem->article_id = $modelArticle->article_id;
    							$articleAuthorItem->author_id = intval($authorId);
    							$articleAuthorItem->sort_order = intval($indexAuthorOrder) + 1;
    							$articleAuthorItem->created_on = date("Y-m-d H:i:s");
    							if(isset($correspondent_author) && (intval($correspondent_author) == intval($authorId))){
    								$articleAuthorItem->is_correspondent = true;
    							}
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
   								$articleReviewerItem->scenario = 'article_reviewer_init';
   								if(!$articleReviewerItem->save()){
    								Yii::error("ArticleController->actionCreate(4): ".json_encode($articleReviewerItem->getErrors()), "custom_errors_articles");
    							}
    						}
    					}
    					ArticleEditor::deleteAll([
    							'article_id' => intval($modelArticle->article_id)
    					]);
    					if($addEditors && $modelArticle != null && $modelArticle->post_editors != null && count($modelArticle->post_editors)>0){
    						foreach ($modelArticle->post_editors as $indexEditorOrder => $editorId) {
    							$articleEditorItem = new ArticleEditor();
    							$articleEditorItem->article_id = $modelArticle->article_id;
    							$articleEditorItem->editor_id = intval($editorId);
    							$articleEditorItem->created_on = date("Y-m-d H:i:s");
    							if(!$articleEditorItem->save()){
    								Yii::error("ArticleController->actionCreate(5): ".json_encode($articleEditorItem->getErrors()), "custom_errors_articles");
    							}
    						}
    					}
    					return $this->redirect(['view', 'id' => $modelArticle->article_id]);
    				}
    			} catch (Exception $e) {
    				Yii::error("ArticleController->actionCreate(6): ".json_encode($e), "custom_errors_articles");
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
    			'arrayArticleEditor' => $arrayArticleEditor,
    			'post_msg' => $post_msg,
    			'isAdminOrEditor' => $isAdminOrEditor,
    			'canEditForm' => $canEditForm,
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
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	//if (Yii::$app->user->isGuest){
    	//	return $this->redirect(['site/error']);
    	//}
    	
    	$current_user_id = ','.Yii::$app->user->id.',';
    	$article_editors = ArticleEditor::getEditorsForArticleString($id);
    	$article_authors = ArticleAuthor::getAuthorsForArticleString($id);

    	$isAdminOrEditor = ((strpos($article_editors['ids'], $current_user_id) !== false) && Yii::$app->session->get('user.is_editor'));
    	$isAdminOrEditor = ($isAdminOrEditor || Yii::$app->session->get('user.is_admin'));    	
    	
    	$user_can_modify = (strpos($article_authors['ids'], $current_user_id) !== false);
    	$user_can_modify = ($user_can_modify || Yii::$app->session->get('user.is_admin'));
    	if ($user_can_modify != true){
    		return $this->redirect(['site/error']);
    	}    	
    	$articleCorrespondentAuthor = null;
    	if(isset($article_authors['correspondent_author'])){
    		$articleCorrespondentAuthor = $article_authors['correspondent_author'];
		}
  	
        $modelArticle = $this->findModel($id);
        $modelArticle->updated_on = date("Y-m-d H:i:s");
        $update_sections_after_save = false; $section_id_old = 0; $section_id_new = 0;
        $modelKeyword = new Keyword();
        $arrayArticleKeyword = [];
        $articleKeywords_array = ArticleKeyword::getKeywordsForArticle($id);
        if($articleKeywords_array != null && count($articleKeywords_array)>0){
        	foreach ($articleKeywords_array as $articleKeyword){
        		$arrayArticleKeyword[] = $articleKeyword->keyword->keyword_id;
        	}
        }
        $modelUser = new User();
        $arrayArticleAuthor = [];
        $articleAuthors_array = ArticleAuthor::getAuthorsForArticle($id);
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
        
        $modelArticleEditor = new ArticleEditor();
        $arrayArticleEditor = [];
        $articleEditors_array = $modelArticleEditor->getEditorsForArticle($id);
        if($articleEditors_array != null && count($articleEditors_array)>0){
        	foreach ($articleEditors_array as $articleEditor){
        		$arrayArticleEditor[] = $articleEditor->editor->id;
        	}
        }        
        
        $post_msg = null;
        $keywords_are_changed = true;
        $authors_are_changed = true;
        $reviewers_are_changed = true;
        $editors_are_changed = true;
        $file_attach = null;
        
        if ($modelArticle->load(Yii::$app->request->post())) 
        {
        	//to do = load is not loading uploaded file
        	if(isset(Yii::$app->request->post()['Article']) && Yii::$app->request->post()['Article'] != null)
        	{
        		$file_attach = UploadedFile::getInstance($modelArticle, "file_attach");
             	if($file_attach != null)
             	{
             		$modelArticle->file_attach = $file_attach;             		          		 
             	}
             	
        		if(isset(Yii::$app->request->post()['Article']['post_keywords']) && Yii::$app->request->post()['Article']['post_keywords'] != null)
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
        		if(isset(Yii::$app->request->post()['Article']['post_authors']) && Yii::$app->request->post()['Article']['post_authors'] != null)
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
        		if(isset(Yii::$app->request->post()['Article']['post_reviewers']) && Yii::$app->request->post()['Article']['post_reviewers'] != null)
        		{
        			$modelArticle->post_reviewers = Yii::$app->request->post()['Article']['post_reviewers'];
        			$current_reviewer_array = [];
        			foreach ($modelArticle->articleReviewers as $reviewerObject){
        				$current_reviewer_array[] = (string)$reviewerObject->reviewer_id;
        			}
        			$reviewers_are_changed = !($modelArticle->post_reviewers == $current_reviewer_array);
        		}
        		if(isset(Yii::$app->request->post()['Article']['post_editors']) && Yii::$app->request->post()['Article']['post_editors'] != null)
        		{
        			$modelArticle->post_editors = Yii::$app->request->post()['Article']['post_editors'];
        			$current_editor_array = [];
        			foreach ($modelArticle->articleEditors as $editorObject){
        				$current_editor_array[] = (string)$editorObject->editor_id;
        			}
        			$editors_are_changed = !($modelArticle->post_editors == $current_editor_array);
        		}
        		if(isset(Yii::$app->request->post()['Article']['post_correspondent_author']) && Yii::$app->request->post()['Article']['post_correspondent_author'] != null)
        		{
        			$modelArticle->post_correspondent_author = [intval(Yii::$app->request->post()['Article']['post_correspondent_author'][0])];
        			$correspondent_author_is_changed = !($modelArticle->post_correspondent_author[0] == $articleCorrespondentAuthor);
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
        		if($file_attach != null)
        		{
        			$modelArticle->file_id = $modelArticle->uploadFile($file_attach);
        		}
        		$transaction = \Yii::$app->db->beginTransaction();
        		try {
        			if ($flag = $modelArticle->save(false)) {
        	
        			} else {
        				Yii::error("ArticleController->actionUpdate(1): ".json_encode($modelArticle->getErrors()), "custom_errors_articles");
        			}
        			if ($flag) {
        				$transaction->commit();        				
        				
        			    if($authors_are_changed || $correspondent_author_is_changed) {
        					$correspondent_author_is_regular_author = false;
        					if($modelArticle != null && $modelArticle->post_authors != null && count($modelArticle->post_authors)>0) {
	        					foreach ($modelArticle->post_authors as $indexAuthorOrder => $authorId) {
	        						if(isset($modelArticle->post_correspondent_author) && (count($modelArticle->post_correspondent_author) > 0) 
	        								&& (intval($modelArticle->post_correspondent_author[0]) == intval($authorId)))
	        						{
	        							$correspondent_author_is_regular_author = true;
	        						}
	        					}
        					}
        					
        					$reviewer_is_editor = false;
        					if($modelArticle != null && $modelArticle->post_reviewers != null && count($modelArticle->post_reviewers)>0) {
	        					foreach ($modelArticle->post_reviewers as $reviewerItem) {
	        						if(ArrayHelper::isIn($reviewerItem, $modelArticle->post_editors)){
	        							$reviewer_is_editor = true;
	        						}
	        					}
        					}
        			        
        					$current_is_author = ArrayHelper::isIn(Yii::$app->user->id, $modelArticle->post_authors);
    						$current_is_author = $current_is_author || (Yii::$app->session->get('user.is_admin') == true);
    						
    						if($correspondent_author_is_regular_author == false || $current_is_author == false || $reviewer_is_editor == true) {
    							//Yii::$app->session->setFlash('error', 'Authors are not correctly updated! Correspondent author is not in the list for regular authors!');
    							$post_msg["type"] = "danger";
    							$post_msg["text"] = "Users are not correctly updated!<br><br>";
    							
    							if($current_is_author == false){
    								$post_msg["text"] .= "You are not listed as an author and You have to be!<br>";
    							}
    							if($correspondent_author_is_regular_author == false){
    								$post_msg["text"] .= "Correspondent author is not in the list for regular authors!<br>";
    							}
    							if($reviewer_is_editor == true){
    								$post_msg["text"] .= "Editor can not be set as an reviewer!<br>";
    							}
   							
    							$canEditForm = ($modelArticle->status == Article::STATUS_SUBMITTED || $modelArticle->status == Article::STATUS_IMPROVEMENT || $modelArticle->status == Article::STATUS_ACCEPTED_FOR_PUBLICATION);
    							
        						return $this->render('update', [
        								'modelArticle' => $modelArticle,
        								'modelKeyword' => $modelKeyword,
        								'modelUser' => $modelUser,
        								'arrayArticleKeyword' => $arrayArticleKeyword,
        								'arrayArticleAuthor' => $arrayArticleAuthor,
        								'arrayArticleReviewer' => $arrayArticleReviewer,
        								'arrayArticleEditor' => $arrayArticleEditor,
        								'post_msg' => $post_msg,
        								'isAdminOrEditor' => $isAdminOrEditor,
        								'canEditForm' => $canEditForm,
        						]);
        					}
        				}        				
        				
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
        				if($authors_are_changed || $correspondent_author_is_changed) {
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
	        						if(isset($modelArticle->post_correspondent_author) && (count($modelArticle->post_correspondent_author) > 0) 
	        								&& (intval($modelArticle->post_correspondent_author[0]) == intval($authorId)))
	        						{
	        							$articleAuthorItem->is_correspondent = true;
	        						}
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
	        						$articleReviewerItem->scenario = 'article_reviewer_init';
	        						if(!$articleReviewerItem->save()){
	        							Yii::error("ArticleController->actionUpdate(4): ".json_encode($articleReviewerItem->getErrors()), "custom_errors_articles");
	        						}
	        					}
        					}
        				}
        				if($editors_are_changed) {
        					ArticleEditor::deleteAll([
        							'article_id' => intval($id)
        					]);
        					if($modelArticle != null && $modelArticle->post_editors != null && count($modelArticle->post_editors)>0) {
        						foreach ($modelArticle->post_editors as $indexEditorOrder => $editorId) {
        							$articleEditorItem = new ArticleEditor();
        							$articleEditorItem->article_id = $id;
        							$articleEditorItem->editor_id = intval($editorId);
        							$articleEditorItem->updated_on = date("Y-m-d H:i:s");
        							$articleEditorItem->created_on = date("Y-m-d H:i:s");
        							if(!$articleEditorItem->save()){
        								Yii::error("ArticleController->actionUpdate(5): ".json_encode($articleEditorItem->getErrors()), "custom_errors_articles");
        							}
        						}
        					}
        				}        				
        	
        				if($update_sections_after_save == true && $section_id_old > 0 && $section_id_new > 0){
        					$modelOldSection = Section::findOne(['section_id' => $section_id_old]);
        					foreach ($modelOldSection->articles as $indexItem => $modelArticleItem) {
        						$modelArticleItem->sort_in_section = $indexItem;
        						if(!$modelArticleItem->save()){
        							Yii::error("ArticleController->actionUpdate(6): ".json_encode($modelArticleItem->getErrors()), "custom_errors_articles");
        						}
        					}
        	
        					$modelNewSection = Section::findOne(['section_id' => $section_id_new]);
        					foreach ($modelNewSection->articles as $indexItem => $modelArticleItem) {
        						$modelArticleItem->sort_in_section = $indexItem;
        						if(!$modelArticleItem->save()){
        							Yii::error("ArticleController->actionUpdate(7): ".json_encode($modelArticleItem->getErrors()), "custom_errors_articles");
        						}
        					}
        				}
        	
          				return $this->redirect(['view', 'id' => $modelArticle->article_id]);
              		}
        		} catch (Exception $e) {
        			Yii::error("ArticleController->actionUpdate(8): ".json_encode($e), "custom_errors_articles");
        			$transaction->rollBack();
        		}
        	}        			
        }
        
        $modelArticle->post_keywords = $arrayArticleKeyword;
        $modelArticle->post_authors = $arrayArticleAuthor;
        $modelArticle->post_reviewers = $arrayArticleReviewer;
        $modelArticle->post_editors = $arrayArticleEditor;
        $modelArticle->post_correspondent_author = [$articleCorrespondentAuthor];
        
        $canEditForm = ($modelArticle->status == Article::STATUS_SUBMITTED || $modelArticle->status == Article::STATUS_IMPROVEMENT || $modelArticle->status == Article::STATUS_ACCEPTED_FOR_PUBLICATION);
        
        return $this->render('update', [
            'modelArticle' => $modelArticle,
        	'modelKeyword' => $modelKeyword,
        	'modelUser' => $modelUser,
        	'arrayArticleKeyword' => $arrayArticleKeyword,
        	'arrayArticleAuthor' => $arrayArticleAuthor,
        	'arrayArticleReviewer' => $arrayArticleReviewer,
        	'arrayArticleEditor' => $arrayArticleEditor,
            'post_msg' => $post_msg,
        	'isAdminOrEditor' => $isAdminOrEditor,
        	'canEditForm' => $canEditForm,
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
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	//if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    	//	return $this->redirect(['site/error']);
    	//}
    	
    	$article_to_delete = $this->findModel($id);
    	$canEditForm = ($article_to_delete->status == Article::STATUS_SUBMITTED);
    	if($canEditForm != true){
    		Yii::$app->session->setFlash('error', 'Article can not be modified/deleted at the current state!');
    		return $this->redirect(['index']);
    	}
    	
    	$article_authors = ArticleAuthor::getAuthorsForArticleString($id);
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
     * Change the status of an existing Article model from STATUS_SUBMITTED to STATUS_UNDER_REVIEW.
     * If change is successful or not, the browser will redirect on the view article (stay on the same) page with message result.
     * @param integer $id
     * @return mixed
     */
    public function actionMoveforreview($id)
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	//if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    	//	return $this->redirect(['site/error']);
    	//}
    	
    	$current_user_id = ','.Yii::$app->user->id.',';
    	$article_editors = ArticleEditor::getEditorsForArticleString($id);
    	
    	$isAdminOrEditor = ((strpos($article_editors['ids'], $current_user_id) !== false) && Yii::$app->session->get('user.is_editor'));
    	$isAdminOrEditor = ($isAdminOrEditor || Yii::$app->session->get('user.is_admin'));
    	 
    	if($isAdminOrEditor == true) {    		
    		$modelArticle = $this->findModel($id);
    		$modelArticle->scenario = 'article_change_status';
    		$modelArticle->status = Article::STATUS_UNDER_REVIEW;
    		$modelArticle->updated_on = date("Y-m-d H:i:s");
    		if(!$modelArticle->save()){
    			Yii::error("ArticleController->actionMoveforreview(1): ".json_encode($modelArticle->getErrors()), "custom_errors_articles");
    			Yii::$app->session->setFlash('error', 'Some error occured! Please try again or contact the admin!');
    		} else {    			
    			$modelsArticleReviewer = ArticleReviewer::findAll([
    					'article_id' => $id,
    					'is_submited' => 0
    			]);    			
    			foreach ($modelsArticleReviewer as $index => $modelArticleReviewer) {
    				$email_sent_reviewer = Yii::$app->mailer->compose(['html' => 'moveForReviewReviewer-html', 'text' => 'moveForReviewReviewer-text'], ['modelArticleReviewer' => $modelArticleReviewer])
						    				->setTo($modelArticleReviewer->reviewer->email)
						    				->setFrom([Yii::$app->user->identity->email => Yii::$app->user->identity->fullName])
						    				->setSubject("Article sent for Review!")
						    				->send();
    				if(!$email_sent_reviewer) {
    					Yii::error("ArticleController->actionMoveforreview(2): Failure! Email to reviewer has not been sent", "custom_errors_articles");
    				}
    			}
    			$modelsArticleAuthor = ArticleAuthor::findAll([
    					'article_id' => $id,
    					'is_correspondent' => 1
    			]);
    			foreach ($modelsArticleAuthor as $index => $modelArticleAuthor) {
    				$email_sent_author = Yii::$app->mailer->compose(['html' => 'moveForReviewAuthor-html', 'text' => 'moveForReviewAuthor-text'], ['modelArticleAuthor' => $modelArticleAuthor])
						    				->setTo($modelArticleAuthor->author->email)
						    				->setFrom([Yii::$app->user->identity->email => Yii::$app->user->identity->fullName])
						    				->setSubject("Article sent for Review!")
						    				->send();
    				if(!$email_sent_author) {
    					Yii::error("ArticleController->actionMoveforreview(3): Failure! Email to author has not been sent", "custom_errors_articles");
    				}
    			}
    			
    			Yii::$app->session->setFlash('success', 'Article status has been successfully changed into \'review\' state!');
    		}    		
    	} else {
    		Yii::$app->session->setFlash('error', 'You do not have permission for performing this action!');
    	}
    
    	return $this->redirect(['view', 'id' => $modelArticle->article_id]);
    }
    
    /**
     * Change the status of an existing Article model from STATUS_UNDER_REVIEW to STATUS_REVIEW_REQUIRED.
     * If change is successful or not, the browser will redirect on the view article (stay on the same) page with message result.
     * @param integer $id
     * @return mixed
     */
    public function actionMoveforreviewrequired($id)
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	//if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    	//	return $this->redirect(['site/error']);
    	//}
    	 
    	$current_user_id = ','.Yii::$app->user->id.',';
    	$article_editors = ArticleEditor::getEditorsForArticleString($id);
    	 
    	$isAdminOrEditor = ((strpos($article_editors['ids'], $current_user_id) !== false) && Yii::$app->session->get('user.is_editor'));
    	$isAdminOrEditor = ($isAdminOrEditor || Yii::$app->session->get('user.is_admin'));
    
    	if($isAdminOrEditor == true) {
    		$transaction = \Yii::$app->db->beginTransaction();
    		try {
	    		$modelArticle = $this->findModel($id);
	    		$modelArticle->scenario = 'article_change_status';
	    		$modelArticle->status = Article::STATUS_REVIEW_REQUIRED;
	    		$modelArticle->updated_on = date("Y-m-d H:i:s");
	    		if($flag = $modelArticle->save(false)){
	    			$modelsArticleReviewer = ArticleReviewer::findAll([
	    				'article_id' => $id
	    			]);
	    			
	    			foreach ($modelsArticleReviewer as $index => $modelArticleReviewer) {
	    				$modelArticleReviewer->is_editable = 0;
	    				$modelArticleReviewer->updated_on = date("Y-m-d H:i:s");
	    			
	    				if (($flag = $modelArticleReviewer->save(false)) === false) {
	    					Yii::error("ArticleController->actionMoveforreviewrequired(1): ".json_encode($modelArticleReview->getErrors()), "custom_errors_articles");
	    					$transaction->rollBack();
	    					break;
	    				}
	    			}
	    			if ($flag) {
	    				$transaction->commit();
	    				Yii::$app->session->setFlash('success', 'Article status has been successfully changed into \'review required\' state!');
	    			}
	    		} else {
	    			Yii::error("ArticleController->actionMoveforreviewrequired(2): ".json_encode($modelArticle->getErrors()), "custom_errors_articles");
	    			$transaction->rollBack();
	    			Yii::$app->session->setFlash('error', 'Some error occured! Please try again or contact the admin!');
		    	}
	    	} catch (Exception $e) {
	    		Yii::error("ArticleController->actionMoveforreviewrequired(3): ".json_encode($e), "custom_errors_articles");
	    		$transaction->rollBack();
	    		Yii::$app->session->setFlash('error', 'Some error occured! Please try again or contact the admin!');
	    	}
    	} else {
    		Yii::$app->session->setFlash('error', 'You do not have permission for performing this action!');
    	}
    
    	return $this->redirect(['view', 'id' => $modelArticle->article_id]);
    }
    
    /**
     * Change the status of an existing Article model from STATUS_ACCEPTED_FOR_PUBLICATION to STATUS_PUBLISHED.
     * If change is successful or not, the browser will redirect on the view article (stay on the same) page with message result.
     * @param integer $id
     * @return mixed
     */
    public function actionMoveforpublish($id)
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	//if (Yii::$app->user->isGuest /*|| Yii::$app->session->get('user.is_admin') != true*/){
    	//	return $this->redirect(['site/error']);
    	//}
    	 
    	$current_user_id = ','.Yii::$app->user->id.',';
    	$article_editors = ArticleEditor::getEditorsForArticleString($id);
    	 
    	$isAdminOrEditor = ((strpos($article_editors['ids'], $current_user_id) !== false) && Yii::$app->session->get('user.is_editor'));
    	$isAdminOrEditor = ($isAdminOrEditor || Yii::$app->session->get('user.is_admin'));
    
    	if($isAdminOrEditor == true) {
    		$modelArticle = $this->findModel($id);
    		$modelArticle->scenario = 'article_change_status';
    		$modelArticle->status = Article::STATUS_PUBLISHED;
    		$modelArticle->updated_on = date("Y-m-d H:i:s");
    		if(!$modelArticle->save()){
    			Yii::error("ArticleController->actionMoveforpublish(1): ".json_encode($modelArticle->getErrors()), "custom_errors_articles");
    			Yii::$app->session->setFlash('error', 'Some error occured! Please try again or contact the admin!');
    		} else {
    			//Yii::$app->session->setFlash('success', 'Article status has been successfully \'published\'!');
    		}
    	} else {
    		Yii::$app->session->setFlash('error', 'You do not have permission for performing this action!');
    	}
    
    	return $this->redirect(['view', 'id' => $modelArticle->article_id]);
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
    
    /*
     * Asynch functions called with Ajax - Article (article view page when see from edior and with status Article::STATUS_REVIEW_REQUIRED)
     */
    public function actionAsynchArticleStatusAccept()
    {
    	$articleReceivedID = Yii::$app->getRequest()->post('articleid');
    	$articleID = json_decode($articleReceivedID);
    
    	$reviewerReceivedID = Yii::$app->getRequest()->post('reviewerid');
    	$reviewerID = json_decode($reviewerReceivedID);
    	 
    	$shortcommentReceived = Yii::$app->getRequest()->post('shortcomment');
    	$shortcomment = $shortcommentReceived; //json_decode($shortcommentReceived);
    
    	$longcommentReceived = Yii::$app->getRequest()->post('longcomment');
    	$longcomment = $longcommentReceived; //json_decode($longcommentReceived);
    
    	$modelArticleReviewer = ArticleReviewer::findOne([
    			'article_id' => $articleID,
    			'reviewer_id' => $reviewerID,
    	]);
    	
    	$transaction = \Yii::$app->db->beginTransaction();
    	try {
    		if ($modelArticleReviewer == null) {
    			$modelArticleReviewer = new ArticleReviewer();
    			$modelArticleReviewer->created_on = date("Y-m-d H:i:s");
    		} else {
    			$modelArticleReviewer->updated_on = date("Y-m-d H:i:s");
    		}
    		 
    		if($shortcomment == '0'){
    			$shortcomment = 0;
    		} else if(isset($shortcomment) && ($shortcomment != null) && ($shortcomment != '')){
    			$shortcomment = intval($shortcomment);
    		}
    		 
    		$modelArticleReviewer->short_comment = $shortcomment;
    		$modelArticleReviewer->long_comment = $longcomment;
    		$modelArticleReviewer->is_submited = 1;
    		$modelArticleReviewer->is_editable = 0;
    		 
    		if($flag = $modelArticleReviewer->save(false)){    			
    			$modelArticle = $this->findModel($articleID);
    			$modelArticle->status = Article::STATUS_ACCEPTED_FOR_PUBLICATION;
    			$modelArticle->updated_on = date("Y-m-d H:i:s");
    			if (($flag = $modelArticle->save(false)) === false) {
    				Yii::error("ArticleController->actionAsynchArticleStatusAccept(1): ".json_encode($modelArticle->getErrors()), "custom_errors_articles");
    				$transaction->rollBack();
    			}
    			
    			if ($flag) {
    				$transaction->commit();
    				return "Article has been successfully accepted! Please refresh the page to get the updated status!";
    			}    			
     		} else {
    			Yii::error("ArticleController->actionAsynchArticleStatusAccept(2): ".json_encode($modelArticleReviewer->getErrors()), "custom_errors_articles");
    			$transaction->rollBack();
    			throw new \Exception('Data not saved: '.print_r($modelArticleReviewer->errors, true), 500);
    		}    		
    	} catch (Exception $e) {
    		Yii::error("ArticleController->actionAsynchArticleStatusAccept(3): ".json_encode($e), "custom_errors_articles");
    		$transaction->rollBack();
    		throw new \Exception('Some error occured! Please try again or contact the admin!', 500);
    	}
    
    	return "Empty message!";
    }    

    /*
     * Asynch functions called with Ajax - Article (article view page when see from edior and with status Article::STATUS_REVIEW_REQUIRED)
     */
    public function actionAsynchArticleStatusReject()
    {
    	$articleReceivedID = Yii::$app->getRequest()->post('articleid');
    	$articleID = json_decode($articleReceivedID);
    
    	$reviewerReceivedID = Yii::$app->getRequest()->post('reviewerid');
    	$reviewerID = json_decode($reviewerReceivedID);
    
    	$shortcommentReceived = Yii::$app->getRequest()->post('shortcomment');
    	$shortcomment = $shortcommentReceived; //json_decode($shortcommentReceived);
    
    	$longcommentReceived = Yii::$app->getRequest()->post('longcomment');
    	$longcomment = $longcommentReceived; //json_decode($longcommentReceived);
    
    	$modelArticleReviewer = ArticleReviewer::findOne([
    			'article_id' => $articleID,
    			'reviewer_id' => $reviewerID,
    	]);
    	 
    	$transaction = \Yii::$app->db->beginTransaction();
    	try {
    		if ($modelArticleReviewer == null) {
    			$modelArticleReviewer = new ArticleReviewer();
    			$modelArticleReviewer->created_on = date("Y-m-d H:i:s");
    		} else {
    			$modelArticleReviewer->updated_on = date("Y-m-d H:i:s");
    		}
    		 
    		if($shortcomment == '0'){
    			$shortcomment = 0;
    		} else if(isset($shortcomment) && ($shortcomment != null) && ($shortcomment != '')){
    			$shortcomment = intval($shortcomment);
    		}
    		 
    		$modelArticleReviewer->short_comment = $shortcomment;
    		$modelArticleReviewer->long_comment = $longcomment;
    		$modelArticleReviewer->is_submited = 1;
    		$modelArticleReviewer->is_editable = 0;
    		 
    		if($flag = $modelArticleReviewer->save(false)){
    			$modelArticle = $this->findModel($articleID);
    			$modelArticle->status = Article::STATUS_REJECTED;
    			$modelArticle->updated_on = date("Y-m-d H:i:s");
    			if (($flag = $modelArticle->save(false)) === false) {
    				Yii::error("ArticleController->actionAsynchArticleStatusReject(1): ".json_encode($modelArticle->getErrors()), "custom_errors_articles");
    				$transaction->rollBack();
    			}
    			 
    			if ($flag) {
    				$transaction->commit();
    				return "Article has been successfully rejected! Please refresh the page to get the updated status!";
    			}
    		} else {
    			Yii::error("ArticleController->actionAsynchArticleStatusReject(2): ".json_encode($modelArticleReviewer->getErrors()), "custom_errors_articles");
    			$transaction->rollBack();
    			throw new \Exception('Data not saved: '.print_r($modelArticleReviewer->errors, true), 500);
    		}
    	} catch (Exception $e) {
    		Yii::error("ArticleController->actionAsynchArticleStatusReject(3): ".json_encode($e), "custom_errors_articles");
    		$transaction->rollBack();
    		throw new \Exception('Some error occured! Please try again or contact the admin!', 500);
    	}
    
    	return "Empty message!";
    }
    
    /*
     * Asynch functions called with Ajax - Article (article view page when see from edior and with status Article::STATUS_REVIEW_REQUIRED)
     */
    public function actionAsynchArticleStatusImprovement()
    {
    	$articleReceivedID = Yii::$app->getRequest()->post('articleid');
    	$articleID = json_decode($articleReceivedID);
    
    	$reviewerReceivedID = Yii::$app->getRequest()->post('reviewerid');
    	$reviewerID = json_decode($reviewerReceivedID);
    
    	$shortcommentReceived = Yii::$app->getRequest()->post('shortcomment');
    	$shortcomment = $shortcommentReceived; //json_decode($shortcommentReceived);
    
    	$longcommentReceived = Yii::$app->getRequest()->post('longcomment');
    	$longcomment = $longcommentReceived; //json_decode($longcommentReceived);
    
    	$modelArticleReviewer = ArticleReviewer::findOne([
    			'article_id' => $articleID,
    			'reviewer_id' => $reviewerID,
    	]);
    
    	$transaction = \Yii::$app->db->beginTransaction();
    	try {
    		if ($modelArticleReviewer == null) {
    			$modelArticleReviewer = new ArticleReviewer();
    			$modelArticleReviewer->created_on = date("Y-m-d H:i:s");
    		} else {
    			$modelArticleReviewer->updated_on = date("Y-m-d H:i:s");
    		}
    		 
    		if($shortcomment == '0'){
    			$shortcomment = 0;
    		} else if(isset($shortcomment) && ($shortcomment != null) && ($shortcomment != '')){
    			$shortcomment = intval($shortcomment);
    		}
    		 
    		$modelArticleReviewer->short_comment = $shortcomment;
    		$modelArticleReviewer->long_comment = $longcomment;
    		$modelArticleReviewer->is_submited = 1;
    		$modelArticleReviewer->is_editable = 1;
    		 
    		if($flag = $modelArticleReviewer->save(false)){
    			$modelArticle = $this->findModel($articleID);
    			$modelArticle->status = Article::STATUS_IMPROVEMENT;
    			$modelArticle->updated_on = date("Y-m-d H:i:s");
    			if (($flag = $modelArticle->save(false)) === false) {
    				Yii::error("ArticleController->actionAsynchArticleStatusImprovement(1): ".json_encode($modelArticle->getErrors()), "custom_errors_articles");
    				$transaction->rollBack();
    			}
    
    			if ($flag) {
    				$transaction->commit();
    				return "Article has been successfully moved back for improvement! Please refresh the page to get the updated status!";
    			}
    		} else {
    			Yii::error("ArticleController->actionAsynchArticleStatusImprovement(2): ".json_encode($modelArticleReviewer->getErrors()), "custom_errors_articles");
    			$transaction->rollBack();
    			throw new \Exception('Data not saved: '.print_r($modelArticleReviewer->errors, true), 500);
    		}
    	} catch (Exception $e) {
    		Yii::error("ArticleController->actionAsynchArticleStatusImprovement(3): ".json_encode($e), "custom_errors_articles");
    		$transaction->rollBack();
    		throw new \Exception('Some error occured! Please try again or contact the admin!', 500);
    	}
    
    	return "Empty message!";
    }
    
    public function actionPdfview($id, $partial = null)
    {
    	$modelArticle = $this->findModel($id);
    	
    	$article_keywords_string = ArticleKeyword::getKeywordsForArticleString($modelArticle->article_id);
    	
    	$article_authors_string = ArticleAuthor::getAuthorsForArticleString($id)['string'];
    	
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
