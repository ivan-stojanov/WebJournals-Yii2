<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Section */

$this->title = $model->title;
?>
<div class="section-view">

    <h2><i>Section: </i><?php echo Html::encode($this->title) ?></h2>
    <hr>

    <p>
        <?= Html::a('Update Section', ['update', 'id' => $model->section_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete Section', ['delete', 'id' => $model->section_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
		 <?= Html::a('View Volume', ['volume/view', 'id' => $model->issue->volume_id], ['class' => 'btn btn-default']) ?>
		 <?= Html::a('View Issue', ['issue/view', 'id' => $model->issue_id], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'section_id',
            'issue_id',
            'title:ntext',
            'sort_in_issue',
            'created_on',
            'updated_on',
            'is_deleted',
        ],
    ]) ?>

</div>
