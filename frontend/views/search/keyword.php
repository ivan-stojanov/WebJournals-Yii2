<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;

$this->title = $modelKeyword->content;
$this->params['breadcrumbs'][] = "Keyword";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="search-page-details">
	<h2><?= $modelKeyword->content; ?></h2>
<?php	
	echo "<hr class='hr-dotted'>";
	echo "<div class='accordion-inner'>";
	if($modelKeyword->publishedArticles != null && count($modelKeyword->publishedArticles)>0) {		
		echo "<h5 class='size18'>Articles</h5>";				
		foreach ($modelKeyword->publishedArticles as $article_index => $article_item) {
			$articleLink = Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['search/article', 'id' => $article_item->article_id]);
			echo "<span class='search-page-details-nested-level-two'><a href='".$articleLink."'>".$article_item->title."</a></span>";
			echo "<br/>";
			echo "<span class='search-page-details-nested-level-three'><i>".\common\models\ArticleAuthor::getAuthorsForArticleString($article_item->article_id)['public_search']."</i></span>";
			echo "<br/>";
			if($article_index < (count($modelKeyword->publishedArticles)-1)) {
				echo "<br/>";
			}
		}		
	} else {
		echo "<div class='serach-section-empty-result'>No Articles were found for this Keyword!</div>";
	}
	echo "</div>";
?>	
	<hr class="hr-dotted">
</div>