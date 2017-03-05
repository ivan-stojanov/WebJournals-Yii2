<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$updateLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/upgrade-unregistered-user', 'token' => $user->helper_token]);
$reportLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/report-duplicate-email', 'token' => $user->helper_token]);
$contactLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/contact']);
?>
Dear <?= $user->fullName ?>,

Someone just tried to register in the system, by using your <?= $type ?>.

<?php if($user->is_unregistered_author) { ?>
If this was you, please create a password for your existing limited account and then update your account data. Create your password by clicking on the following link: <?= $updateLink ?>. The link is valid for next 24 hours.</p>
(HINT) Your username is: "<?= $user->username ?>".
<?php } else { ?>
If this was you, please notify our administrators for violation of your email by clicking on the following link: <?= $reportLink ?>. The link is valid for next 24 hours.</p>
<?php } ?>

If this was not you, please ignore the email. If you got this email multile times, please report the case to our administrators by clicking on the following link: <?= $contactLink ?>.

Regards,

<?= \Yii::$app->name ?> team.