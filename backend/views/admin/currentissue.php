<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Current Issue';
$this->params['breadcrumbs'][] = $this->title;
if($modelIssue != null)
	$this->params['breadcrumbs'][] = $modelIssue->title;
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
	<hr class="hr-dotted">
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
	<hr class="hr-dotted">
<?php 
	} else {
		echo "<hr class='hr-dotted'>";
		echo "<div class='serach-section-empty-result'>No current Issue is found!</div>";
		echo "<hr class='hr-dotted'>";
	}
?>
<?php 
/* //to do ivan: try this widget with steps if you need if somewhere
//https://github.com/drsdre/yii2-wizardwidget
$wizard_config = [
    'id' => 'stepwizard',
    'steps' => [
        1 => [
            'title' => 'Step 1',
            'icon' => 'glyphicon glyphicon-cloud-download',
            'content' => $this->render('about'),
            'buttons' => [
                'next' => [
                    'title' => 'Forward', 
                   /* 'options' => [
                        'class' => 'disabled'
                    ],* /
                 ],
             ],
        ],
        2 => [
            'title' => 'Step 2',
            'icon' => 'glyphicon glyphicon-cloud-upload',
            'content' => '<h3>Step 2</h3>This is step 2',
            'skippable' => true,
        ],
        3 => [
            'title' => 'Step 3',
            'icon' => 'glyphicon glyphicon-transfer',
            'content' => '<h3>Step 3</h3>This is step 3',
        ],
    ],
    'complete_content' => "You are done!", // Optional final screen
    'start_step' => 1, // Optional, start with a specific step
];
?>

<?= \drsdre\wizardwidget\WizardWidget::widget($wizard_config); ?>
*/
?>
</div>
