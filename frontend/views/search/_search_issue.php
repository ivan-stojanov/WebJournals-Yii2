<?php
if($issues_result != null && count($issues_result)>0) {
	foreach ($issues_result as $issue_index => $issue_item) {
		echo "<div class='row'>";
		echo "<span class='issue-serach-section-result-issue'>".$issue_item->title."</span>";
		echo "<br/>";
		$show_details = true;
		if($params_GET != null && $params_GET->getQueryParam('details') != null && $params_GET->getQueryParam('details') == '0')
			$show_details = false;
		$issue_sectons = null;
		if($issue_item->sections != null)
			$issue_sectons = $issue_item->sections;
		if($issue_sectons != null && count($issue_sectons)>0 && $show_details) {
			foreach ($issue_sectons as $section_index => $section_item) {
				echo "<span class='issue-serach-section-result-section'>".$section_item->title."</span>";
				echo "<br/>";
				$secton_articles = null;
				if($section_item->publishedArticles != null)
					$secton_articles = $section_item->publishedArticles;
				if($secton_articles != null && count($secton_articles)>0) {
					foreach ($secton_articles as $article_index => $article_item) {
						echo "<ul class='issue-serach-section-result-article'>";
						echo "	<li>";
						echo "		<span>".$article_item->title."</span>";
						echo "		<br>";
						echo "		<i>Authors: </i><span class='issue-serach-section-result-users'>".\common\models\ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['string']."</span>";
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
			echo "<span class='issue-serach-section-result-volume'><u><i>Volume:</i></u> ".$issue_item->volume->title."</span>";
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