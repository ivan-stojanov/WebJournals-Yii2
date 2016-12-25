<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\UnregisteredUser */

$this->title = 'Unregistered User Details';
?>
<div class="unregistered-user-view">

	<h1><?php echo Html::encode($model->first_name." ".$model->last_name);?></h1>
    <hr>

    <p>
	    <?php if($user_can_modify) { ?>    
	        <?= Html::a('Update', ['update', 'id' => $model->unregistered_user_id], ['class' => 'btn btn-primary']) ?>
	        <?= Html::a('Delete', ['delete', 'id' => $model->unregistered_user_id], [
	            'class' => 'btn btn-danger',
	            'data' => [
	                'confirm' => 'Are you sure you want to delete this item?',
	                'method' => 'post',
	            ],
	        ]) ?>
        <?php } ?>	        
    </p>
    
    <?php 
	    	$attributes = [
	            //'unregistered_user_id',
	            //'user_creator_id',
	        	array(
	        			'class' => DataColumn::className(), // this line is optional
	        			'attribute' => 'user_creator_id',
	        			'value' => $model->userCreator->fullName,
	        			'format' => 'HTML',
	        	),        		
	            'username',
	            'email:email',
	            'first_name',
	        	'middle_name',
	            'last_name',            
	            'gender',
	            'initials',
	    		array(
	    				'class' => DataColumn::className(), // this line is optional
	    				'attribute' => 'country',
	    				'value' => $common_vars->country_values[$model->country],
	    				'format' => 'HTML',
	    		),	    			
	            'affiliation:ntext',	            
	        ];
	    if($user_can_modify) {    	
	    	$attributes = ArrayHelper::merge($attributes, [
	    		'mailing_address:ntext',            
	            'created_on',
	            'updated_on',
	            //'is_deleted',
	    	]);    	
	    }    	
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>

</div>
