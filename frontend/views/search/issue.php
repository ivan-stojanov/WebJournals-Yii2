<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;

$this->title = $modelIssue->title;
$this->params['breadcrumbs'][] = "Issue";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="search-page-details">
	<h2><?= $modelIssue->title; ?></h2>
	
	<hr class="hr-dotted">
	<div class="accordion-inner">
<?php 
	$have_articles = false;
	if($modelIssue->sections != null && count($modelIssue->sections)>0) {
		echo "<h4 class='size20'>Table of Contents</h4>";
		echo "<hr class='hr-dashed'>";
		echo "<h5 class='size18'>Sections</h5>";
		foreach ($modelIssue->sections as $section_index => $section_item) {					
			$sectionLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/section', 'id' => $section_item->section_id]);
			echo "<span class='search-page-details-nested-level-two'><a href='".$sectionLink."'>".$section_item->title."</a></span>";
			echo "<br/>";
			if($have_articles == false && $section_item->publishedArticles != null && count($section_item->publishedArticles)>0) {
				$have_articles = true;
			}
		}				
		if($have_articles == true) {
			echo "<hr class='hr-dashed'>";
			echo "<h5 class='size18'>Articles</h5>";
		}				
		foreach ($modelIssue->sections as $section_index => $section_item) {
			if($section_item->publishedArticles != null && count($section_item->publishedArticles)>0) {
				foreach ($section_item->publishedArticles as $article_index => $article_item) {
					$articleLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/article', 'id' => $article_item->article_id]);
					echo "<span class='search-page-details-nested-level-two'><a href='".$articleLink."'>".$article_item->title."</a></span>";
					echo "<br/>";
					echo "<span class='search-page-details-nested-level-three'><i>".\common\models\ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['public_search']."</i></span>";
					echo "<br/>";			
				}
			}
		}
		if($modelIssue->volume != null) {
			echo "<hr class='hr-dashed'>";
			echo "<h5 class='size18'>Volume</h5>";
			$volumeLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/volume', 'id' => $modelIssue->volume->volume_id]);
			echo "<span class='search-page-details-nested-level-two'><a href='".$volumeLink."'>".$modelIssue->volume->searchVolumeTitle."</a></span>";
			echo "<br/>";
		}
	}
?>
	</div>
	<hr class="hr-dotted">
</div>