<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\base\Widget;

/* @var $this yii\web\View */
/* @var $model backend\models\VolumeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="volume-search">

	<?php if(isset($post_msg)){ ?>
	    <div class="alert alert-dismissable <?php echo "alert-".$post_msg["type"];?>" id="homepage-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
		    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		    <strong><span id="homepage-section-alert-msg"></span><?php echo $post_msg["text"]; ?></strong>
		</div>
	<?php } ?>
	<h1><?php echo "Volume List" ?></h1>
	<hr>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title:ntext',
            'year',
        	'created_on:datetime',
        	/*[
        		//'attribute' => 'created_on',
        		'header'		=> 'Created on',
        		'value'		=> 'created_on',
        		'format'	=> 'datetime',
        		'filter'	=> DatePicker::widget([
        			'model' 		=> $searchModel,
        			'attribute' 	=> 'created_on',
        			'clientOptions' => [
        				'autoclose' => true,
        				'format'	=> 'datetime',
    				]
    			]),
        	],*/            

        	['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
