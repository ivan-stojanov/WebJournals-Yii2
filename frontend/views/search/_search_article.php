<?php
if($articles_result != null && count($articles_result)>0) {
	foreach ($articles_result as $article_index => $article_item) {
		echo "<div class='row'>";		
		echo "<ul class='article-serach-section-result-article'>";
		echo "	<li>";
		echo "		<span>".$article_item->title."</span>";
		echo "		<br>";
		echo "		<span class='article-serach-section-result-users'><i>Authors: </i>".\common\models\ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['string']."</span>";
		echo "	</li>";
		echo "</ul>";
		$show_details = true;
		if($params_GET != null && $params_GET->getQueryParam('details') != null && $params_GET->getQueryParam('details') == '0')
			$show_details = false;		
		$have_section = false;
		$have_issue = false;
		$have_volume = false;
		if($article_item != null && $article_item->section != null) {
			$have_section = true;
			if($article_item->section->issue != null){
				$have_issue = true;
				if($article_item->section->issue->volume != null) {
					$have_volume = true;
				}
			}			
		}
		if($have_volume == true && $show_details) {
			echo "<span class='article-serach-section-result-volume'><u><i>Volume:</i></u> ".$article_item->section->issue->volume->title."</span>";
			echo "<br/>";				
		}
		if($have_issue == true && $show_details) {
			echo "<span class='article-serach-section-result-issue'><u><i>Issue:</i></u> ".$article_item->section->issue->title."</span>";
			echo "<br/>";				
		}
		if($have_section == true && $show_details) {
			echo "<span class='article-serach-section-result-section'><u><i>Section:</i></u> ".$article_item->section->title."</span>";
			echo "<br/>";				
		}
		echo "</div>";
		if($article_index < (count($articles_result)-1)) {
			echo "<hr style='border-top: 3px dashed #eee;' />";
		}
	}
} else {
	echo "<div class='serach-section-empty-result'>No Articles are found!</div>";
}
?>