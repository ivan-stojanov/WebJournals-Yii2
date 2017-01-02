<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UnregisteredUser */

$this->title = 'Update Unregistered User';
?>
<div class="unregistered-user-update">

    <?= $this->render('_form_unregisteredauthor', [
        'model' => $model,
    	'common_vars' => $common_vars,
    	'additional_vars' => $additional_vars,
    	'post_msg' => $post_msg,
    	'is_update' => true
    ]) ?>

</div>

    <hr style="color: black">    
 	<h3>Privacy Statement</h3>

    <div class="row" id="PrivacyStatement">   
    
        <div class="col-md-12">
        
        	<p>The names and email addresses entered in this journal site will be used exclusively for the stated purposes of this journal and will not be made available for any other purpose or to any other party.</p>
        
        </div>
        
     </div>