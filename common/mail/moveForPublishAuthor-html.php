<?php
use yii\helpers\Html;
use common\models\ArticleReviewer;

/* @var $this yii\web\View */
/* @var $user common\models\User */
$articleLink = Yii::$app->urlManagerBackEnd->createAbsoluteUrl(['article/view', 'id' => $modelArticleAuthor->article_id]);
?>
<div class="moveforpublished-form">
    <p>Dear <?= Html::encode($modelArticleAuthor->author->fullName) ?>,</p>

    <p>This is a notification from the site "<?= \Yii::$app->name ?>".</p>
    
    <p>Your article with the title '<?= $modelArticleAuthor->article->title ?>' has been successfully published.</p>
    
    <p>You are set as correspondent author. You can see the article details and track it's status by clicking <?= Html::a(Html::encode("here"), $articleLink) ?>.</p>

	<p>Regards,<br/><?= \Yii::$app->name ?> team.</p>
</div>
