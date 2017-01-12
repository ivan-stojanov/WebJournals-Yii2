<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\HomepageSection;
use common\models\CommonVariables;

class AdminController extends \yii\web\Controller
{
	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'rules' => [
								//not logged users do not have access to any action
								/*[
										'actions' => ['login', 'error'],
										'allow' => true,
								],*/
								//only logged users have access to actions
								[
										'actions' => [	'index', 'home', 'homecontent', 'home', 
														'asynch-home-section-change-visibility',
														'asynch-home-section-change-sorting',												
													 ],
										'allow' => true,
										'roles' => ['@'],
								],
						],
				],
				'verbs' => [
						'class' => VerbFilter::className(),
						'actions' => [
								'asynch-home-section-change-visibility' => ['post'],
								'asynch-home-section-change-sorting' => ['post'],
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
	
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionHome()
    {    	
    	if (Yii::$app->user->isGuest) {
    		return $this->redirect(Yii::$app->urlManagerFrontEnd->createUrl('site/login'));
    	}
    	if (Yii::$app->session->get('user.is_admin') != true){
    		return $this->redirect(['site/error']);
    	}
    	
    	$common_vars = new CommonVariables();
    	$homeSections = HomepageSection::find()
    					->where(['is_deleted' => false])
    					->orderBy('sort_order')
    					->all();
    	
    	return $this->render('home', [
    			'model' => $homeSections,
    			'common_vars' => $common_vars,
    	]);
    }
    
    public function actionHomecontent()
    {    	
    	$homeSection = HomepageSection::find()
    					->where(['section_type' => "page_content"])
    					->one();
    	
    	if ($homeSection->load(Yii::$app->request->post()) && $homeSection->save()) {
    		return $this->redirect(['home']);
    	} else {  	 
	    	return $this->render('homecontent', [
	    			'model' => $homeSection,
	    	]);
    	}
    } 
    
    /*
     * Asynch functions called with Ajax - Homepage (list of sections - change visibility)
     */
    public function actionAsynchHomeSectionChangeVisibility()
    {    
    	$rowId = Yii::$app->getRequest()->post('rowId');
    	$isChecked = Yii::$app->getRequest()->post('isChecked');    	

	   	$homeSection = HomepageSection::findOne([
    		'homepage_section_id' => $rowId
    	]);	
	   	
	   	if($isChecked == true)	{ $isChecked = 1; }	else { $isChecked = 0; }
	   	
	   	if(!isset($homeSection)){
	   		throw new \Exception('Homepage section with id: '.$rowId.' not found.', 500);
	   	}
	   	
    	$homeSection->is_visible = $isChecked;
    	$homeSection->updated_on = date("Y-m-d H:i:s");
    	
    	if(!$homeSection->save()){
    		throw new \Exception('Data not saved: '.print_r($homeSection->errors, true), 500);
    	}
    	
    	return "Section visibility has been successfully changed.";
    }
    
    /*
     * Asynch functions called with Ajax - Homepage (list of sections - change sorting)
     */
    public function actionAsynchHomeSectionChangeSorting()
    {
    	$homepageSectionIds = Yii::$app->getRequest()->post('sortedEntityIds');
    	
    	$sectionIds = json_decode($homepageSectionIds);
    	for ($i = 0; $i < count($sectionIds); $i++)
    	{
    		$rowId = intval(str_replace("row-number-", "", $sectionIds[$i]));
    		$homeSection = HomepageSection::findOne([
    				'homepage_section_id' => $rowId
    		]);
    		
    		if(!isset($homeSection)){
    			throw new \Exception('Homepage section with id: '.$rowId.' not found.', 500);
    		}
    		
	    	$homeSection->sort_order = $i + 1;
	    	$homeSection->updated_on = date("Y-m-d H:i:s");
	    	
	    	if(!$homeSection->save()){
	    		throw new \Exception('Data not saved: '.print_r($homeSection->errors, true), 500);
	    	}
    	}
    	
    	return "Section sorting has been successfully changed.";
    }
}
