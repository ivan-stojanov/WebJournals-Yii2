<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\IssueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Issue List';
?>
<div class="issue-index">

    <?= $this->render('_search', [
        'dataProvider' => $dataProvider,
    	'searchModel' => $searchModel,
    	'post_msg' => $post_msg
    ]) ?>

</div>
