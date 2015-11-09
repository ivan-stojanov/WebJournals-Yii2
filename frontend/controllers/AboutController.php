<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\CommonVariables;

/**
 * About controller
 */
class AboutController extends Controller
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
     * Displays "Editorial Team" sub-section in "About" section.
     *
     * @return mixed
     */
    public function actionEditorialTeam()
    {
        return $this->render('editorialteam');
    }

    /**
     * Displays "Policies" sub-section in "About" section.
     *
     * @return mixed
     */
    public function actionPolicies()
    {
        return $this->render('policies');
    }
    
    /**
     * Displays "Submissions" sub-section in "About" section.
     *
     * @return mixed
     */
    public function actionSubmissions()
    {
    	return $this->render('submissions');
    }
    
    /**
     * Displays "Site Map" sub-section of "Other" sub-section in "About" section.
     *
     * @return mixed
     */
    public function actionSiteMap()
    {
    	return $this->render('sitemap');
    }
    
    /**
     * Displays "About This Publishing System" sub-section of "Other" sub-section in "About" section.
     *
     * @return mixed
     */
    public function actionAboutThisPublishingSystem()
    {
    	return $this->render('aboutthispublishingsystem');
    }
    
}
