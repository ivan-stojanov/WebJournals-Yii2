<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Create User';
?>
<div class="user-create">

    <?= $this->render('_form', [
        'model' => $model,
    	'common_vars' => $common_vars,
    	'additional_vars' => $additional_vars,
    	'post_msg' => $post_msg
    ]) ?>

</div>

    <hr style="color: black">    
 	<!-- <h3>Privacy Statement</h3>

    <div class="row" id="PrivacyStatement">   
    
        <div class="col-md-12">
        
        	<p>The names and email addresses entered in this journal site will be used exclusively for the stated purposes of this journal and will not be made available for any other purpose or to any other party.</p>
        
        </div>
        
     </div>
     -->