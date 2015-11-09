<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model common\models\Announcement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="announcement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
    
    <?php echo $form->field($model, 'content')->widget(TinyMce::className(), [
	    'options' => ['rows' => 15],
	    'language' => 'en_GB',
	    'clientOptions' => [
			'theme' => "modern",
		    'plugins' => [
		        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
		        "searchreplace wordcount visualblocks visualchars code fullscreen",
		        "insertdatetime media nonbreaking save table contextmenu directionality",
		        "emoticons template paste textcolor colorpicker textpattern imagetools"
		    ],
		    'toolbar1' => "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
		    'toolbar2' => "print preview media | forecolor backcolor emoticons",
		    'image_advtab' => true,			    
	    ]
	]);?>

    <div class="form-group">
        <?php echo Html::a('Back', ['announcement/index'], ['class' => 'btn btn-success']) ?>
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
