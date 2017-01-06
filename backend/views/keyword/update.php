<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Keyword */

$this->title = 'Update Keyword';
?>
<div class="keyword-update">

    <?= $this->render('_form', [
        'modelKeyword' => $modelKeyword,
    	'post_msg' => $post_msg
    ]) ?>

</div>
