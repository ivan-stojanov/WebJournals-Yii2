<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'My Profile ';
?>
<div class="user-update">

    <!-- <h1><?php /*echo Html::encode($this->title)*/ ?></h1> -->

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