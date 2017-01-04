<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$contactLink= Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/contact']);
$reportLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/report-duplicate-email', 'token' => $user->helper_token]);
?>
Dear <?= $user->fullName ?>,

Someone just tried to register in the system, by using your <?= $type ?>.

If this was not you, please ignore the email. If you got this email multile times, please report the case to our administrators by clicking on the following link: <?= $reportLink ?>.

If this was you, please notify our administrators for violation of your email by clicking on the following link: <?= $contactLink ?>. . The link is valid for next 24 hours.

Regards,
<?= \Yii::$app->name ?> team.