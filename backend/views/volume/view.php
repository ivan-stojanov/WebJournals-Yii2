<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Volume */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Volumes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="volume-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->volume_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->volume_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'volume_id',
            'title:ntext',
            'year',
            'created_on',
            'updated_on',
            'is_deleted',
        ],
    ]) ?>

</div>
