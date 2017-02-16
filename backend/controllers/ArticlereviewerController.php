<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\models\ArticleSearch;
use common\models\Section;
use common\models\Article;
use common\models\ArticleAuthor;
use common\models\ArticleKeyword;
use common\models\ArticleReviewer;
use common\models\ArticleEditor;
use common\models\ArticleFile;
use common\models\Keyword;
use common\models\User;

class ArticlereviewerController extends Controller
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
					'asynch-create-article-review' => ['POST'],
					'asynch-update-article-review' => ['POST'],
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
	 * Lists all Pending reviews.
	 * @return mixed
	 */	
    public function actionPending()
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	if (Yii::$app->session->get('user.is_reviewer') != true){
    		return $this->redirect(['site/error']);
    	}
    	
    	$queryParams = Yii::$app->request->queryParams;
    	$queryParams['ArticleSearch']['is_deleted'] = 0;
    	$queryParams['ArticleSearch']['is_submited'] = 0;
    	$queryParams['ArticleSearch']['statuses_review'] = "1"; //under review and review required

    	$searchModel = new ArticleSearch();
    	$dataProvider = $searchModel->search($queryParams, null, Yii::$app->user->id);
    	$post_msg = null;
    	
    	return $this->render('pending', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'post_msg' => $post_msg,
    	]);
    }

    /**
     * Lists all Submitted reviews.
     * @return mixed
     */
    public function actionSubmitted()
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	if (Yii::$app->session->get('user.is_reviewer') != true){
    		return $this->redirect(['site/error']);
    	}
    	
    	$queryParams = Yii::$app->request->queryParams;
    	$queryParams['ArticleSearch']['is_deleted'] = 0;
    	$queryParams['ArticleSearch']['is_submited'] = 1;

    	$searchModel = new ArticleSearch();
    	$dataProvider = $searchModel->search($queryParams, null, Yii::$app->user->id);
    	$post_msg = null;
    	
    	return $this->render('submitted', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'post_msg' => $post_msg,
    	]);
    }
    
    /**
     * Displays a single Article model with Reviews.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	//if (Yii::$app->session->get('user.is_reviewer') != true){
    	//	return $this->redirect(['site/error']);
    	//}
    	
    	$modelArticleReviewer = ArticleReviewer::findOne([
    		'article_id' => $id,
    		'reviewer_id' => Yii::$app->user->id,    			
    	]);
    	
    	//$modelsArticleReviewer = ArticleReviewer::findAll([
    	//	'article_id' => $id
    	//]);
    	 
    	$modelArticle = Article::findOne($id);
    	$canEditForm = ($modelArticle->status == Article::STATUS_UNDER_REVIEW);
    	
    	$article_authors = ArticleAuthor::getAuthorsForArticleString($id);
    	$article_keywords_string = ArticleKeyword::getKeywordsForArticleString($id);
    	$article_reviewers = ArticleReviewer::getReviewersForArticleString($id);
    	$article_editors = ArticleEditor::getEditorsForArticleString($id);
    	$current_user_id = ','.Yii::$app->user->id.',';
    
    	$isAdminOrEditor = ((strpos($article_editors['ids'], $current_user_id) !== false) && Yii::$app->session->get('user.is_editor'));
    	$isAdminOrEditor = ($isAdminOrEditor || Yii::$app->session->get('user.is_admin'));
    	$isReviewer = ((strpos($article_reviewers['ids'], $current_user_id) !== false) && Yii::$app->session->get('user.is_reviewer'));
    	 
    	$user_can_modify = (strpos($article_authors['ids'], $current_user_id) !== false);
    	$user_can_modify = ($user_can_modify || ((strpos($article_editors['ids'], $current_user_id) !== false) && Yii::$app->session->get('user.is_editor')));
    	$user_can_modify = ($user_can_modify || Yii::$app->session->get('user.is_admin'));
    
    	return $this->render('view', [
    			//'modelsArticleReviewer' => $modelsArticleReviewer,
    			'modelArticleReviewer' => $modelArticleReviewer,
    			'modelArticle' => $modelArticle,
    			'article_authors' => $article_authors,
    			'article_keywords_string' => $article_keywords_string,
    			'article_reviewers' => $article_reviewers,
    			'article_editors' => $article_editors,
    			'user_can_modify' => $user_can_modify,
    			'isAdminOrEditor' => $isAdminOrEditor,
    			'isReviewer' => $isReviewer,
    			'canEditForm' => $canEditForm,
    	]);
    }
    
    /*
     * Asynch functions called with Ajax - ArticleReviewer (Reviewer menu - articlereviewer view page)
     */
    public function actionAsynchCreateArticleReview()
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
    	
    	if(!$modelArticleReviewer->save()){
    		Yii::error("ArticlereviewerController->actionAsynchCreateArticleReview(1): ".json_encode($modelArticleReviewer->getErrors()), "custom_errors_reviews");
    		throw new \Exception('Data not saved: '.print_r($modelArticleReviewer->errors, true), 500);
    	} else {
    		return "Review has been successfully created! Please refresh the page to get the updated status!";
    	}
    
    	return "Empty message!";
    }
    
    /*
     * Asynch functions called with Ajax - ArticleReviewer (Reviewer menu - articlereviewer view page)
     */
    public function actionAsynchUpdateArticleReview()
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
    	 
    	if(!$modelArticleReviewer->save()){
    		Yii::error("ArticlereviewerController->actionAsynchUpdateArticleReview(1): ".json_encode($modelArticleReviewer->getErrors()), "custom_errors_reviews");
    		throw new \Exception('Data not saved: '.print_r($modelArticleReviewer->errors, true), 500);
    	} else {
    		return "Review has been successfully updated! Please refresh the page to get the updated status!";
    	}
    
    	return "Empty message!";
    }

}
