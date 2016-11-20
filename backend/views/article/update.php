<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = 'Update Article';
?>
<div class="article-update">

    <?= $this->render('_form', [
        'modelArticle' => $modelArticle,
    	'modelKeyword' => $modelKeyword,
    	'arrayArticleKeyword' => $arrayArticleKeyword,
    	'post_msg' => $post_msg
    ]) ?>

</div>
