<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$keywordLink = Yii::$app->urlManagerBackEnd->createAbsoluteUrl(['keyword/view', 'id' => $keyword->keyword_id]);
?>
<div class="keyword-created-form">
    <p>Dear Admin,</p>

    <p>New "keyword" has been added to the site "<?= \Yii::$app->name ?>".</p>
    
    <p>Please check the keyword <?= Html::a(Html::encode("here"), $keywordLink) ?> and make sure it's well formed.</p>
    
    <p>These are the details related to the creator:</p>
    
    <p>
    	Creator name: <?= $keywordCreator->fullName ?><br>
    	Creator username: <?= $keywordCreator->username ?><br>
    	Creator email: <?= $keywordCreator->email ?><br>
    </p>
</div>
