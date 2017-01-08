<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\grid\DataColumn;
use common\models\Article;
use common\models\Section;
use common\models\Issue;
use common\models\ArticleAuthor;

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
            ['class' => 'yii\grid\SerialColumn'],

            //'title:ntext',
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
	        	'attribute' => 'section_id',
	        	'label' => 'Section title',
	        	'value' =>function ($data) {
	        		if(isset($data->section))
	        			return displayColumnContent($data->section->title, 30);
	        		else
	        			/*if(!isset($data->section_id)){
	        				return 0;
	        			}*/
	        			return null;
	        	},
	        	"format" => "HTML",
	        	'filter'=>Article::get_sections(),
	        	'headerOptions' => ['style' => 'width:25%'],
        	],
        	[
	        	'class' => DataColumn::className(), // this line is optional
	        	'attribute' => 'status',
	        	'value' =>function ($data) {
	        		if ($data->status == Article::STATUS_SUBMITTED)
	        			return "<div class='glyphicon glyphicon-book'> Submitted</div>";
	        		else if ($data->status == Article::STATUS_UNDER_REVIEW)
	        			return "<div class='glyphicon glyphicon-eye-open'> Under review</div>";
	        		else if ($data->status == Article::STATUS_REVIEW_REQUIRED)
	        			return "<div class='glyphicon glyphicon-eye-open'> Review required</div>";
	        		else if ($data->status == Article::STATUS_ACCEPTED_FOR_PUBLICATION)
	        			return "<div class='glyphicon glyphicon-ok-circle'> Accepted for publication</div>";
	        		else if ($data->status == Article::STATUS_PUBLISHED)
	        			return "<div class='glyphicon glyphicon-ok'> Published</div>";
	        		else if ($data->status == Article::STATUS_REJECTED)
	        			return "<div class='glyphicon glyphicon-remove'> Rejected</div>";	        		 
        		},
	        	"label" => "Status",
	        	"format" => "HTML",
	        	'filter'=>[
	        			(string)Article::STATUS_SUBMITTED => "Submitted",
	        			(string)Article::STATUS_UNDER_REVIEW => "Under review",
	        			(string)Article::STATUS_REVIEW_REQUIRED => "Review required",
	        			(string)Article::STATUS_ACCEPTED_FOR_PUBLICATION => "Accepted for publication",
	        			(string)Article::STATUS_PUBLISHED => "Published",
	        			(string)Article::STATUS_REJECTED => "Rejected"
	        	],
	        	'headerOptions' => ['style' => 'width:20%'],
        	],
        	[
	        	'class' => DataColumn::className(), // this line is optional
	        	'label' => 'Authors',
	        	'value' =>function ($data) {
    				return ArticleAuthor::getAuthorsForArticleString($data->article_id)['string'];
	        	},
	        	"format" => "HTML",
	        	'headerOptions' => ['style' => 'width:20%'],
        	],
        	/*[
	        	'class' => DataColumn::className(), // this line is optional
	        	'attribute' => 'section.issue.title',
	        	'label' => 'Issue title',
	        	'value' =>function ($data) {
	        		return displayColumnContent($data->section->issue->title, 25);
	        	},
	        	"format" => "HTML",
	        	'filter'=>Section::get_issues(),
	        	'headerOptions' => ['style' => 'width:20%'],
        	],        	
        	[
	        	'class' => DataColumn::className(), // this line is optional
	        	'attribute' => 'section.issue.volume.title',
	        	'label' => 'Volume title',
	        	'value' =>function ($data) {
	        		return displayColumnContent($data->section->issue->volume->title, 25);
	        	},
	        	"format" => "HTML",
	        	'filter'=>Issue::get_volumes(),
	        	'headerOptions' => ['style' => 'width:20%'],
        	],*/        	
            // 'created_on',
            // 'updated_on',
            // 'is_deleted',
	        ['class' => 'yii\grid\ActionColumn']
        ];
		
		/*if(Yii::$app->session->get('user.is_admin') == true) {
			$columns[] = ['class' => 'yii\grid\ActionColumn'];
		} else {
			$columns[] = ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update} {delete}'];
		}*/
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
