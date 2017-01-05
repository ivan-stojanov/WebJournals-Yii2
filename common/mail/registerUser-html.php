<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/verify-user', 'token' => $user->registration_token]);
$cancelLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/cancel-user', 'token' => $user->registration_token]);
?>
<div class="register-user">
    <p>Dear <?= Html::encode($user->fullName) ?>,</p>

    <p>An account has beed created using your email.</p>
    
    <p>These are the details related to the user:</p>
    
    <p>
    	Username: <?= $user->username ?><br>
    	Email: <?= $user->email ?><br>
    	Full Name: <?= $user->fullName ?>
    </p>
    
    <p>If this was you, please verify your account by clicking <?= Html::a(Html::encode("here"), $verifyLink) ?>.</p>
    
    <p>If this was not you, please cancel the account by clicking <?= Html::a(Html::encode("here"), $cancelLink) ?>.</p>
    
    <p>Regards,<br/><?= \Yii::$app->name ?> team.</p>
</div>
