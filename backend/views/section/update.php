<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Volume */

$this->title = 'Update Section';
?>
<div class="section-update">

    <?= $this->render('_form', [
        'modelSection' => $modelSection,
    	'modelsArticle' => $modelsArticle,    		
    	'post_msg' => $post_msg
    ]) ?>   

</div>