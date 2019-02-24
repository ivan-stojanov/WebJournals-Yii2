<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UnregisteredUser */
/* @var $form yii\widgets\ActiveForm */
\backend\assets\AppAsset::register($this);
$this->registerJsFile("@web/js/userScript.js", [ 'depends' => ['\yii\web\JqueryAsset'], 'position' => \yii\web\View::POS_END]);
?>

<?php if(isset($post_msg)){ ?>
    <div class="alert alert-dismissable <?php echo "alert-".$post_msg["type"];?>" id="user-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    <strong><span id="user-section-alert-msg"><?php echo $post_msg["text"]; ?></span></strong>
	</div>
<?php } ?>

<h1><?= Html::encode($this->title) ?></h1>
<hr>

<div class="row">

    <?php $form = ActiveForm::begin(['id' => 'UnregisteredUserProfileForm']); ?>
    
    	<div class="col-md-6">

    		<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    		<?= $form->field($model, 'email')->textInput(['maxlength' => true])->label('Email') ?>

			<?= $form->field($model, 'repeat_email') ?>
    		
    		<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    		<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    		<?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>

    		<?= $form->field($model, 'gender')->dropDownList($common_vars->gender_values, $additional_vars->gender_opt) ?>

    		<?= $form->field($model, 'initials')->textInput(['maxlength' => true]) ?>

		</div>
		
		<div class="col-md-6">		

    		<?= $form->field($model, 'mailing_address')->textarea(['rows' => 6]) ?>

    		<?= $form->field($model, 'country')->dropDownList($common_vars->country_values, $additional_vars->country_opt) ?>

		    <div class="form-group">		        
		    	<?php 
		    		echo Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'signup-button']);
			    	if($is_update == true && (Yii::$app->session->get('user.is_admin') == true)){
			    		echo "&nbsp;";
	        			echo Html::a('Register User', ['update', 'id' => $model->id], ['class' => 'btn btn-success']);
			    	}
		    	?>
		    </div>
		    
		</div>
		
    <?php ActiveForm::end(); ?>
    
	<hr>
</div>
