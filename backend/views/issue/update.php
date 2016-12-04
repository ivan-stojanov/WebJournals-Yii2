<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Issue */

$this->title = 'Update Issue';
?>
<div class="issue-update">

    <?= $this->render('_form', [
        'modelIssue' => $modelIssue,
    	'modelsSection' => $modelsSection,
    	'modelUser' => $modelUser,
    	'post_msg' => $post_msg
    ]) ?>

</div>
