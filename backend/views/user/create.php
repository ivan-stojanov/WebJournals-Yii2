<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Create User';
?>
<div class="user-create">

    <?= $this->render('_form', [
        'model' => $model,
    	'common_vars' => $common_vars,
    	'additional_vars' => $additional_vars,
    	'post_msg' => $post_msg
    ]) ?>

</div>
