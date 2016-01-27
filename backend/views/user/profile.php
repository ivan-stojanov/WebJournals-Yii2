<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'My Profile ';
?>
<div class="user-update">

    <!-- <h1><?php /*echo Html::encode($this->title)*/ ?></h1> -->
    
    <?php if(isset($post_error_msg)){ ?>
        <div class="alert alert-dismissable alert-warning" id="homepage-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
		    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		    <strong><span id="homepage-section-alert-msg"></span><?php echo $post_error_msg; ?></strong>
		</div>
	<?php } ?>

    <?= $this->render('_form', [
        'model' => $model,
    	'common_vars' => $common_vars,
    	'additional_vars' => $additional_vars
    ]) ?>

</div>

    <hr style="color: black">    
 	<h3>Privacy Statement</h3>

    <div class="row" id="PrivacyStatement">   
    
        <div class="col-md-12">
        
        	<p>The names and email addresses entered in this journal site will be used exclusively for the stated purposes of this journal and will not be made available for any other purpose or to any other party.</p>
        
        </div>
        
     </div>