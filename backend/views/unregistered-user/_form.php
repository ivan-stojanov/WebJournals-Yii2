<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UnregisteredUser */
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

<div class="row">

    <?php $form = ActiveForm::begin(['id' => 'UnregisteredUserProfileForm']); ?>
    
    	<div class="col-md-6">

    		<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    		<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

			<?= $form->field($model, 'repeat_email') ?>
    		
    		<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    		<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    		<?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>

    		<?= $form->field($model, 'gender')->dropDownList($common_vars->gender_values, $additional_vars->gender_opt) ?>

    		<?= $form->field($model, 'initials')->textInput(['maxlength' => true]) ?>

		</div>
		
		<div class="col-md-6">
		
			<?= $form->field($model, 'affiliation')->textarea(['rows' => 6]) ?>		

    		<?= $form->field($model, 'mailing_address')->textarea(['rows' => 6]) ?>

    		<?= $form->field($model, 'country')->dropDownList($common_vars->country_values, $additional_vars->country_opt) ?>

		    <div class="form-group">
		        <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
		    </div>
		    
		</div>
		
    <?php ActiveForm::end(); ?>
    
	<hr>
</div>
