<?php
use common\models\ArticleReviewer;

/* @var $this yii\web\View */
/* @var $user common\models\User */
$articleLink = Yii::$app->urlManagerBackEnd->createAbsoluteUrl(['article/view', 'id' => $modelArticleAuthor->article_id]);
?>
Dear <?= $modelArticleAuthor->author->fullName ?>,

This is a notification from the site "<?= \Yii::$app->name ?>".
    
Your article with the title '<?= $modelArticleAuthor->article->title ?>' has been accepted for publication. Only editors/admins can do the changes to it now (before publishing).
 
Editor's action: <?= ArticleReviewer::$STATUS_REVIEW[$modelArticleReviewer->short_comment] ?>

Editor's comment: <?= $modelArticleReviewer->long_comment ?>

Editor's email: <?= $modelArticleReviewer->reviewer->email ?>

You are set as correspondent author. Please see the article details and track it's status on the following link: <?= $articleLink ?>.

Regards,
<?= \Yii::$app->name ?> team.