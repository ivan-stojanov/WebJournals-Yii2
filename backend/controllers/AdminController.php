<?php

namespace backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use common\models\HomepageSection;
use common\models\CommonVariables;

class AdminController extends \yii\web\Controller
{
	public function behaviors()
	{
		return [
				'verbs' => [
						'class' => VerbFilter::className(),
						'actions' => [
								'asynch-home-section-change-visibility' => ['post'],
								'asynch-home-section-change-sorting' => ['post'],
						],
				],
		];
	}
	
	
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionHome()
    {
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

    /**
     * set admin layout for menu of the left in admin section pages
     */
    public function beforeAction($action){
    	
    	$this->layout = 'adminlayout';    	 
    	return parent::beforeAction($action);
    	
    }
}
