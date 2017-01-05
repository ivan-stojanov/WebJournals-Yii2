<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$updateLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/upgrade-unregistered-user', 'token' => $user->helper_token]);
$reportLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/report-duplicate-email', 'token' => $user->helper_token]);
$contactLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/contact']);
?>
<div class="duplicate-user-report">
    <p>Dear <?= Html::encode($user->fullName) ?>,</p>

    <p>Someone just tried to register in the system, by using your <?= $type ?>.</p>
    
<?php if($user->is_unregistered_author) { ?>
    <p>If this was you, please create a password for your existing limited account and then update your account data. Create your password by clicking <?= Html::a(Html::encode("here"), $updateLink) ?>. The link is valid for next 24 hours.</p>
    <p>(HINT) Your username is: "<?= Html::encode($user->username) ?>".</p>
<?php } else { ?>
    <p>If this was you, please notify our administrators for violation of your email by clicking <?= Html::a(Html::encode("here"), $reportLink) ?>. The link is valid for next 24 hours.</p>
<?php } ?>
    <p>If this was not you, please ignore the email. If you got this email multile times, please report the case to our administrators by clicking <?= Html::a(Html::encode("here"), $contactLink) ?>.</p>
    
    <p>Regards,<br/><?= \Yii::$app->name ?> team.</p>
</div>
