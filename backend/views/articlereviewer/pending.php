<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="article-reviewer-pending">

    <?= $this->render('_search', [
        'dataProvider' => $dataProvider,
    	'searchModel' => $searchModel,
    	'post_msg' => $post_msg,
    	'title_msg' => "Pending Reviews",
    ]) ?>
    
</div>
