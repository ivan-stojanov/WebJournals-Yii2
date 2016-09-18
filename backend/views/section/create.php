<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Section */

$this->title = 'Create Section';
?>
<div class="section-create">

    <?= $this->render('_form', [
        'modelSection' => $modelSection,
    	'modelsArticle' => $modelsArticle,    		
    	'post_msg' => $post_msg
    ]) ?>  

</div>
