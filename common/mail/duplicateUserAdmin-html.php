<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
?>
<div class="duplicate-user-report">
    <p>Dear Admin,</p>

    <p>Report for violation of the user email on the site "<?= \Yii::$app->name ?>" has beed submitted.</p>
    
    <p>These are the details related to the user:</p>
    
    <p>
    	User ID: <?= $user->id ?><br>
    	Username: <?= $user->username ?><br>
    	Email: <?= $user->email ?><br>
    	Full Name: <?= $user->fullName ?><br>
    </p>
</div>
