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

class ArticleeditorController extends Controller
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

    public function actionMyarticles()
    {
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	if (Yii::$app->session->get('user.is_editor') != true){
    		return $this->redirect(['site/error']);
    	}
    	
    	$queryParams = Yii::$app->request->queryParams;
    	$queryParams['ArticleSearch']['is_deleted'] = 0;
    	
    	$searchModel = new ArticleSearch();
    	$dataProvider = $searchModel->search($queryParams, null, null, Yii::$app->user->id);
    	$post_msg = null;
    	 
    	return $this->render('myarticles', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'post_msg' => $post_msg,
    	]);
    }
}
