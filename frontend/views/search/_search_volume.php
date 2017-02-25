<?php
if($volumes_result != null && count($volumes_result)>0) {
	foreach ($volumes_result as $volume_index => $volume_item) {
		echo "<div class='row'>";
		echo "<span class='volume-serach-section-result-volume'>".$volume_item->title."</span>";
		echo "<br/>";
		$show_details = true;
		if($params_GET != null && $params_GET->getQueryParam('details') != null && $params_GET->getQueryParam('details') == '0')
			$show_details = false;
		$volume_issues = null;
		if($volume_item->issues != null)
			$volume_issues = $volume_item->issues;
		if($volume_issues != null && count($volume_issues)>0 && $show_details) {
			foreach ($volume_issues as $issue_index => $issue_item) {
				echo "<span class='volume-serach-section-result-issue'><u><i>Issue #".($issue_index+1).":</i></u> ".$issue_item->title."</span>";
				echo "<br/>";
				$issue_sectons = null;
				if($issue_item->sections != null)
					$issue_sectons = $issue_item->sections;
				if($issue_sectons != null && count($issue_sectons)>0) {
					foreach ($issue_sectons as $section_index => $section_item) {
						echo "<span class='volume-serach-section-result-section'>".$section_item->title."</span>";
						echo "<br/>";
						$secton_articles = null;
						if($section_item->publishedArticles != null)
							$secton_articles = $section_item->publishedArticles;
						if($secton_articles != null && count($secton_articles)>0) {									
							foreach ($secton_articles as $article_index => $article_item) {
								echo "<ul class='volume-serach-section-result-article'>";
								echo "	<li>";
								echo "		<span>".$article_item->title."</span>";
								echo "		<br>";
								echo "		<i>Authors: </i><span class='volume-serach-section-result-users'>".\common\models\ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['string']."</span>";
								echo "	</li>";
								echo "</ul>";
							}
						}
					}
				}
			}
		}
		echo "</div>";
		if($volume_index < (count($volumes_result)-1)) {
			echo "<hr style='border-top: 3px dashed #eee;' />";
		}					
	}
} else {
	echo "<div class='serach-section-empty-result'>No Volumes are found!</div>";
}
?>