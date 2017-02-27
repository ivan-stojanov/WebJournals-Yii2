<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;

$this->title = $modelArticle->title;
$this->params['breadcrumbs'][] = "Article";
$this->params['breadcrumbs'][] = $this->title;

$articleLinkHTML = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/article', 'id' => $modelArticle->article_id, 'type' => 'html']);
$articleLinkPDF = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/article', 'id' => $modelArticle->article_id, 'type' => 'pdf']);
?>
<div class="search-page-details">
	<h2><?= $modelArticle->title; ?></h2>
	<span class="public-abstract search-page-details-nested-level-two">
		Author(s): <i><?= \common\models\ArticleAuthor::getAuthorsForArticleString($modelArticle->article_id)['public_search']?></i>
	</span>
	<br/>	
	<hr class="hr-dotted">
	<div class="accordion-inner">
		<h4 class="size20">Abstract</h4>
		<div class="public-abstract search-page-details-nested-level-two">
			<?= $modelArticle->abstract ?>
		</div>
		<hr class="hr-dashed">
		<h4 class="size20">Keyword(s)</h4>
		<div class="public-keyword search-page-details-nested-level-two">
			<i><?= \common\models\ArticleKeyword::getKeywordsForArticleString($modelArticle->article_id)['public_search']?></i>
		</div>		
		<hr class="hr-dashed">
		<h4 class="size20">Full Text</h4>
		<div class="public-full-text search-page-details-nested-level-two">
			<span><a href="<?= $articleLinkHTML ?>">HTML</a></span>&nbsp;&nbsp;
			<span><a href="<?= $articleLinkPDF ?>">PDF</a></span>
		</div>
<?php
	if($modelArticle->section != null) {
		if($modelArticle->section->issue != null) {
			if($modelArticle->section->issue->volume != null) {
				echo "<hr class='hr-dashed'>";
				echo "<h5 class='size18'>Volume</h5>";
				$volumeLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/volume', 'id' => $modelArticle->section->issue->volume->volume_id]);
				echo "<span class='search-page-details-nested-level-two'><a href='".$volumeLink."'>".$modelArticle->section->issue->volume->searchVolumeTitle."</a></span>";
				echo "<br/>";
			}
			echo "<hr class='hr-dashed'>";
			echo "<h5 class='size18'>Issue</h5>";
			$issueLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/issue', 'id' => $modelArticle->section->issue->issue_id]);
			echo "<span class='search-page-details-nested-level-two'><a href='".$issueLink."'>".$modelArticle->section->issue->title."</a></span>";
			echo "<br/>";
		}
		echo "<hr class='hr-dashed'>";
		echo "<h5 class='size18'>Section</h5>";
		$sectionLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/section', 'id' => $modelArticle->section->section_id]);
		echo "<span class='search-page-details-nested-level-two'><a href='".$sectionLink."'>".$modelArticle->section->title."</a></span>";
		echo "<br/>";
	}
?>
		
	</div>
	
	<hr class="hr-dotted">
</div>