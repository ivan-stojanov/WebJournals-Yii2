<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;

$this->title = $modelUser->fullName;
$this->params['breadcrumbs'][] = "User";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="search-page-details">
	<h2><?= $modelUser->fullName; ?></h2>
<?php
	echo "<hr class='hr-dotted'>";
	echo "<div class='accordion-inner'>";
	if($modelUser->myPublishedArticles != null && count($modelUser->myPublishedArticles)>0) {		
		echo "<h5 class='size18'>Articles</h5>";				
		foreach ($modelUser->myPublishedArticles as $article_index => $article_item) {
			$articleLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/article', 'id' => $article_item->article_id]);
			echo "<span class='search-page-details-nested-level-two'><a href='".$articleLink."'>".$article_item->title."</a></span>";
			echo "<br/>";
			echo "<span class='search-page-details-nested-level-three'><i>".\common\models\ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['public_search']."</i></span>";
			echo "<br/>";			
			if($article_index < (count($modelUser->myPublishedArticles)-1)) {
				echo "<br/>";
			}
		}		
	} else {
		echo "<div class='serach-section-empty-result'>No Articles were found for this User!</div>";
	}
	echo "</div>";
?>	
	<hr class="hr-dotted">
</div>