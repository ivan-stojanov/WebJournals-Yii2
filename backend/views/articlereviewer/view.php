<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use common\models\Article;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $modelArticle->title;

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
    <h1><?= Html::encode("Reviews") ?></h1>
    
    <?php 
    	if($modelArticleReviewer->is_submited == 0) {
    		echo "Review is not submitted yet!";
    	} else if($modelArticleReviewer->is_submited == 1 && $modelArticleReviewer->is_editable == 1) {
    		echo "Can edit submited Review!";
    	} else if($modelArticleReviewer->is_submited == 1 && $modelArticleReviewer->is_editable == 0) {
    		echo "Can not edit submited Review, just show it!";
    	}
    ?>

</div>
