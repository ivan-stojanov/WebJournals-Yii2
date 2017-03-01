<?php
if($articles_result != null && count($articles_result)>0) {
	foreach ($articles_result as $article_index => $article_item) {
		$articleLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/article', 'id' => $article_item->article_id]);
		echo "<div class='row'>";		
		echo "<ul class='article-serach-section-result-article'>";
		echo "	<li>";
		echo "		<span><a href='".$articleLink."'>".$article_item->title."</a></span>";
		echo "		<br>";
		$authors_list = \common\models\ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['public_search'];
		if(isset($authors_list) && strlen($authors_list) > 0) {
			echo "		<span class='article-serach-section-result-users'><i>Authors: </i>".$authors_list."</span>";
		}		
		echo "	</li>";
		echo "</ul>";
		$show_details = false;
		if($params_GET != null && $params_GET->getQueryParam('details') != null && $params_GET->getQueryParam('details') == '1')
			$show_details = true;
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
			$volumeLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/volume', 'id' => $article_item->section->issue->volume->volume_id]);
			echo "<span class='article-serach-section-result-volume'><u><i>Volume:</i></u> <a href='".$volumeLink."'>".$article_item->section->issue->volume->searchVolumeTitle."</a></span>";
			echo "<br/>";				
		}
		if($have_issue == true && $show_details) {
			$issueLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/issue', 'id' => $article_item->section->issue->issue_id]);
			echo "<span class='article-serach-section-result-issue'><u><i>Issue:</i></u> <a href='".$issueLink."'>".$article_item->section->issue->title."</a></span>";
			echo "<br/>";				
		}
		if($have_section == true && $show_details) {
			$sectionLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/section', 'id' => $article_item->section->section_id]);
			echo "<span class='article-serach-section-result-section'><u><i>Section:</i></u> <a href='".$sectionLink."'>".$article_item->section->title."</a></span>";
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