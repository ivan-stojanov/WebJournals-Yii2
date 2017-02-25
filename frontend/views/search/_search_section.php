<?php
if($sections_result != null && count($sections_result)>0) {
	foreach ($sections_result as $section_index => $section_item) {
		echo "<div class='row'>";
		echo "<span class='section-serach-section-result-section'>".$section_item->title."</span>";
		echo "<br/>";
		$show_details = true;
		if($params_GET != null && $params_GET->getQueryParam('details') != null && $params_GET->getQueryParam('details') == '0')
			$show_details = false;
		$secton_articles = null;
		if($section_item->publishedArticles != null)
			$secton_articles = $section_item->publishedArticles;
		if($secton_articles != null && count($secton_articles)>0 && $show_details) {
			foreach ($secton_articles as $article_index => $article_item) {
				echo "<ul class='section-serach-section-result-article'>";
				echo "	<li>";
				echo "		<span>".$article_item->title."</span>";
				echo "		<br>";
				echo "		<i>Authors: </i><span class='section-serach-section-result-users'>".\common\models\ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['string']."</span>";
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
			echo "<span class='section-serach-section-result-volume'><u><i>Volume:</i></u> ".$section_item->issue->volume->title."</span>";
			echo "<br/>";				
		}
		if($have_issue == true && $show_details) {
			echo "<span class='section-serach-section-result-issue'><u><i>Issue:</i></u> ".$section_item->issue->title."</span>";
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