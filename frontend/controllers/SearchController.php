<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\CommonVariables;
use common\models\User;
use common\models\Volume;
use common\models\Issue;
use common\models\Section;
use common\models\Article;
use common\models\Keyword;

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
    	
    	$volumes_result = null;
    	if(($params_GET == null || $params_GET->getQueryParam('type') == null) || ($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'volume')) {
    		$volumes_result = Volume::find()->where(['is_deleted' => 0]);    		
    		if(!(($params_GET == null || $params_GET->getQueryParam('letter') == null) || ($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'All'))) {
    			if($params_GET != null && $params_GET->getQueryParam('letter') != null) {    			
    				$volumes_result = $volumes_result->andWhere(['like', 'title', $params_GET->getQueryParam('letter').'%', false]);
    			}
    		}    		
    		if($params_GET != null && $params_GET->getQueryParam('text') != null) {
    			$volumes_result = $volumes_result->andWhere(['like', 'title', $params_GET->getQueryParam('text')]);
    		}    		
    		$volumes_result = $volumes_result->orderBy('title ASC')->all();
    	}
    	
    	$issues_result = null;
    	if($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'issue') {
    		$issues_result = Issue::find()->where(['is_deleted' => 0]);
    		if(!(($params_GET == null || $params_GET->getQueryParam('letter') == null) || ($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'All'))) {
    			if($params_GET != null && $params_GET->getQueryParam('letter') != null) {
    				$issues_result = $issues_result->andWhere(['like', 'title', $params_GET->getQueryParam('letter').'%', false]);
    			}
    		}
    		if($params_GET != null && $params_GET->getQueryParam('text') != null) {
    			$issues_result = $issues_result->andWhere(['like', 'title', $params_GET->getQueryParam('text')]);
    		}
    		$issues_result = $issues_result->orderBy('title ASC')->all();
    	}
    	
    	$sections_result = null;
    	if($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'section') {
    		$sections_result = Section::find()->where(['is_deleted' => 0]);
    		if(!(($params_GET == null || $params_GET->getQueryParam('letter') == null) || ($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'All'))) {
    			if($params_GET != null && $params_GET->getQueryParam('letter') != null) {
    				$sections_result = $sections_result->andWhere(['like', 'title', $params_GET->getQueryParam('letter').'%', false]);
    			}
    		}
    		if($params_GET != null && $params_GET->getQueryParam('text') != null) {
    			$sections_result = $sections_result->andWhere(['like', 'title', $params_GET->getQueryParam('text')]);
    		}
    		$sections_result = $sections_result->orderBy('title ASC')->all();
    	}
    	
    	$articles_result = null;
    	if($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'article') {
    		$articles_result = Article::find()->where(['is_deleted' => 0, 'status' => Article::STATUS_PUBLISHED]);
    		if(!(($params_GET == null || $params_GET->getQueryParam('letter') == null) || ($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'All'))) {
    			if($params_GET != null && $params_GET->getQueryParam('letter') != null) {
    				$articles_result = $articles_result->andWhere(['like', 'title', $params_GET->getQueryParam('letter').'%', false]);
    			}
    		}
    		if($params_GET != null && $params_GET->getQueryParam('text') != null) {
    			$articles_result = $articles_result->andWhere(['like', 'title', $params_GET->getQueryParam('text')]);
    		}
    		$articles_result = $articles_result->orderBy('title ASC')->all();
    	}
    	
    	$keywords_result = null;
    	if($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'keyword') {
    		$keywords_result = Keyword::find()->where(['is_deleted' => 0]);
    		if(!(($params_GET == null || $params_GET->getQueryParam('letter') == null) || ($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'All'))) {
    			if($params_GET != null && $params_GET->getQueryParam('letter') != null) {
    				$keywords_result = $keywords_result->andWhere(['like', 'content', $params_GET->getQueryParam('letter').'%', false]);
    			}
    		}
    		if($params_GET != null && $params_GET->getQueryParam('text') != null) {
    			$keywords_result = $keywords_result->andWhere(['like', 'content', $params_GET->getQueryParam('text')]);
    		}
    		$keywords_result = $keywords_result->orderBy('content ASC')->all();
    	}
    	  
    	$users_result = null;
    	if($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'user') {
    	$users_result = User::find()->where(['status' => User::STATUS_ACTIVE, 'is_author' => 1]);
    		if(!(($params_GET == null || $params_GET->getQueryParam('letter') == null) || ($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'All'))) {
    			if($params_GET != null && $params_GET->getQueryParam('letter') != null) {
    				$users_result = $users_result->andWhere(['like', 'last_name', $params_GET->getQueryParam('letter').'%', false]);
    			}
    		}
    		if($params_GET != null && $params_GET->getQueryParam('text') != null) {
    			$users_result = $users_result->andFilterWhere(['or',
										    					['like', 'first_name', $params_GET->getQueryParam('text')],
										    					['like', 'last_name', $params_GET->getQueryParam('text')]
											    			  ]);
    		}
    		$users_result = $users_result->orderBy('last_name ASC, first_name ASC')->all();
    	}
    	
    	return $this->render('index', [
    			'volumes_result' => $volumes_result,
    			'issues_result' => $issues_result,
    			'sections_result' => $sections_result,
    			'articles_result' => $articles_result,
    			'keywords_result' => $keywords_result,
    			'users_result' => $users_result,
    			'current_base_url' => $current_base_url,
    			'params_GET' => $params_GET,
    	]);
    }
    
    /**
     * Displays volume details page, on search.
     *
     * @return mixed
     */
    public function actionVolume($id)
    {
    	$modelVolume = $this->findVolume($id);    	
    	
    	return $this->render('volume', [
    			'modelVolume' => $modelVolume
    	]);
    }
    
    /**
     * Displays issue details page, on search.
     *
     * @return mixed
     */
    public function actionIssue($id)
    {
    	$modelIssue = $this->findIssue($id);
    	 
    	return $this->render('issue', [
    			'modelIssue' => $modelIssue
    	]);
    }
    
    /**
     * Displays section details page, on search.
     *
     * @return mixed
     */
    public function actionSection($id)
    {
    	$modelSection = $this->findSection($id);
    
    	return $this->render('section', [
    			'modelSection' => $modelSection
    	]);
    }
    
    /**
     * Displays article details page, on search.
     *
     * @return mixed
     */
    public function actionArticle($id)
    {
    	$modelArticle = $this->findArticle($id);
    
    	return $this->render('article', [
    			'modelArticle' => $modelArticle
    	]);
    }
    
    /**
     * Displays keyword details page, on search.
     *
     * @return mixed
     */
    public function actionKeyword($id)
    {
    	$modelKeyword = $this->findKeyword($id);
    
    	return $this->render('keyword', [
    			'modelKeyword' => $modelKeyword
    	]);
    }
    
    /**
     * Displays user details page, on search.
     *
     * @return mixed
     */
    public function actionUser($id)
    {
    	$modelUser = $this->findUser($id);
    
    	return $this->render('user', [
    			'modelUser' => $modelUser
    	]);
    }
    
    /**
     * Finds the Volume model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Volume the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findVolume($id)
    {
    	if (($model = Volume::findOne($id)) !== null) {
    		return $model;
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }
    
    /**
     * Finds the Issue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Issue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findIssue($id)
    {
    	if (($model = Issue::findOne($id)) !== null) {
    		return $model;
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }
    
    /**
     * Finds the Section model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Section the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findSection($id)
    {
    	if (($model = Section::findOne($id)) !== null) {
    		return $model;
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }
    
    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findArticle($id)
    {
    	if (($model = Article::findOne($id)) !== null) {
    		return $model;
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }
    
    /**
     * Finds the Keyword model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Keyword the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findKeyword($id)
    {
    	if (($model = Keyword::findOne($id)) !== null) {
    		return $model;
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
    }
    
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findUser($id)
    {
    	if (($model = User::findOne($id)) !== null) {
    		return $model;
    	} else {
    		throw new NotFoundHttpException('The requested page does not exist.');
    	}
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
