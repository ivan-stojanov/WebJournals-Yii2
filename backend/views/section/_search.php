<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\grid\DataColumn;
use common\models\Section;
use common\models\Issue;

/* @var $this yii\web\View */
/* @var $model backend\models\SectionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="section-search">

	<?php if(isset($post_msg)){ ?>
	    <div class="alert alert-dismissable <?php echo "alert-".$post_msg["type"];?>" id="homepage-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
		    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		    <strong><span id="homepage-section-alert-msg"></span><?php echo $post_msg["text"]; ?></strong>
		</div>
	<?php } ?>
	<h1><?php echo "Section List" ?></h1>
	<hr>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title:ntext',
        	[
	        	'class' => DataColumn::className(), // this line is optional
	        	'attribute' => 'issue_id',
	        	'label' => 'Issue title',
	        	'value' =>function ($data) {
	        		return $data->issue->title;
	        	},
	        	"format" => "HTML",
	        	'filter'=>Section::get_issues(),
        	],
        	[
	        	'class' => DataColumn::className(), // this line is optional
	        	'attribute' => 'issue.volume.title',
	        	'label' => 'Volume title',
	        	'value' =>function ($data) {
	        		return $data->issue->volume->title;
	        	},
	        	"format" => "HTML",
	        	'filter'=>Issue::get_volumes(),
        	],        	
            // 'created_on',
            // 'updated_on',
            // 'is_deleted',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
