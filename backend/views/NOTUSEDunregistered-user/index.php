<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UnregisteredUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Unregistered Users';
?>

<div class="unregistered-user-index">

    <?= $this->render('_search', [
        'dataProvider' => $dataProvider,
    	'searchModel' => $searchModel,
    	'post_msg' => $post_msg
    ]) ?>

</div>
