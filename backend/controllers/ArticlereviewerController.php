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
    			'modelArticleReviewer' => $modelArticleReviewer,
    			'modelArticle' => Article::findOne($id),
    			'article_authors' => $article_authors,
    			'article_keywords_string' => $article_keywords_string,
    			'article_reviewers' => $article_reviewers,
    			'article_editors' => $article_editors,
    			'user_can_modify' => $user_can_modify,
    			'isAdminOrEditor' => $isAdminOrEditor,
    			'isReviewer' => $isReviewer,
    	]);
    }

}
