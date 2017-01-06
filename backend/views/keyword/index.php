<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\KeywordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Keyword List';
?>
<div class="keyword-index">

    <?= $this->render('_search', [
        'dataProvider' => $dataProvider,
    	'searchModel' => $searchModel,
    	'post_msg' => $post_msg
    ]) ?>

</div>
