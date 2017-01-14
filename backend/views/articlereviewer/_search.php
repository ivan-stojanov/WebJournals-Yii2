<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\grid\DataColumn;
use common\models\Article;
use common\models\Section;
use common\models\Issue;
use common\models\ArticleAuthor;
use common\models\ArticleReviewer;

/* @var $this yii\web\View */
/* @var $model backend\models\ArticleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-search">

	<?php if(isset($post_msg)){ ?>
	    <div class="alert alert-dismissable <?php echo "alert-".$post_msg["type"];?>" id="homepage-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
		    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		    <strong><span id="homepage-section-alert-msg"></span><?php echo $post_msg["text"]; ?></strong>
		</div>
	<?php } ?>
	<h1><?php echo $title_msg ?></h1>
	<hr>
	
	<?php	
		$columns = [
            [
            	'class' => 'yii\grid\SerialColumn',
            	'headerOptions' => ['style' => 'width:5%'],
            ],
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'title',
        		'label' => 'Article title',
        		'value' =>function ($data) {
        			return displayColumnContent($data->title, 30);
        		},
        		"format" => "HTML",
        		'headerOptions' => ['style' => 'width:25%'],
        	],
        	[
	        	'class' => DataColumn::className(), // this line is optional
	        	'label' => 'Authors',
	        	'value' =>function ($data) {
    				return ArticleAuthor::getAuthorsForArticleString($data->article_id)['string'];
	        	},
	        	"format" => "HTML",
	        	'headerOptions' => ['style' => 'width:25%'],
        	],
        	[
	        	'class' => DataColumn::className(), // this line is optional
	        	'label' => 'Reviewers',
	        	'value' =>function ($data) {
	        		return ArticleReviewer::getReviewersForArticleString($data->article_id)['string'];
	        	},
	        	"format" => "HTML",
	        	'headerOptions' => ['style' => 'width:25%'],
        	],
	        [
	        	'class' => 'yii\grid\ActionColumn', 
	        	'template' => '{view}', 
	        	'headerOptions' => ['style' => 'width:5%'],	        		
	        ],
        ];
	?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); 
    
    function displayColumnContent($contenttext, $contentlimitlength){
    	$displaytext = $contenttext;
    	if(strlen($displaytext) > $contentlimitlength) $displaytext = substr($displaytext, 0, $contentlimitlength)."...";
    	return "<div title='".$contenttext."'>".$displaytext."</div>";
    }
    
    ?>
</div>
