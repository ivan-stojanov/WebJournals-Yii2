<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Issue */

$this->title = 'Create Issue';
?>
<div class="issue-create">

    <?= $this->render('_form', [
        'modelIssue' => $modelIssue,
    	'modelsSection' => $modelsSection,
    	'post_msg' => $post_msg
    ]) ?>

</div>
