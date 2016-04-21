<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Issue */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="issue-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'volume_id')->textInput() ?>

    <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'published_on')->textInput() ?>

    <?= $form->field($model, 'is_special_issue')->textInput() ?>

    <?= $form->field($model, 'special_title')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'special_editor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cover_image')->textInput() ?>

    <?= $form->field($model, 'created_on')->textInput() ?>

    <?= $form->field($model, 'updated_on')->textInput() ?>

    <?= $form->field($model, 'is_deleted')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
