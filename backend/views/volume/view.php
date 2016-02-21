<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;

/* @var $this yii\web\View */
/* @var $model common\models\Volume */

$this->title = $model->title;
?>
<div class="volume-view">

    <h1><?php echo Html::encode($this->title) ?></h1>
    <hr>

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
            // 'volume_id',
            'title:ntext',
            'year',
            'created_on:datetime',
            'updated_on:datetime',
        	array(
        			'class' => DataColumn::className(), // this line is optional
        			'attribute' => 'is_deleted',
        			'value' => ($model->is_deleted == 0) ? "<div class='glyphicon glyphicon-remove'></div>" : "<div class='glyphicon glyphicon-ok'></div>",
        			'format' => 'HTML'
        	),
        ],
    ]) ?>

</div>
