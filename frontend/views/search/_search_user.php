<?php
if($users_result != null && count($users_result)>0) {
	foreach ($users_result as $users_index => $users_item) {
		$userLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/user', 'id' => $users_item->id]);
		echo "<div class='row'>";
		echo "<span class='user-serach-section-result-user'><a href='".$userLink."'>".$users_item->fullName."</a></span>";
		echo "<br/>";
		$show_details = false;
		if($params_GET != null && $params_GET->getQueryParam('details') != null && $params_GET->getQueryParam('details') == '1')
			$show_details = true;
		if($show_details) {
			$published_ArticleAuthor_result = \common\models\ArticleAuthor::getPublishedArticlesForAuthor($users_item->id);
			foreach ($published_ArticleAuthor_result as $published_ArticleAuthor_index => $published_ArticleAuthor_item) {
				$have_article = false;
				$have_section = false;
				$have_issue = false;
				$have_volume = false;
				if($published_ArticleAuthor_item->article != null) {
					$have_article = true;
					if($published_ArticleAuthor_item->article->section != null) {
						$have_section = true;
						if($published_ArticleAuthor_item->article->section->issue != null){
							$have_issue = true;
							if($published_ArticleAuthor_item->article->section->issue->volume != null) {
								$have_volume = true;
							}
						}
					}
				}
				if($have_article == true) {
					$articleLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/article', 'id' => $published_ArticleAuthor_item->article->article_id]);
					echo "<ul class='keyword-serach-section-result-article'>";
					echo "	<li>";
					echo "		<span><a href='".$articleLink."'>".$published_ArticleAuthor_item->article->title."</a></span>";
					echo "		<br>";
					echo "		<span class='keyword-serach-section-result-users'><i>Authors: </i>".\common\models\ArticleAuthor::getAuthorsForArticleString($published_ArticleAuthor_item->article->article_id)['string']."</span>";
					echo "	</li>";
					echo "</ul>";
				}
				if($have_volume == true) {
					$volumeLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/volume', 'id' => $published_ArticleAuthor_item->article->section->issue->volume->volume_id]);
					echo "<span class='keyword-serach-section-result-volume'><u><i>Volume:</i></u> <a href='".$volumeLink."'>".$published_ArticleAuthor_item->article->section->issue->volume->title."</a></span>";
					echo "<br/>";
				}
				if($have_issue == true) {
					$issueLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/issue', 'id' => $published_ArticleAuthor_item->article->section->issue->issue_id]);
					echo "<span class='keyword-serach-section-result-issue'><u><i>Issue:</i></u> <a href='".$issueLink."'>".$published_ArticleAuthor_item->article->section->issue->title."</a></span>";
					echo "<br/>";
				}
				if($have_section == true) {
					$sectionLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/section', 'id' => $published_ArticleAuthor_item->article->section->section_id]);
					echo "<span class='keyword-serach-section-result-section'><u><i>Section:</i></u> <a href='".$sectionLink."'>".$published_ArticleAuthor_item->article->section->title."</a></span>";
					echo "<br/>";
				}
					
				if($published_ArticleAuthor_index < (count($published_ArticleAuthor_result)-1)) {
					echo "<br/>";
				}
			}
		}
		echo "</div>";
		if($users_index < (count($users_result)-1)) {
			echo "<hr style='border-top: 3px dashed #eee;' />";
		}
	}
} else {
	echo "<div class='serach-section-empty-result'>No Users are found!</div>";
}
?>