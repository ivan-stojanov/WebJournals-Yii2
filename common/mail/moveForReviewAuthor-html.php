<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
$articleLink = Yii::$app->urlManagerBackEnd->createAbsoluteUrl(['article/view', 'id' => $modelArticleAuthor->article_id]);
?>
<div class="moveforreviewreviewer-form">
    <p>Dear <?= Html::encode($modelArticleAuthor->author->fullName) ?>,</p>

    <p>This is a notification from the site "<?= \Yii::$app->name ?>".</p>
    
    <p>Your article with the title '<?= $modelArticleAuthor->article->title ?>' has been sent for review. While the article is 'under review' status, changes to it can not be made.</p>
    
    <p>You are set as correspondent author. Please see the article details and track it's status by clicking <?= Html::a(Html::encode("here"), $articleLink) ?>.</p>

	<p>Regards,<br/><?= \Yii::$app->name ?> team.</p>
</div>
