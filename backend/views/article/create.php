<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = 'Create Article';
?>
<div class="article-create">

    <?= $this->render('_form', [
        'modelArticle' => $modelArticle,
    	'post_msg' => $post_msg
    ]) ?>

</div>
