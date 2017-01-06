<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\grid\DataColumn;

/* @var $this yii\web\View */
/* @var $model backend\models\KeywordSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="keyword-search">

	<?php if(isset($post_msg)){ ?>
	    <div class="alert alert-dismissable <?php echo "alert-".$post_msg["type"];?>" id="homepage-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
		    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		    <strong><span id="homepage-section-alert-msg"></span><?php echo $post_msg["text"]; ?></strong>
		</div>
	<?php } ?>
	<h1><?php echo "Keyword List" ?></h1>
	<hr>
	
	<?php 
	
		$columns = [
            ['class' => 'yii\grid\SerialColumn'],

            //'keyword_id',
            'content:ntext',
            //'created_on',
            //'updated_on',
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'is_deleted',
        		'value' =>function ($data) {
        			if ($data->is_deleted == 1)
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
        	],
        ];

		if(Yii::$app->session->get('user.is_admin') == true) {
			$columns[] = ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update}'];
		} else {
			$columns[] = ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'];
		}
	
	?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>

</div>
