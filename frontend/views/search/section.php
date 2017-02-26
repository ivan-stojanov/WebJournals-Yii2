<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;

$this->title = $modelSection->title;
$this->params['breadcrumbs'][] = "Section";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="search-page-details">
	<h2><?= $modelSection->title; ?></h2>
<?php 
	$have_articles = false;
	if($modelSection->publishedArticles != null && count($modelSection->publishedArticles)>0) {
		echo "<hr class='hr-dotted'>";
		echo "<div class='accordion-inner'>";
		echo "<h4 class='size20'>Table of Contents</h4>";
		echo "<hr class='hr-dashed'>";
		echo "<h5 class='size18'>Articles</h5>";				
		foreach ($modelSection->publishedArticles as $article_index => $article_item) {
			$articleLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/article', 'id' => $article_item->article_id]);
			echo "<span class='search-page-details-nested-level-two'><a href='".$articleLink."'>".$article_item->title."</a></span>";
			echo "<br/>";
			echo "<span class='search-page-details-nested-level-three'><i>".\common\models\ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['public_search']."</i></span>";
			echo "<br/>";			
		}
		if($modelSection->issue != null && $modelSection->issue->volume != null) {
			echo "<hr class='hr-dashed'>";
			echo "<h5 class='size18'>Volume</h5>";
			$volumeLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/volume', 'id' => $modelSection->issue->volume->volume_id]);
			echo "<span class='search-page-details-nested-level-two'><a href='".$volumeLink."'>".$modelSection->issue->volume->title."</a></span>";
			echo "<br/>";
		}
		if($modelSection->issue != null) {
			echo "<hr class='hr-dashed'>";
			echo "<h5 class='size18'>Issue</h5>";
			$issueLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/issue', 'id' => $modelSection->issue->issue_id]);
			echo "<span class='search-page-details-nested-level-two'><a href='".$issueLink."'>".$modelSection->issue->title."</a></span>";
			echo "<br/>";		
		}
	}
?>
	</div>
	<hr class="hr-dotted">
</div>