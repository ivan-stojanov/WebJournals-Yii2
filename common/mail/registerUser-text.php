<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/verify-user', 'token' => $user->registration_token]);
$cancelLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/cancel-user', 'token' => $user->registration_token]);
?>
Dear <?= $user->fullName ?>,

An account has beed created using your email.

These are the details related to the user:

Username: <?= $user->username ?>

Email: <?= $user->email ?>

Full Name: <?= $user->fullName ?>

If this was you, please verify your account by clicking on the following link: <?= $verifyLink ?>.
    
If this was not you, please cancel the account by clicking on the following link: <?= $cancelLink ?>.

Regards,

<?= \Yii::$app->name ?> team.