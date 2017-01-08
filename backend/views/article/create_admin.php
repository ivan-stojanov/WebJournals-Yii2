<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = 'Create Article';
?>
<div class="article-create">

    <?= $this->render('_form', [
        'modelArticle' => $modelArticle,
    	'modelKeyword' => $modelKeyword,
		'modelUser' => $modelUser,
    	'arrayArticleKeyword' => $arrayArticleKeyword,
    	'arrayArticleAuthor' => $arrayArticleAuthor,
    	'arrayArticleReviewer' => $arrayArticleReviewer,
    	'arrayArticleEditor' => $arrayArticleEditor,
    	'post_msg' => $post_msg,
    	'isAdminOrEditor' => $isAdminOrEditor,
    	'canEditForm' => $canEditForm,
    ]) ?>

</div>
