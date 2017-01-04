<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */
?>
Dear Admin,

Report for violation of the user email on the site "<?= \Yii::$app->name ?>" has beed submitted.

These are the details related to the submitted message:

User ID: <?= $user->id ?>
Username: <?= $user->username ?>
Email: <?= $user->email ?>
Full Name: <?= $user->fullName ?>