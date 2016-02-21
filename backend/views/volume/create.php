<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Volume */

$this->title = 'Create Volume';
$this->params['breadcrumbs'][] = ['label' => 'Volumes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="volume-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
