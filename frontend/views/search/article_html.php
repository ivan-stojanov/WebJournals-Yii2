<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;

$this->title = $modelArticle->title;
$this->params['breadcrumbs'][] = "Article";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="search-page-details">
	<h2><?= $modelArticle->title; ?></h2>
	<span class="public-abstract search-page-details-nested-level-two">
		Author(s): <i><?= \common\models\ArticleAuthor::getAuthorsForArticleString($modelArticle->article_id)['public_search']?></i>
	</span>
	<br/>	
	<hr class="hr-dotted">
	<div class="accordion-inner">
		<h4 class="size20">Abstract</h4>
		<div class="public-abstract search-page-details-nested-level-two">
			<?= $modelArticle->abstract ?>
		</div>	
		<hr class="hr-dashed">
		<h4 class="size20">Full Text</h4>
		<div class="public-full-text search-page-details-nested-level-two">
			<?= $modelArticle->content ?>
		</div>
		<hr class="hr-dashed">
		<h4 class="size20">Keyword(s)</h4>
		<div class="public-keyword search-page-details-nested-level-two">
			<i><?= \common\models\ArticleKeyword::getKeywordsForArticleString($modelArticle->article_id)['public_search']?></i>
		</div>
	</div>
	
	<hr class="hr-dotted">
</div>