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
	        			return null;
	        	},
	        	"format" => "HTML",
	        	'filter'=>Article::get_sections(),
	        	'headerOptions' => ['style' => 'width:25%'],
        	],
        	/*[
	        	'class' => DataColumn::className(), // this line is optional
	        	'attribute' => 'is_archived',
	        	'value' =>function ($data) {
	        		if ($data->is_archived == 1)
	        			return "<div class='glyphicon glyphicon-remove'> Archived</div>";
	        		else
	        			return "<div class='glyphicon glyphicon-ok'> Active</div>";
	        	},
	        	"label" => "Status",
	        	"format" => "HTML",
	        	'filter'=>[
	        			"1" => "Archived",
	        			"0" => "Active"
	        	],        	
        	],*/
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
