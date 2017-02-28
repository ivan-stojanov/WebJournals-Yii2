<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\grid\DataColumn;
use common\models\Article;
use common\models\Section;
use common\models\Issue;
use common\models\ArticleAuthor;
use common\models\ArticleEditor;

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
	<h1>Editor's Issues</h1>
	<hr>
	
	<?php	
		$columns = [
            [
            	'class' => 'yii\grid\SerialColumn',
            	//'headerOptions' => ['style' => 'width:5%'],
            ],
				// 'issue_id',
				//'title:ntext',
			[
				'class' => DataColumn::className(), // this line is optional
				'attribute' => 'title',
				'label' => 'Issue title',
				'value' =>function ($data) {
					return displayColumnContent($data->title, 45);
				},
				"format" => "HTML",
				'headerOptions' => ['style' => 'width:35%'],
			],
			[
				'class' => DataColumn::className(), // this line is optional
				'attribute' => 'volume_id',
				'label' => 'Volume title',
				'value' =>function ($data) {
					return displayColumnContent($data->volume->title, 40);
				},
				"format" => "HTML",
				'filter'=>Issue::get_volumes(),
				'headerOptions' => ['style' => 'width:30%'],
			],
			[
				'class' => DataColumn::className(), // this line is optional
				'attribute' => 'is_special_issue',
				'value' =>function ($data) {
					if ($data->is_special_issue == 0)
						return "<div class='glyphicon glyphicon-remove'></div>";
					else
						return "<div class='glyphicon glyphicon-ok'></div>";
				},
				"format" => "HTML",
				'filter'=>[
						"1" => "Yes",
						"0" => "No"
				],				
			],
			[
				'class' => DataColumn::className(), // this line is optional
				'attribute' => 'published_on',
				'value' =>function ($data) {
				if (isset($data->published_on))
					return date("M d, Y, g:i:s A", strtotime($data->published_on));
				},
				'format' => 'HTML',
				//'headerOptions' => ['style' => 'width:15%'],
			],
			//'published_on:datetime',
			// 'special_title:ntext',
			// 'special_editor',
			// 'cover_image',
			// 'sort_in_volume',
			// 'created_on',
			// 'updated_on',
			// 'is_deleted',
        ];
		$columns[] =
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view}',
				//'headerOptions' => ['style' => 'width:5%'],
				'buttons' => [
					'view' => function ($url, $model) {
						//overwrite link in {view} template
						$urlIssueLink = Yii::$app->urlManagerBackEnd->createAbsoluteUrl(['issue/view', 'id' => $model->issue_id]);
						return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $urlIssueLink, [
								'title' => Yii::t('app', 'lead-view'),
						]);
					},
				]					
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
