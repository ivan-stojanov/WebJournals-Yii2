<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
//use yii\bootstrap\ActiveField;

\frontend\assets\AppAsset::register($this);
$this->registerJsFile("@web/js/userScript.js", [ 'depends' => ['\yii\web\JqueryAsset'], 'position' => \yii\web\View::POS_END]);

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-signup">

	<?php if(isset($post_msg)){ ?>
	    <div class="alert alert-dismissable <?php echo "alert-".$post_msg["type"];?>" id="user-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
		    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		    <strong><span id="user-section-alert-msg"><?php echo $post_msg["text"]; ?></span></strong>
		</div>
	<?php } ?>

    <h2><?= Html::encode($this->title) ?></h2>
    <hr>

    <p>Fill in this form to register with this site.</p>
    
    <p><?= Html::a('Click here', ['/site/login']) ?> if you are already registered with this or another journal on this site.</p>

	<h3>Profile</h3>

    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        	<div class="col-md-6">
        	
				<?= $form->field($model, 'username') ?>
				
				<?= $form->field($model, 'password')->passwordInput() ?>
				
				<?= $form->field($model, 'repeat_password')->passwordInput() ?>
				
				<?= $form->field($model, 'salutation') ?>
				
				<?= $form->field($model, 'first_name') ?>
				
				<?= $form->field($model, 'middle_name') ?>
                
                <?= $form->field($model, 'last_name') ?>  
                
 				<?= $form->field($model, 'initials')->label('Initials (e.g. Joan Alice Smith = JAS)') ?>
				
				<?= $form->field($model, 'gender')->dropDownList($common_vars->gender_values, $model->gender_opt) ?>
                
                <?= $form->field($model, 'affiliation')->textArea(['rows' => 3])->label('Affiliation (Your institution, e.g. "Simon Fraser University")') ?>    
                
                <?= $form->field($model, 'signature')->textArea(['rows' => 3]) ?> 
                    		
        		<?= $form->field($model, 'bio_statement')->textArea(['rows' => 3]) ?>         
                            
        	</div>
        	
        	<div class="col-md-6">
        	
        		<?= $form->field($model, 'email')->label('Email') ?>
        		
        		<?= $form->field($model, 'repeat_email') ?>
        		
        		<?= $form->field($model, 'orcid_id') ?>        		      		
        		<?= Html::label('ORCID iDs can only be assigned by the '.Html::a('ORCID Registry', 'http://orcid.org/', ['target'=>'_blank']).'. You must conform to their standards for expressing ORCID iDs, and include the full URI (eg. http://orcid.org/0000-0002-1825-0097).', null, ['style' => 'font-weight:normal;margin-top:-15px']) ?>
        		
        		<?= $form->field($model, 'url') ?>
        		
        		<?= $form->field($model, 'phone') ?>
        		
        		<?= $form->field($model, 'fax') ?>
        		
        		<?= $form->field($model, 'mailing_address')->textArea(['rows' => 3]) ?> 
  		
        		<?= $form->field($model, 'country')->dropDownList($common_vars->country_values, $model->country_opt) ?>
        		
        		<?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?> 

				<?= $form->field($model, 'send_confirmation')->checkbox()->label(false)->hiddenInput() ?>
				
				<?= $form->field($model, 'is_reader')->checkbox()->label(false)->hiddenInput() ?>
				
				<?= $form->field($model, 'is_author')->checkbox() ?>
				
				<?= $form->field($model, 'is_reviewer')->checkbox() ?>
				
				<?php /*echo $form->field($model, 'reviewer_interests')->label()*/ ?>
    	
	        	<div class="form-group">
	            	<?= Html::submitButton('Register', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
	            </div>
                                
        	</div>       	

        <?php ActiveForm::end(); ?>
        
        <hr>       

    </div>
    <hr style="color: black">    
 	<!--<h3>Privacy Statement</h3>

    <div class="row" id="PrivacyStatement">   
    
        <div class="col-md-12">
        
        	<p>The names and email addresses entered in this journal site will be used exclusively for the stated purposes of this journal and will not be made available for any other purpose or to any other party.</p>
        
        </div>
        
     </div>
     -->
</div>
