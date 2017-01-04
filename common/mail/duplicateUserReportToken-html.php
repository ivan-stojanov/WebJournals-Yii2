<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$contactLink= Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/contact']);
$reportLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/report-duplicate-email', 'token' => $user->helper_token]);

?>
<div class="duplicate-user-report">
    <p>Dear <?= Html::encode($user->fullName) ?>,</p>

    <p>Someone just tried to register in the system, by using your <?= $type ?>.</p>
    
    <p>If this was not you, please ignore the email. If you got this email multile times, please report the case to our administrators by clicking <?= Html::a(Html::encode("here"), $contactLink) ?>.</p>
    
    <p>If this was you, please notify our administrators for violation of your email by clicking <?= Html::a(Html::encode("here"), $reportLink) ?>. The link is valid for next 24 hours.</p>
    
    <p>Regards,<br/><?= \Yii::$app->name ?> team.</p>
</div>
