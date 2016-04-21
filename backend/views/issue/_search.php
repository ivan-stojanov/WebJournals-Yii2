<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\base\Widget;
use yii\grid\DataColumn;
use common\models\Issue;

/* @var $this yii\web\View */
/* @var $model backend\models\IssueSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="issue-search">

	<?php if(isset($post_msg)){ ?>
	    <div class="alert alert-dismissable <?php echo "alert-".$post_msg["type"];?>" id="homepage-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
		    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		    <strong><span id="homepage-section-alert-msg"></span><?php echo $post_msg["text"]; ?></strong>
		</div>
	<?php } ?>
	<h1><?php echo "Issue List" ?></h1>
	<hr>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'issue_id',
            'title:ntext',
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'volume_id',
        		'label' => 'Volume title',
        		'value' =>function ($data) {
        			return $data->volume->title;
        		},
        		"format" => "HTML",
        		'filter'=>Issue::get_volumes(),
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
        	'published_on:datetime',
            // 'special_title:ntext',
            // 'special_editor',
            // 'cover_image',
            // 'sort_in_volume',
            // 'created_on',
            // 'updated_on',
            // 'is_deleted',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
