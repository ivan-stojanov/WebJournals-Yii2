<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
    
<?php if(isset($post_msg)){ ?>
    <div class="alert alert-dismissable <?php echo "alert-".$post_msg["type"];?>" id="homepage-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    <strong><span id="homepage-section-alert-msg"></span><?php echo $post_msg["text"]; ?></strong>
	</div>
<?php } ?>

<div class="row">

    <?php $form = ActiveForm::begin(['id' => 'UserProfileForm']); ?>
    
       	<div class="col-md-6">
        	
			<?= $form->field($model, 'username') ?>
		
			<?= $form->field($model, 'password')->passwordInput() ?>
				
			<?= $form->field($model, 'repeat_password')->passwordInput() ?>
				
			<?= $form->field($model, 'salutation') ?>
				
			<?= $form->field($model, 'first_name') ?>
				
			<?= $form->field($model, 'middle_name') ?>
                
            <?= $form->field($model, 'last_name') ?>  
               
 			<?= $form->field($model, 'initials')->label('Initials (e.g. Joan Alice Smith = JAS)') ?>
				
			<?= $form->field($model, 'gender')->dropDownList($common_vars->gender_values, $additional_vars->gender_opt) ?>
                
            <?= $form->field($model, 'affiliation')->textArea(['rows' => 3])->label('Affiliation (Your institution, e.g. "Simon Fraser University")') ?>    
                
            <?= $form->field($model, 'signature')->textArea(['rows' => 3]) ?> 
                    		
        	<?= $form->field($model, 'bio_statement')->textArea(['rows' => 3]) ?>         
                            
       	</div>
        	
       	<div class="col-md-6">
        	
       		<?= $form->field($model, 'email')->label('Email (see '.Html::a('Privacy Statement', '#PrivacyStatement').')') ?>
        		
       		<?= $form->field($model, 'repeat_email') ?>
        		
       		<?= $form->field($model, 'orcid_id') ?>        		      		
       		<?= Html::label('ORCID iDs can only be assigned by the '.Html::a('ORCID Registry', 'http://orcid.org/', ['target'=>'_blank']).'. You must conform to their standards for expressing ORCID iDs, and include the full URI (eg. http://orcid.org/0000-0002-1825-0097).', null, ['style' => 'font-weight:normal;margin-top:-15px']) ?>
        		
       		<?= $form->field($model, 'url') ?>
      		
       		<?= $form->field($model, 'phone') ?>
        		
       		<?= $form->field($model, 'fax') ?>
        		
       		<?= $form->field($model, 'mailing_address')->textArea(['rows' => 3]) ?> 
  		
      		<?= $form->field($model, 'country')->dropDownList($common_vars->country_values, $additional_vars->country_opt) ?>		 
       		
      		<?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
      			'captchaAction'=>'user/captcha'
            ]) ?> 

			<?= $form->field($model, 'send_confirmation')->checkbox() ?>
				
			<?= $form->field($model, 'is_reader')->checkbox() ?>
				
			<?= $form->field($model, 'is_author')->checkbox() ?>
				
			<?= $form->field($model, 'is_reviewer')->checkbox() ?>			
			
			<?php if (Yii::$app->session->get('user.is_editor') == true || Yii::$app->session->get('user.is_admin') == true){ ?>
				<?= $form->field($model, 'is_editor')->checkbox() ?>
			<?php } ?>
			
			<?php if (Yii::$app->session->get('user.is_admin') == true){ ?>
				<?= $form->field($model, 'is_admin')->checkbox() ?>
			<?php } ?>
				
			<?= $form->field($model, 'reviewer_interests')->label() ?>
			
			<input type="hidden" name="type" value="type"/>
    	
	       	<div class="form-group">
	           	<?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
	        </div>
                                
       	</div>       	

    <?php ActiveForm::end(); ?>
    
    <hr>       
</div>