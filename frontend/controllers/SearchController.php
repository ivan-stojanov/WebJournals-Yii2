<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\CommonVariables;
use common\models\User;
use common\models\Volume;

/**
 * Site controller
 */
class SearchController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays search page.
     *
     * @return mixed
     */
    public function actionIndex()
    {
    	$current_base_url = Yii::$app->urlManagerFrontEnd->createUrl('search/index');
    	$params_GET = Yii::$app->getRequest();
    	
    	/*$params_GET->getQueryParam('type')
    	$params_GET->getQueryParam('letter')
    	$params_GET->getQueryParam('text')*/
    	
    	$volumes_result = Volume::findAll(['is_deleted' => 0]);
    	//var_dump($volumes_result);
    	
    	return $this->render('index', [
    			'volumes_result' => $volumes_result,
    			'current_base_url' => $current_base_url,
    			'params_GET' => $params_GET,
    	]);
    }
    
    /*
     * Asynch functions called with Ajax - Search page (front-end)
     */
    /*public function actionAsynchSearchCriteria()
    {
    	$search_by_letter_POST = Yii::$app->getRequest()->post('search_by_letter');
    	$search_by_letter = json_decode($search_by_letter_POST);
    
    	$search_by_entities_POST = Yii::$app->getRequest()->post('search_by_entities');
    	$search_by_entities = json_decode($search_by_entities_POST);
    
    	$search_by_text_POST = Yii::$app->getRequest()->post('search_by_text');
    	$search_by_text = json_decode($search_by_text_POST);
     	 
    	return "Empty message!";
    }*/
}
