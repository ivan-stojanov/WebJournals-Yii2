<?php
if($keywords_result != null && count($keywords_result)>0) {	
	foreach ($keywords_result as $keyword_index => $keyword_item) {
		echo "<div class='row'>";
		echo "<span class='keyword-serach-section-result-keyword'>".$keyword_item->content."</span>";
		echo "<br/>";
		$show_details = true;
		if($params_GET != null && $params_GET->getQueryParam('details') != null && $params_GET->getQueryParam('details') == '0')
			$show_details = false;
		if($show_details) {
			$published_ArticlesKeyword_result = \common\models\ArticleKeyword::getPublishedArticlesForKeyword($keyword_item->keyword_id);
			foreach ($published_ArticlesKeyword_result as $published_ArticlesKeyword_index => $published_ArticlesKeyword_item) {			
				$have_article = false;
				$have_section = false;
				$have_issue = false;
				$have_volume = false;
				if($published_ArticlesKeyword_item->article != null) {
					$have_article = true;
					if($published_ArticlesKeyword_item->article->section != null) {
						$have_section = true;
						if($published_ArticlesKeyword_item->article->section->issue != null){
							$have_issue = true;
							if($published_ArticlesKeyword_item->article->section->issue->volume != null) {
								$have_volume = true;
							}
						}
					}
				}
				if($have_article == true) {
					echo "<ul class='keyword-serach-section-result-article'>";
					echo "	<li>";
					echo "		<span>".$published_ArticlesKeyword_item->article->title."</span>";
					echo "		<br>";
					echo "		<span class='keyword-serach-section-result-users'><i>Authors: </i>".\common\models\ArticleAuthor::getAuthorsForArticleString($published_ArticlesKeyword_item->article->article_id)['string']."</span>";
					echo "	</li>";
					echo "</ul>";
				}
				if($have_volume == true) {
					echo "<span class='keyword-serach-section-result-volume'><u><i>Volume:</i></u> ".$published_ArticlesKeyword_item->article->section->issue->volume->title."</span>";
					echo "<br/>";
				}
				if($have_issue == true) {
					echo "<span class='keyword-serach-section-result-issue'><u><i>Issue:</i></u> ".$published_ArticlesKeyword_item->article->section->issue->title."</span>";
					echo "<br/>";
				}
				if($have_section == true) {
					echo "<span class='keyword-serach-section-result-section'><u><i>Section:</i></u> ".$published_ArticlesKeyword_item->article->section->title."</span>";
					echo "<br/>";
				}
				
				if($published_ArticlesKeyword_index < (count($published_ArticlesKeyword_result)-1)) {
					echo "<br/>";
				}
			}
		}
		echo "</div>";
		if($keyword_index < (count($keywords_result)-1)) {
			echo "<hr style='border-top: 3px dashed #eee;' />";
		}
	}
} else {
	echo "<div class='serach-section-empty-result'>No Keywords are found!</div>";
}
?>