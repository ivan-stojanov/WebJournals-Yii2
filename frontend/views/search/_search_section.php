<?php
if($sections_result != null && count($sections_result)>0) {
	foreach ($sections_result as $section_index => $section_item) {
		$sectionLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/section', 'id' => $section_item->section_id]);
		echo "<div class='row'>";
		echo "<span class='section-serach-section-result-section'><a href='".$sectionLink."'>".$section_item->title."</a></span>";
		echo "<br/>";
		$show_details = false;
		if($params_GET != null && $params_GET->getQueryParam('details') != null && $params_GET->getQueryParam('details') == '1')
			$show_details = true;
		$secton_articles = null;
		if($section_item->publishedArticles != null)
			$secton_articles = $section_item->publishedArticles;
		if($secton_articles != null && count($secton_articles)>0 && $show_details) {
			foreach ($secton_articles as $article_index => $article_item) {
				$articleLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/article', 'id' => $article_item->article_id]);
				echo "<ul class='section-serach-section-result-article'>";
				echo "	<li>";
				echo "		<span><a href='".$articleLink."'>".$article_item->title."</a></span>";
				echo "		<br>";
				$authors_list = \common\models\ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['public_search'];
				if(isset($authors_list) && strlen($authors_list) > 0) {
					echo "		<i>Authors: </i><span class='section-serach-section-result-users'>".$authors_list."</span>";
				}				
				echo "	</li>";
				echo "</ul>";
			}
		}
		$have_issue = false;
		$have_volume = false;
		if($section_item != null && $section_item->issue != null) {
			$have_issue = true;
			if($section_item->issue->volume != null){				
				$have_volume = true;
			}
		}
		if($have_volume == true && $show_details) {
			$volumeLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/volume', 'id' => $section_item->issue->volume->volume_id]);
			echo "<span class='section-serach-section-result-volume'><u><i>Volume:</i></u> <a href='".$volumeLink."'>".$section_item->issue->volume->searchVolumeTitle."</a></span>";
			echo "<br/>";				
		}
		if($have_issue == true && $show_details) {
			$issueLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/issue', 'id' => $section_item->issue->issue_id]);
			echo "<span class='section-serach-section-result-issue'><u><i>Issue:</i></u> <a href='".$issueLink."'>".$section_item->issue->title."</a></span>";
			echo "<br/>";				
		}
		echo "</div>";
		if($section_index < (count($sections_result)-1)) {
			echo "<hr style='border-top: 3px dashed #eee;' />";
		}
	}
} else {
	echo "<div class='serach-section-empty-result'>No Sections are found!</div>";
}
?>