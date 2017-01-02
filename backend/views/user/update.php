<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $model common\models\User */

if(isset(Yii::$app->user->identity) && isset(Yii::$app->user->identity->attributes["id"]) &&
		isset($current_id) && (Yii::$app->user->identity->attributes["id"] == $current_id))
{
	$this->title = 'My Profile ';
} else {
	if($is_unregistered_author != null && $is_unregistered_author == true){
		$this->title = 'Register User';
	} else {
		$this->title = 'Update User';
	}	
}
?>
<div class="user-update">

    <!-- <h1><?php /*echo Html::encode($this->title)*/ ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    	'common_vars' => $common_vars,
    	'additional_vars' => $additional_vars,
    	'post_msg' => $post_msg
    ]) ?>

</div>

    <hr style="color: black">    
 	<h3>Privacy Statement</h3>

    <div class="row" id="PrivacyStatement">   
    
        <div class="col-md-12">
        
        	<p>The names and email addresses entered in this journal site will be used exclusively for the stated purposes of this journal and will not be made available for any other purpose or to any other party.</p>
        
        </div>
        
     </div>