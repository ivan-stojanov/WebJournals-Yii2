<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Article List';
?>
<div class="article-index">

    <?= $this->render('_search', [
        'dataProvider' => $dataProvider,
    	'searchModel' => $searchModel,
    	'post_msg' => $post_msg,
    	'title_msg' => $title_msg
    ]) ?>
    
</div>
