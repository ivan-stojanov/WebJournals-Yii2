<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$contactLink= Yii::$app->urlManager->createAbsoluteUrl(['site/contactus']);
$reportLink = Yii::$app->urlManager->createAbsoluteUrl(['site/report-duplicate-email', 'token' => $user->helper_token]);

?>
<div class="password-reset">
    <p>Dear <?= Html::encode($user->fullName) ?>,</p>

    <p>Someone just tried to register in the system, by using your <?= $type ?>.</p>
    
    <p>If this was not you, please ignore the email. If you got this email multile times, please report the case to our administrators by clciking <?= Html::a(Html::encode("here"), $contactLink) ?>.</p>
    
    <p>If this was you, please notify our administrators for violation of your email by clicking <?= Html::a(Html::encode("here"), $reportLink) ?>.</p>
    
    <p>Regards,<br/><?= \Yii::$app->name ?> team.</p>
</div>
