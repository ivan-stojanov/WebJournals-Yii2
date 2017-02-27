<?php
if($issues_result != null && count($issues_result)>0) {
	foreach ($issues_result as $issue_index => $issue_item) {
		$issueLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/issue', 'id' => $issue_item->issue_id]);
		echo "<div class='row'>";
		echo "<span class='issue-serach-section-result-issue'><a href='".$issueLink."'>".$issue_item->title."</a></span>";
		echo "<br/>";
		$show_details = false;
		if($params_GET != null && $params_GET->getQueryParam('details') != null && $params_GET->getQueryParam('details') == '1')
			$show_details = true;
		$issue_sectons = null;
		if($issue_item->sections != null)
			$issue_sectons = $issue_item->sections;
		if($issue_sectons != null && count($issue_sectons)>0 && $show_details) {
			foreach ($issue_sectons as $section_index => $section_item) {
				$sectionLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/section', 'id' => $section_item->section_id]);
				echo "<span class='issue-serach-section-result-section'><a href='".$sectionLink."'>".$section_item->title."</a></span>";
				echo "<br/>";
				$secton_articles = null;
				if($section_item->publishedArticles != null)
					$secton_articles = $section_item->publishedArticles;
				if($secton_articles != null && count($secton_articles)>0) {
					foreach ($secton_articles as $article_index => $article_item) {
						$articleLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/article', 'id' => $article_item->article_id]);
						echo "<ul class='issue-serach-section-result-article'>";
						echo "	<li>";
						echo "		<span><a href='".$articleLink."'>".$article_item->title."</a></span>";
						echo "		<br>";
						echo "		<i>Authors: </i><span class='issue-serach-section-result-users'>".\common\models\ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['public_search']."</span>";
						echo "	</li>";
						echo "</ul>";
					}
				}
			}
		}
		$have_volume = false;
		if($issue_item != null && $issue_item->volume != null) {
			$have_volume = true;
		}
		if($have_volume == true && $show_details) {
			$volumeLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/volume', 'id' => $issue_item->volume->volume_id]);
			echo "<span class='issue-serach-section-result-volume'><u><i>Volume:</i></u> <a href='".$volumeLink."'>".$issue_item->volume->searchVolumeTitle."</a></span>";
			echo "<br/>";
		}
		echo "</div>";
		if($issue_index < (count($issues_result)-1)) {
			echo "<hr style='border-top: 3px dashed #eee;' />";
		}
	}
} else {
	echo "<div class='serach-section-empty-result'>No Issues are found!</div>";
}
?>