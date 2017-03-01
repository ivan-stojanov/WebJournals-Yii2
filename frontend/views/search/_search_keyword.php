<?php
if($keywords_result != null && count($keywords_result)>0) {	
	foreach ($keywords_result as $keyword_index => $keyword_item) {
		$keywordLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/keyword', 'id' => $keyword_item->keyword_id]);
		echo "<div class='row'>";
		echo "<span class='keyword-serach-section-result-keyword'><a href='".$keywordLink."'>".$keyword_item->content."</a></span>";
		echo "<br/>";
		$show_details = false;
		if($params_GET != null && $params_GET->getQueryParam('details') != null && $params_GET->getQueryParam('details') == '1')
			$show_details = true;
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
					$articleLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/article', 'id' => $published_ArticlesKeyword_item->article->article_id]);
					echo "<ul class='keyword-serach-section-result-article'>";
					echo "	<li>";
					echo "		<span><a href='".$articleLink."'>".$published_ArticlesKeyword_item->article->title."</a></span>";
					echo "		<br>";
					$authors_list = \common\models\ArticleAuthor::getAuthorsForArticleString($published_ArticlesKeyword_item->article->article_id)['public_search'];
					if(isset($authors_list) && strlen($authors_list) > 0) {
						echo "		<span class='keyword-serach-section-result-users'><i>Authors: </i>".$authors_list."</span>";
					}					
					echo "	</li>";
					echo "</ul>";
				}
				if($have_volume == true) {
					$volumeLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/volume', 'id' => $published_ArticlesKeyword_item->article->section->issue->volume->volume_id]);
					echo "<span class='keyword-serach-section-result-volume'><u><i>Volume:</i></u> <a href='".$volumeLink."'>".$published_ArticlesKeyword_item->article->section->issue->volume->searchVolumeTitle."</a></span>";
					echo "<br/>";
				}
				if($have_issue == true) {
					$issueLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/issue', 'id' => $published_ArticlesKeyword_item->article->section->issue->issue_id]);
					echo "<span class='keyword-serach-section-result-issue'><u><i>Issue:</i></u> <a href='".$issueLink."'>".$published_ArticlesKeyword_item->article->section->issue->title."</a></span>";
					echo "<br/>";
				}
				if($have_section == true) {
					$sectionLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/section', 'id' => $published_ArticlesKeyword_item->article->section->section_id]);
					echo "<span class='keyword-serach-section-result-section'><u><i>Section:</i></u> <a href='".$sectionLink."'>".$published_ArticlesKeyword_item->article->section->title."</a></span>";
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