<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */
$reviewLink = Yii::$app->urlManagerBackEnd->createAbsoluteUrl(['articlereviewer/view', 'id' => $modelArticleReviewer->article_id]);
?>
Dear <?= $modelArticleReviewer->reviewer->fullName ?>,

This is a notification from the site "<?= \Yii::$app->name ?>".
    
An article with the title '<?= $modelArticleReviewer->article->title ?>' has been improved and was sent for review again.
    
You are set as one of the reviewer(s). You can see the article details and update your review by clicking by clicking on the following link: <?= $reviewLink ?>.

Regards,

<?= \Yii::$app->name ?> team.