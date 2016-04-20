<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Volume */

$this->title = 'Create Volume';
?>
<div class="volume-create">

    <?= $this->render('_form', [
        'modelVolume' => $modelVolume,
    	'modelsIssue' => $modelsIssue,    		
    	'post_msg' => $post_msg
    ]) ?>    

</div>
