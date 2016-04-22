<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use wbraganca\dynamicform\DynamicFormWidget;
use common\models\Volume;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Issue */
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

<div class="issue-form">

    <?php $form = ActiveForm::begin([
     /*   'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'validateOnChange' => true,
        'validateOnBlur' => false,*/
        'options' => [
            'enctype' => 'multipart/form-data',
            'id' => 'dynamic-form'
        ]
    ]); ?>    
    
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'volume_id')->dropDownList(
    		ArrayHelper::map(Volume::find()->all(), 'volume_id', 'title'),
    		['prompt' => 'Select Volume']
    ) ?>

    <?= $form->field($model, 'published_on')->widget(
    		DatePicker::className(), [
    			// inline too, not bad
    			'inline' => false,
    			// modify template for custom rendering
    			//'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
    			'clientOptions' => [
    				'autoclose' => true,
    				'format' => 'dd-M-yyyy HH:ii P'
    			]
    		]
    ) ?>    
    
    <?= $form->field($model, 'cover_image')->textInput() ?>

    <?= $form->field($model, 'is_special_issue')->textInput() ?>

    <?= $form->field($model, 'special_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'special_editor')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
