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
use common\models\ArticleKeyword;
use common\models\ArticleAuthor;

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
    	
    	$params_GET = Yii::$app->getRequest();
    	if($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'html') {
    		return $this->render('issue_html', [
    				'modelIssue' => $modelIssue
    		]);
    	} else if($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'pdf') {
    		return $this->redirect(['/search/pdfviewissue', 'id' => $modelIssue->issue_id]);
    	}
    	 
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
    	
    	$params_GET = Yii::$app->getRequest();
    	if($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'html') {
    		return $this->render('article_html', [
    				'modelArticle' => $modelArticle
    		]);
    	} else if($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'pdf') {
    		/*return $this->render('article_pdf', [
    				'modelArticle' => $modelArticle
    		]);  */  		
    		return $this->redirect(['/search/pdfview', 'id' => $modelArticle->article_id]);
    	}
    	
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
    
    public function actionPdfview($id, $partial = null)
    {
    	$modelArticle = $this->findArticle($id);
    	 
    	$article_keywords_string = ArticleKeyword::getKeywordsForArticleString($modelArticle->article_id)['string'];
    	 
    	$article_authors_string = ArticleAuthor::getAuthorsForArticleString($modelArticle->article_id)['string'];
    	 
    	// get your HTML raw content without any layouts or scripts
    	$abstract_title = "<strong>Abstract</strong><br/><br/>";
    	$content = $abstract_title.$modelArticle->abstract."<br>".$modelArticle->content;
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
    
    public function actionPdfviewissue($id)
    {
    	$modelIssue = $this->findIssue($id);    	
    	$pdf = Yii::$app->pdf; 
    	
    	$content = "";    	
    	
    	$issueImagesPath = Yii::$app->urlManagerCommon->createUrl('images/issues/cover.jpg');
    	if(isset($modelIssue->cover_image) && ($modelIssue->cover_image > 0) && isset($modelIssue->coverimage)){
    		$modelImage = $modelIssue->coverimage;
    		 
    		if ($modelImage) {
    			$issueImagesPath = Yii::$app->urlManagerCommon->createUrl('images/issues') . DIRECTORY_SEPARATOR . $modelIssue->volume_id . DIRECTORY_SEPARATOR;
    			$issueImagesPath = $issueImagesPath . $modelImage->path;
    		}
    	}
    	
    	$test = "http://localhost".$issueImagesPath;
    	$first_page = "<br><div style='text-align:center;'><img src='".$test."' style='height:80%;'/></div><br>";
    	$first_page .= "<br><div style='text-align:center; font-size:24px;'><strong>".$modelIssue->title."</strong></div>";
    	$first_page .= "<div style='text-align:center; font-size:16px;'><strong>(Volume: ".$modelIssue->volume->title.")</strong></div><br/>";
    	//$issue_title .= $test;//"http://".$_SERVER["SERVER_NAME"].$issueImagesPath;
    	$first_page .= "<pagebreak />";
    	
    	if($modelIssue->sections != null && count($modelIssue->sections)>0) {
    		foreach ($modelIssue->sections as $section_index => $section_item) {
    			if($section_item->publishedArticles != null && count($section_item->publishedArticles)>0) {
	    			//if($section_index == 0) {	$content .= "<hr>";	}
    				$content .= "Section: <i>".$section_item->title."</i><br/>";
    				foreach ($section_item->publishedArticles as $article_index => $article_item) {    					
    					
    					$article_keywords_string = ArticleKeyword::getKeywordsForArticleString($article_item->article_id)['string'];
    					$article_authors_string = ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['string'];
    					$beforeArticleContent = "<h3 style='text-align: center;'>".$article_item->title."</h3>";
    					$afterArticleContent = "";    					 
    					if (strlen($article_authors_string)>0) {
    						$beforeArticleContent = $beforeArticleContent."<p style='text-align: center;'><strong>Authors:&nbsp;</strong>".$article_authors_string."</p>";
    					}
    					if (strlen($article_keywords_string)>0) {
    						$afterArticleContent = "<p style='text-align: justify;'><strong>Keywords:&nbsp;</strong>".$article_keywords_string."</p>";
    					}    					
    					$articleContent = "<strong>Abstract</strong><br/><br/>";
    					$articleContent = $articleContent.$article_item->abstract."<br>".$article_item->content;
    					$articleContent = $beforeArticleContent.$articleContent.$afterArticleContent;
    					
    					$content .= "<div>";
    					$content .= $articleContent;
    					$content .= "</div>";    					
    					if($article_index < (count($section_item->publishedArticles)-1)) {
    						$content .= "<pagebreak />";
    					}
    				}
    			}    			   			
    		}
    	}
    	
		$content = $first_page.$content;
    			
    	$pdf->content = $content;
    	$pdf->options = [
    			'title' => $modelIssue->title,
    			'keywords' => 'krajee, grid, export, yii2-grid, pdf'
    	];
    	// call mPDF methods on the fly
    	$header = "||Volume: ".$modelIssue->volume->title;
    	$pageno = "|{PAGENO}|";    	
    	if(isset($modelIssue->published_on)) {
    		$pageno = "|{PAGENO}|Publish date: ".date("M d, Y", strtotime($modelIssue->published_on));
    	}    	
    	
    	$pdf->methods = [
    			'SetHeader'=>[$header],
    			'SetFooter'=>[$pageno],
    	];
    	return $pdf->render();
    }
}
