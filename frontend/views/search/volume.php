<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;

$this->title = $modelVolume->title;
$this->params['breadcrumbs'][] = "Volume";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="search-page-details">
	<h2><?= $modelVolume->searchVolumeTitle; ?></h2>
	<hr class="hr-dotted">
<?php 
	if($modelVolume->issues != null && count($modelVolume->issues)>0) {
		foreach ($modelVolume->issues as $issue_index => $issue_item) {
?>	
	<div id="accordion-first" class="clearfix">
		<div class="accordion" id="accordion2">
        	<div class="accordion-group" id="Issue<?= $issue_item->issue_id ?>">
            	<div class="accordion-heading">
                	<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?= $issue_index ?>">
                    	<em class="icon-fixed-width glyphicon glyphicon-plus"></em><?= "<u><i>Issue #".($issue_index+1).":</i></u> ".$issue_item->title."</a>"; ?>
                	</a>
                </div>
                <div style="height: 0px;" id="collapse<?= $issue_index ?>" class="accordion-body collapse">
                    <div class="accordion-inner search-page-details-nested-level-one">
<?php 
			$have_articles = false;
			if($issue_item->sections != null && count($issue_item->sections)>0) {
				$issueLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/issue', 'id' => $issue_item->issue_id]);
				echo "<h4 class='size20'><a href='".$issueLink."'>Table of Contents</a></h4>";
				echo "<hr class='hr-dashed'>";
				echo "<h5 class='size18'>Sections</h5>";
				foreach ($issue_item->sections as $section_index => $section_item) {					
					$sectionLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/section', 'id' => $section_item->section_id]);
					echo "<span class='search-page-details-nested-level-two'><a href='".$sectionLink."'>".$section_item->title."</a></span>";
					echo "<br/>";
					if($have_articles == false && $section_item->publishedArticles != null && count($section_item->publishedArticles)>0) {
						$have_articles = true;
					}
				}				
				if($have_articles == true) {
					echo "<hr class='hr-dashed'>";
					echo "<h5 class='size18'>Articles</h5>";
				}				
				foreach ($issue_item->sections as $section_index => $section_item) {
					if($section_item->publishedArticles != null && count($section_item->publishedArticles)>0) {
						foreach ($section_item->publishedArticles as $article_index => $article_item) {
							$articleLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/article', 'id' => $article_item->article_id]);
							echo "<span class='search-page-details-nested-level-two'><a href='".$articleLink."'>".$article_item->title."</a></span>";
							echo "<br/>";
							echo "<span class='search-page-details-nested-level-three'><i>".\common\models\ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['public_search']."</i></span>";
							echo "<br/>";			
						}
					}
				}
			}
?>
               		</div>
            	</div>
        	</div>
       		<hr class="hr-dotted">
   		</div>
	</div><!-- end accordion -->
<?php 			
		}
	}
?>
</div>