<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Volume */

$this->title = 'Update Volume: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Volumes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->volume_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="volume-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
