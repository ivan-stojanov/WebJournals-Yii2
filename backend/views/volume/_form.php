<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model common\models\Volume */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if(isset($post_msg)){ ?>
    <div class="alert alert-dismissable <?php echo "alert-".$post_msg["type"];?>" id="homepage-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    <strong><span id="homepage-section-alert-msg"></span><?php echo $post_msg["text"]; ?></strong>
	</div>
<?php } ?>

<h1><?= Html::encode($this->title) ?></h1>
<hr>

<div class="volume-form">

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'validateOnChange' => true,
        'validateOnBlur' => false,
        'options' => [
            'enctype' => 'multipart/form-data',
            'id' => 'dynamic-form'
        ]
    ]); ?>

    <?= $form->field($modelVolume, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelVolume, 'year')->textInput(['maxlength' => true]) ?>
    
    <?= $this->render('_form_issues', [
        'form' => $form,
        'modelVolume' => $modelVolume,
    	'modelsIssue' => $modelsIssue
    ]) ?>

    <div class="form-group">
		<?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
	</div>

    <?php ActiveForm::end(); ?>

</div>
