<?php

use common\models\Volume;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use wbraganca\dynamicform\DynamicFormWidget;
use dosamigos\datetimepicker\DateTimePicker;
use kartik\file\FileInput;

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
    
    <?= $form->field($modelIssue, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelIssue, 'volume_id')->dropDownList(
    		ArrayHelper::map(Volume::find()->all(), 'volume_id', 'title'),
    		['prompt' => 'Select Volume']
    ) ?>

	<?= $form->field($modelIssue, 'published_on')->widget(DateTimePicker::classname(), [
        'size' => 'ms',
    	'template' => '{input}',
    	'pickButtonIcon' => 'glyphicon glyphicon-time',
        'inline' => false,    		
        'clientOptions' => [
			'autoclose' => true,
			'format' 	=> 'M d, yyyy, H:ii:ss P',
			'todayBtn'  => true,
        ]
    ]) ?>
 
    <?php //echo $form->field($model, 'cover_image')->textInput() ?>
	<?php	                            
		$issueImagesPath = Yii::$app->urlManagerCommon->createUrl('images/issues/cover.jpg');
		$initialPreview = [];
		if(isset($modelIssue->cover_image) && ($modelIssue->cover_image > 0) && isset($modelIssue->coverimage)){
			$modelImage = $modelIssue->coverimage;
		                            
			if ($modelImage) {	                        		
				$issueImagesPath = Yii::$app->urlManagerCommon->createUrl('images/issues') . DIRECTORY_SEPARATOR . $modelIssue->volume->volume_id . DIRECTORY_SEPARATOR;
				$issueImagesPath = $issueImagesPath . $modelImage->path;
			}
		}
		$initialPreview[] = Html::img($issueImagesPath, ['class' => 'file-preview-image', 'type' => 'file']);
	?>
    <?= $form->field($modelIssue, "cover_image")->widget(FileInput::classname(), [
    	'options' => [
        'multiple' => false,
        'accept' => 'image/*',
        'class' => 'optionvalue-img'	                            	
    ],
    	'pluginOptions' => [
        'previewFileType' => 'image',
        'showCaption' => false,
        'showUpload' => false,
        'browseClass' => 'btn btn-default btn-sm btn-brw-custom',
        'browseLabel' => ' Pick img',
        'browseIcon' => '<i class="glyphicon glyphicon-picture"></i>',
        'removeClass' => 'btn btn-danger btn-sm btn-rmv-custom',
        'removeLabel' => ' Delete',
        'removeIcon' => '<i class="fa fa-trash"></i>',
        'previewSettings' => [
        	'image' => ['width' => '138px', 'height' => 'auto']
        ],
            'initialPreview' => $initialPreview,
            'layoutTemplates' => ['footer' => '']
        ]
    ]) ?>
	
	<?php 
		$is_special_issue_flag = false;
		if(isset($modelIssue) && isset($modelIssue->is_special_issue) && ($modelIssue->is_special_issue)){
			$is_special_issue_flag = true;
		}
	?>
	
	<label class="control-label" for="issue-is_special_issue">Is special issue</label>
	<br>
	<label class="switch">
	 	<input type="checkbox" id="issue-is_special_issue" name="Issue[is_special_issue]" 
	 		<?php echo (($is_special_issue_flag) ? 'checked' : '')?> value="<?php echo $is_special_issue_flag ?>">
		<span></span>
	</label>
	
	<div class="special-issue-div<?php echo ((!$is_special_issue_flag) ? ' hidden-div' : '') ?>">
		<?= $form->field($modelIssue, 'special_title')->textInput(['maxlength' => true]) ?>
	
	    <?= $form->field($modelIssue, 'special_editor')->textInput(['maxlength' => true]) ?>
	</div>
	
	<?= $this->render('_form_sections', [
        'form' => $form,
    	'modelIssue' => $modelIssue,
		'modelsSection' => $modelsSection
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
