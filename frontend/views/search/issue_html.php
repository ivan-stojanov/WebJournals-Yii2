<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;

$this->title = $modelIssue->title;
$this->params['breadcrumbs'][] = "Issue";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="search-page-details">
		
<?php 
	$issueLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/issue', 'id' => $modelIssue->issue_id]);
	echo "<h2><a href='".$issueLink."'>".$modelIssue->title."</a></h2>";
	echo "<hr class='hr-double'>";
	echo "<div class='accordion-inner'>";
	if($modelIssue->sections != null && count($modelIssue->sections)>0) {
		foreach ($modelIssue->sections as $section_index => $section_item) {
			$sectionLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/section', 'id' => $section_item->section_id]);
			echo "<i><h5 class='size20'><a href='".$sectionLink."'>".$section_item->title."</a></h5></i>";
			if($section_item->publishedArticles != null && count($section_item->publishedArticles)>0) {
				echo "<div class='search-page-details-nested-level-one'>";
				echo "<hr class='hr-dashed'>";
				foreach ($section_item->publishedArticles as $article_index => $article_item) {					
					$articleLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/article', 'id' => $article_item->article_id]);
					echo "<b><h5 class='size20'><a href='".$articleLink."'>".$article_item->title."</a></h5></b>";
					echo "<span class='public-abstract search-page-details-nested-level-two'>";
					echo "	Author(s): <i>".\common\models\ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['public_search']."</i>";
					echo "</span>";
					echo "<br/>";
					echo "<hr class='hr-dotted'>";
					echo "<h4 class='size18'>Abstract</h4>";
					echo "<div class='public-abstract search-page-details-nested-level-two'>";
					echo $article_item->abstract;
					echo "</div>";
					echo "<hr class='hr-dotted'>";
					echo "<h4 class='size18'>Full Text</h4>";
					echo "<div class='public-abstract search-page-details-nested-level-two'>";
					echo $article_item->content;
					echo "</div>";
					echo "<hr class='hr-dotted'>";
					echo "<h4 class='size18'>Keyword(s)</h4>";
					echo "<div class='public-abstract search-page-details-nested-level-two'>";
					echo \common\models\ArticleKeyword::getKeywordsForArticleString($article_item->article_id)['public_search'];
					echo "</div>";
					echo "<hr class='hr-dotted'>";
					echo "<hr class='hr-dashed'>";
				}
				echo "</div>";
			}
			echo "<hr class='hr-double'>";
		}
	}
	echo "</div>";
?>
</div>