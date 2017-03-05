<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$keywordLink = Yii::$app->urlManagerBackEnd->createAbsoluteUrl(['keyword/view', 'id' => $keyword->keyword_id]);
?>
Dear Admin,

New "keyword" has been added to the site "<?= \Yii::$app->name ?>".
    
Please check the keyword by clicking on the following link: <?= $keywordLink ?> and make sure it's well formed.
    
hese are the details related to the creator:

Creator name: <?= $keywordCreator->fullName ?>

Creator username: <?= $keywordCreator->username ?>

Creator email: <?= $keywordCreator->email ?>