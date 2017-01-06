<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Keyword */

$this->title = 'Create Keyword';
?>
<div class="keyword-create">

    <?= $this->render('_form', [
        'modelKeyword' => $modelKeyword,
    	'post_msg' => $post_msg
    ]) ?>

</div>
