<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\VolumeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Volume List';
?>
<div class="volume-index">
    
    <?= $this->render('_search', [
        'dataProvider' => $dataProvider,
    	'searchModel' => $searchModel,
    	'post_msg' => $post_msg
    ]) ?>

</div>
