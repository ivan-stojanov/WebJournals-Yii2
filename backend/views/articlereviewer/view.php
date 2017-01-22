<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\Article;
use common\models\ArticleReviewer;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $modelArticle->title;

\backend\assets\AppAsset::register($this);
$this->registerJsFile("@web/js/articlereviewerScript.js", [ 'depends' => ['backend\assets\CustomJuiAsset'], 'position' => \yii\web\View::POS_END]);
?>
<div class="article-view">

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
    
    <hr> 
    <h1><?= Html::encode("My Review") ?></h1>
    
    <?php 
    	if($modelArticleReviewer->is_submited == 0) {
    		echo "Status: Review is not submitted yet!";
    		$form = ActiveForm::begin();    		
    		    echo $form->field($modelArticleReviewer, 'short_comment')->dropDownList(
    		    	ArticleReviewer::$STATUS_REVIEW,
    		    	['prompt' => 'Select Status']
    		    );    		    
    		    echo $form->field($modelArticleReviewer, 'long_comment');
    		    echo Html::button('Submit', ['id' => 'create-review-btn', 'data-articleid' => $modelArticleReviewer->article_id, 'data-reviewerid' => $modelArticleReviewer->reviewer_id, 'class' => 'btn btn-primary']);
    		ActiveForm::end();
    		echo "<hr>";
    	} else if($modelArticleReviewer->is_submited == 1 && $modelArticleReviewer->is_editable == 1) {
    		echo "Status: Review is submitted, but can be edited still!";
    		$form = ActiveForm::begin();
    			echo $form->field($modelArticleReviewer, 'short_comment')->dropDownList(
    				ArticleReviewer::$STATUS_REVIEW,
    				['prompt' => 'Select Status']
    			);
    			echo $form->field($modelArticleReviewer, 'long_comment');
    		    echo Html::button('Submit', ['id' => 'update-review-btn', 'data-articleid' => $modelArticleReviewer->article_id, 'data-reviewerid' => $modelArticleReviewer->reviewer_id, 'class' => 'btn btn-primary']);
    		ActiveForm::end();    		
    		echo "<hr>";
    	} else if($modelArticleReviewer->is_submited == 1 && $modelArticleReviewer->is_editable == 0) {
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
    	}
    ?>

</div>
