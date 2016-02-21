<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Volume */

$this->title = $modelVolume->title;
?>
<div class="volume-update">

    <?= $this->render('_form', [
        'modelVolume' => $modelVolume,
    ]) ?>

</div>
