<?php
if($sections_result != null && count($sections_result)>0) {
	foreach ($sections_result as $section_index => $section_item) {
		echo "<div class='row'>";
		echo "<span class='section-serach-section-result-section'>".$section_item->title."</span>";
		echo "<br/>";
		$secton_articles = null;
		if($section_item->articles != null)
			$secton_articles = $section_item->articles;
		if($secton_articles != null && count($secton_articles)>0) {
			foreach ($secton_articles as $article_index => $article_item) {
				echo "<ul class='section-serach-section-result-article'>";
				echo "	<li>";
				echo "		<span>".$article_item->title."</span>";
				echo "		<br>";
				echo "		<i>Authors: </i><span class='section-serach-section-result-users'>".$article_item->title."</span>";
				echo "	</li>";
				echo "</ul>";
			}
		}
		echo "<span class='section-serach-section-result-volume'><u><i>Volume:</i></u> ".$section_item->issue->volume->title."</span>";
		echo "<br/>";
		echo "<span class='section-serach-section-result-issue'><u><i>Issue:</i></u> ".$section_item->issue->title."</span>";
		echo "<br/>";
		echo "</div>";
		if($section_index < (count($sections_result)-1)) {
			echo "<hr style='border-top: 3px dashed #eee;' />";
		}
	}
} else {
	echo "<div class='serach-section-empty-result'>No Sections are found!</div>";
}
?>