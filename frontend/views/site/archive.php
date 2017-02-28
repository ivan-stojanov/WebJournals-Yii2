<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;

$this->title = 'Archive';
$this->params['breadcrumbs'][] = $this->title;

echo "<h2>Archive</h2>";
echo "<hr style='border-top: 3px dashed #eee;' />";

if($volumes_result != null && count($volumes_result)>0) {
	foreach ($volumes_result as $volume_index => $volume_item) {
		echo "<div class='row'>";
		$volume_issues = null;
		if($volume_item->issues != null)
			$volume_issues = $volume_item->issues;
		$volume_has_issue = false;
		if($volume_issues != null && count($volume_issues)>0) {						
			foreach ($volume_issues as $issue_index => $issue_item) {
				if($issue_item->is_current == 1) {
					continue;
				}
				$issue_sectons = null;
				if($issue_item->sections != null)
					$issue_sectons = $issue_item->sections;
				if($issue_sectons != null && count($issue_sectons)>0) {
					foreach ($issue_sectons as $section_index => $section_item) {						
						$secton_articles = null;
						if($section_item->publishedArticles != null)
							$secton_articles = $section_item->publishedArticles;
						if($secton_articles != null && count($secton_articles)>0) {													
							foreach ($secton_articles as $article_index => $article_item) {	
								if($issue_index == 0 && $section_index == 0 && $article_index == 0) {
									$volume_has_issue = true;
									$volumeLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/volume', 'id' => $volume_item->volume_id]);
									echo "<span class='volume-serach-section-result-volume'><a href='".$volumeLink."'>".$volume_item->searchVolumeTitle."</a></span>";
									echo "<br/>";
								}
								if($section_index == 0 && $article_index == 0) {
									$issueLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/issue', 'id' => $issue_item->issue_id]);
									echo "<span class='volume-serach-section-result-issue'><u></u> <a href='".$issueLink."'>".$issue_item->title."</a></span>";
									echo "<br/>";
								}
									$sectionLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/section', 'id' => $section_item->section_id]);
									echo "<span class='volume-serach-section-result-section'><a href='".$sectionLink."'>".$section_item->title."</a></span>";
									echo "<br/>";							
								$articleLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/article', 'id' => $article_item->article_id]);
								echo "<ul class='volume-serach-section-result-article'>";
								echo "	<li>";
								echo "		<span><a href='".$articleLink."'>".$article_item->title."</a></span>";
								echo "		<br>";
								echo "		<i>Authors: </i><span class='volume-serach-section-result-users'>".\common\models\ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['public_search']."</span>";
								echo "	</li>";
								echo "</ul>";
							}
						}
					}
				}
			}
		}
		echo "</div>";
		if($volume_index < (count($volumes_result)-1) && ($volume_has_issue == true)) {
			echo "<hr style='border-top: 3px dashed #eee;' />";
		}
	}
} else {
	echo "<div class='serach-section-empty-result'>No Volumes are found!</div>";
}
?>
