<?php 
	if(isset($homeSections) && count($homeSections)>0) {		
		echo "<hr class='hr-double'>";
		foreach ($homeSections as $modelSection) {
        	$id = $modelSection->homepage_section_id;
        	$section_type = $modelSection->section_type;
        	$section_name = $common_vars->admin_homepage_sections[$section_type]['section_name'];
        	$section_url = $common_vars->admin_homepage_sections[$section_type]['section_url'];
        	if($section_type == "page_content"){
        		echo $modelSection->section_content;
        	} else if($section_type == "current_issue"){
?>
			<div class="search-page-details">
				<?php 
					if($modelIssue != null) {
				?>
					<h2><?= $modelIssue->title; ?></h2>
					<?php if(isset($modelIssue->published_on)) { ?>	
						<div class="search-page-details-nested-level-two">
							<span>Published at: <i><?= date("M d, Y, g:i:s A", strtotime($modelIssue->published_on)) ?></i></span>
						</div>
					<?php } ?>
					<hr class="hr-dashed">
					<div class="accordion-inner">
				<?php 
					if($modelIssue->sections != null && count($modelIssue->sections)>0) {
						echo "<h4 class='size20'>Table of Contents</h4>";
						echo "<hr class='hr-dashed'>";
						echo "<h5 class='size18'>Section(s) / Article(s) / Author(s)</h5>";
						foreach ($modelIssue->sections as $section_index => $section_item) {					
							$sectionLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/section', 'id' => $section_item->section_id]);
							echo "<span class='search-page-details-nested-level-two'><a href='".$sectionLink."'>".$section_item->title."</a></span>";
							echo "<br/>";
				
							if($section_item->publishedArticles != null && count($section_item->publishedArticles)>0) {
								echo "<div class='search-page-details-nested-level-two'>";
								foreach ($section_item->publishedArticles as $article_index => $article_item) {
									$articleLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/article', 'id' => $article_item->article_id]);
									echo "<span class='search-page-details-nested-level-two'><a href='".$articleLink."'>".$article_item->title."</a></span>";
									echo "<br/>";
									$authors_list = \common\models\ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['public_search'];
									if(isset($authors_list) && strlen($authors_list) > 0) {
										echo "<span class='search-page-details-nested-level-three'><i>".$authors_list."</i></span>";
										echo "<br/>";
									}
								}
								echo "</div>";
							}
						}	
						
						/*if($modelIssue != null) {
							$issueLinkHTML = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/issue', 'id' => $modelIssue->issue_id, 'type' => 'html']);
							$issueLinkPDF = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/issue', 'id' => $modelIssue->issue_id, 'type' => 'pdf']);
							echo "<hr class='hr-dashed'>";
							echo "<h4 class='size20'>Full Text</h4>";
							echo "<div class='public-full-text search-page-details-nested-level-two'>";
							echo "<span><a href=".$issueLinkHTML.">HTML</a></span>&nbsp;&nbsp;";
							echo "<span><a href=".$issueLinkPDF.">PDF</a></span>";
							echo "</div>";
						}*/
						
						if($modelIssue->volume != null) {
							echo "<hr class='hr-dashed'>";
							echo "<h5 class='size18'>Volume</h5>";
							$volumeLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/volume', 'id' => $modelIssue->volume->volume_id]);
							echo "<span class='search-page-details-nested-level-two'><a href='".$volumeLink."'>".$modelIssue->volume->searchVolumeTitle."</a></span>";
							echo "<br/>";
						}
					}
				?>
					</div>
				<?php 
					} else {
						echo "<hr class='hr-dotted'>";
						echo "<div class='serach-section-empty-result'>No current Issue is found!</div>";
						echo "<hr class='hr-dotted'>";
					}
				?>
				</div>
<?php 
        	}	
        	echo "<hr class='hr-double'>";
		}
	} else { 
		echo "No result"; 
	} 
?>