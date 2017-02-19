<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\Article;
use common\models\ArticleReviewer;
use common\models\ArticleReviewResponse;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $modelArticle->title;

\backend\assets\AppAsset::register($this);
$this->registerJsFile("@web/js/articlereviewerScript.js", [ 'depends' => ['backend\assets\CustomJuiAsset'], 'position' => \yii\web\View::POS_END]);
?>
<div class="article-view">

	<div class="alert alert-dismissable hidden-div" id="articlereview-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong><span id="articlereview-section-alert-msg"></span></strong>
	</div>

    <h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_article_details', [
    			'model' => $modelArticle,
    			'article_authors' => $article_authors,
    			'article_keywords_string' => $article_keywords_string,
    			'article_reviewers' => $article_reviewers,
    			'article_editors' => $article_editors,
    			'user_can_modify' => $user_can_modify,
    			'isAdminOrEditor' => $isAdminOrEditor,
    			'isReviewer' => $isReviewer,
    ]) ?>
    
    <?php 
    	$disabled = "disabled";
    ?>    
    
    <hr> 
	<div id="myreview-section-container">	    
	    <?php    
	    	if(isset($modelArticleReviewer) && $modelArticleReviewer->is_submited == 0) {
	    		echo "<h2><i>My Review</i></h2>";
	    		if(!isset($modelArticleReviewer)){
	    			echo "No Review Available!";
	    		}
	    		
	    		if($canEditForm)
	    			echo "Status: Review is not submitted yet!";
	    		else
	    			echo "Status: Article is not 'under review' state!";
	    		$form = ActiveForm::begin();    		
	    		    echo $form->field($modelArticleReviewer, 'short_comment')->dropDownList(
	    		    	ArticleReviewer::$STATUS_REVIEW,
	    		    	['prompt' => 'Select Status', 'disabled' => !$canEditForm]
	    		    );    		    
	    		    echo $form->field($modelArticleReviewer, 'long_comment')->textInput(['disabled' => !$canEditForm]);
	    		    echo Html::button('Submit', ['id' => 'create-review-btn', 'data-articleid' => $modelArticleReviewer->article_id, 'data-reviewerid' => $modelArticleReviewer->reviewer_id, 'class' => 'btn btn-primary', 'disabled' => !$canEditForm]);
	    		ActiveForm::end();
	    		echo "<hr>";
	    	} else if(isset($modelArticleReviewer) && $modelArticleReviewer->is_submited == 1 && $modelArticleReviewer->is_editable == 1) {
	    		echo "<h2><i>My Review</i></h2>";
	    		if(!isset($modelArticleReviewer)){
	    			echo "No Review Available!";
	    		}
	    		
	    		if($canEditForm)
	    			echo "Status: Review is submitted, but can be edited still!";
	    		else
	    			echo "Status: Article is not 'under review' state!";    		
	    		$form = ActiveForm::begin();
	    			echo $form->field($modelArticleReviewer, 'short_comment')->dropDownList(
	    				ArticleReviewer::$STATUS_REVIEW,
	    				['prompt' => 'Select Status', 'disabled' => !$canEditForm]
	    			);
	    			echo $form->field($modelArticleReviewer, 'long_comment')->textInput(['disabled' => !$canEditForm]);
	    		    echo Html::button('Submit', ['id' => 'update-review-btn', 'data-articleid' => $modelArticleReviewer->article_id, 'data-reviewerid' => $modelArticleReviewer->reviewer_id, 'class' => 'btn btn-primary', 'disabled' => !$canEditForm]);
	    		ActiveForm::end();    		
	    		echo "<hr>";
	    	} /*else if(isset($modelArticleReviewer) &&  $modelArticleReviewer->is_submited == 1 && $modelArticleReviewer->is_editable == 0) {
	    		echo "Status: Can not edit submitted Review!";//, just show it
	    		echo DetailView::widget([
	    			'model' => $modelArticleReviewer,
	    			'attributes' => [
	    				[
	    					'class' => DataColumn::className(), // this line is optional
	    					'attribute' => 'short_comment',
	    					'value' => (isset(ArticleReviewer::$STATUS_REVIEW[$modelArticleReviewer->short_comment])) ? ArticleReviewer::$STATUS_REVIEW[$modelArticleReviewer->short_comment] : null,
	    					'format' => 'HTML'
	    				],
	    				[
	    					'class' => DataColumn::className(), // this line is optional
	    					'attribute' => 'long_comment',
	    					'value' => (isset($modelArticleReviewer->long_comment)) ? $modelArticleReviewer->long_comment : null,
	    					'format' => 'HTML'
	    				],
	    				//'created_on:datetime',
	    				//'updated_on:datetime',
	    				[
	    					'class' => DataColumn::className(), // this line is optional
	    					'attribute' => 'created_on',
	    					'value' => (isset($modelArticleReviewer->created_on)) ? date("M d, Y, g:i:s A", strtotime($modelArticleReviewer->created_on)) : null,
	    					'format' => 'HTML'
	    				],
	    				[
	    					'class' => DataColumn::className(), // this line is optional
	    					'attribute' => 'updated_on',
	    					'value' => (isset($modelArticleReviewer->updated_on)) ? date("M d, Y, g:i:s A", strtotime($modelArticleReviewer->updated_on)) : null,
	    					'format' => 'HTML'
	    				],
	    			],
	    		]);
	    		echo "<hr>";
	    	}*/
	    ?>
	</div>
	
    <?php    	
    	if($modelsArticleReviewer != null) {
    ?>
			<h2><i>Submited Review(s):</i></h2>    	  		    
	<?php    		
    	    foreach ($modelsArticleReviewer as $index => $modelArticleReviewer){ 
    	    	
    	    	$attributesModelsArticleReviewer = null;
    	    	if($modelArticleReviewer->reviewer->id == \Yii::$app->user->identity->id){
    	    		$attributesModelsArticleReviewer = [
    	    			[
    	    				'class' => DataColumn::className(), // this line is optional
    	    				'attribute' => 'article_id',
    	    				'value' => "<b><span style='color:blue'>MY ARTICLE</span></b>",
    	    				'format' => 'HTML',
    	    				'label' => '',
    	    			],
    	    		];
    	    	}
    	    	
    	    	$attributesModelsArticleReviewer = ArrayHelper::merge($attributesModelsArticleReviewer, [
    	    		[
    	    			'class' => DataColumn::className(), // this line is optional
    	    			'attribute' => 'reviewer_id',
    	    			'value' => $modelArticleReviewer->reviewer->fullName." <".$modelArticleReviewer->reviewer->email.">",
    	    			//'format' => 'HTML'
    	    		],
    	    		[
    	    			'class' => DataColumn::className(), // this line is optional
    	    			'attribute' => 'short_comment',
    	    			'value' => ArticleReviewer::$STATUS_REVIEW[$modelArticleReviewer->short_comment],
    	    			'format' => 'HTML'
    	    		],
    	    		'long_comment',
    	    		[
    	    			'class' => DataColumn::className(), // this line is optional
    	    			'attribute' => 'created_on',
    	    			'value' => (isset($modelArticleReviewer->created_on)) ? date("M d, Y, g:i:s A", strtotime($modelArticleReviewer->created_on)) : null,
    	    			'format' => 'HTML'
    	    		],
    	    		[
    	    			'class' => DataColumn::className(), // this line is optional
    	    			'attribute' => 'updated_on',
    	    			'value' => (isset($modelArticleReviewer->updated_on)) ? date("M d, Y, g:i:s A", strtotime($modelArticleReviewer->updated_on)) : null,
    	    			'format' => 'HTML'
    	    		],
    	    	]);
    	    	
    	    	echo DetailView::widget([    	    		
    	    		'model' => $modelArticleReviewer,
    	    		'attributes' => $attributesModelsArticleReviewer,
    	    	]);
    	    	
    	    	$modelArticleReviewResponseNew = new ArticleReviewResponse();
    	    	$modelArticleReviewResponseNew->article_id = $modelArticleReviewer->article_id;
    	    	$modelArticleReviewResponseNew->reviewer_id = $modelArticleReviewer->reviewer_id;
    	    	$modelArticleReviewResponseNew->response_creator_id = \Yii::$app->user->id;

    	    	echo "<div id='reviewresponse_section".$index."'>";
    	    	
    	    	$modelsArticleReviewResponse = ArticleReviewResponse::find()->where(['article_id' => $modelArticleReviewer->article_id])
															    	    	->andWhere(['reviewer_id' => $modelArticleReviewer->reviewer_id])
															    	    	->andWhere(['is_deleted' => 0])
															    	    	->orderBy(['created_on' => SORT_ASC])
    	    																->all();
				if($modelsArticleReviewResponse != null && count($modelsArticleReviewResponse)>0){
					echo "<h4><i>Review's respons(es):</i></h4>";
					
					echo "<div class='alert alert-dismissable hidden-div' id='article-reviewresponse-section-alert".$index."'>";/*alert-danger alert-success alert-warning */
					echo "	<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
					echo "	<strong><span id='article-reviewresponse-section-alert-msg".$index."'></span></strong>";
					echo "</div>";					
				}
				echo "<div class='row'>";
	    	    echo "	<div class='col-xs-1'>";
			    echo "	</div>";	
    	    	echo "	<div class='col-xs-11'>";
					foreach ($modelsArticleReviewResponse as $indexReviewResoponse => $modelArticleReviewResponse){					
						echo DetailView::widget([
							'model' => $modelArticleReviewResponse,
							'attributes' => [
								[
									'class' => DataColumn::className(), // this line is optional
									'attribute' => 'response_creator_id',
									'value' => $modelArticleReviewResponse->responseCreator->fullName." <".$modelArticleReviewResponse->responseCreator->email.">",
									//'format' => 'HTML'
								],
									'long_comment',
								[
									'class' => DataColumn::className(), // this line is optional
									'attribute' => 'created_on',
									'value' => (isset($modelArticleReviewResponse->created_on)) ? date("M d, Y, g:i:s A", strtotime($modelArticleReviewResponse->created_on)) : null,
									'format' => 'HTML'
								],
							],
						]);					
					}
				echo "	</div>";
				echo "</div>";
				
				if($modelArticle->status == Article::STATUS_REVIEW_REQUIRED || $modelArticle->status == Article::STATUS_IMPROVEMENT ||
						$modelArticle->status == Article::STATUS_ACCEPTED_FOR_PUBLICATION || $modelArticle->status == Article::STATUS_REJECTED)
				{
					$form = ActiveForm::begin();
					echo $form->field($modelArticleReviewResponseNew, 'long_comment')->input('long_comment', ['placeholder' => "Enter Your Comment"])->label(false);
					echo Html::button('Post a comment', ['id' => 'id-post-reviewresponse-btn', 'data-articleid' => $modelArticleReviewResponseNew->article_id, 'data-reviewerid' => $modelArticleReviewResponseNew->reviewer_id, 'data-responsecreatorid' => $modelArticleReviewResponseNew->response_creator_id, 'data-index' => $index, 'class' => 'class-post-reviewresponse-btn btn btn-primary']);
					ActiveForm::end();
					echo "</div>";
					echo "<hr>";
				}    	    	
    	    }
    	}
    	
	?>	
	
</div>
