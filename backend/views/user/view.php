<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'User Details';
?>
<div class="user-view">

	<h1><?php echo Html::encode($model->first_name." ".$model->last_name);?></h1>
    <hr>

    <p>
	    <?php if($user_can_modify) { ?>
	    	<?php if($model->is_unregistered_author) {
	        	echo Html::a('Update', ['updateunregisteredauthor', 'id' => $model->id], ['class' => 'btn btn-primary']);
	        	if(Yii::$app->session->get('user.is_admin') == true){
	        		echo "&nbsp;";
	        		echo Html::a('Register User', ['update', 'id' => $model->id], ['class' => 'btn btn-success']);
	    		}
	    	} else {
	        	echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
	        }
	        echo "&nbsp;";
	        echo Html::a('Delete', ['delete', 'id' => $model->id], [
	            'class' => 'btn btn-danger',
	            'data' => [
	                'confirm' => 'Are you sure you want to delete this item?',
	                'method' => 'post',
	            ],
	        ]);
        } ?>
    </p>
    
    <?php 
	    	$attributes = [
	            // 'id',
	            'username',
	            // 'auth_key',
	            // 'password_hash',
	            // 'password_reset_token',
	            'email:email',
	            // 'status',            
	            'first_name',
	        	'middle_name',
	            'last_name',
	            'gender',
	    		'initials',
	    	];
    	if($user_can_modify) {
	    	$attributes = ArrayHelper::merge($attributes, [
	            'phone',
	            'fax',
	            'mailing_address:ntext',
	    	]);
    	}
    		$attributes = ArrayHelper::merge($attributes, [
    			array(
    					'class' => DataColumn::className(), // this line is optional
    					'attribute' => 'country',
    					'value' => ($model->country != null) ? $common_vars->country_values[$model->country] : null,
    					'format' => 'HTML',
    			),    				
	            'bio_statement:ntext',
	        	'url:url',
	        	'salutation',
	        	'affiliation:ntext',
    		]);
    	if($user_can_modify) {
    		$attributes = ArrayHelper::merge($attributes, [    				
	        	'signature:ntext',
	        	'orcid_id',            
	        	 array(
	        			'class' => DataColumn::className(), // this line is optional
	        			'attribute' => 'send_confirmation',
	        			'value' => ($model->send_confirmation == 0) ? "<div class='glyphicon glyphicon-remove'></div>" : "<div class='glyphicon glyphicon-ok'></div>",
	        			'format' => 'HTML'
	        	 ),        		
	        	 array(
	        			'class' => DataColumn::className(), // this line is optional
	        			'attribute' => 'is_admin',
	        			'value' => ($model->is_admin == 0) ? "<div class='glyphicon glyphicon-remove'></div>" : "<div class='glyphicon glyphicon-ok'></div>",
	        	 		'format' => 'HTML'
	        	 ),
	        	 array(
	        			'class' => DataColumn::className(), // this line is optional
	        			'attribute' => 'is_editor',
	        			'value' => ($model->is_editor == 0) ? "<div class='glyphicon glyphicon-remove'></div>" : "<div class='glyphicon glyphicon-ok'></div>",
	        			'format' => 'HTML'
	        	 ),
	        	 array(
	        			'class' => DataColumn::className(), // this line is optional
	        			'attribute' => 'is_reader',
	        			'value' => ($model->is_reader == 0) ? "<div class='glyphicon glyphicon-remove'></div>" : "<div class='glyphicon glyphicon-ok'></div>",
	        			'format' => 'HTML'
	        	 ), 				
    		]);
    	}
    		$attributes = ArrayHelper::merge($attributes, [
	        	 array(
	        			'class' => DataColumn::className(), // this line is optional
	        			'attribute' => 'is_author',
	        			'value' => ($model->is_author == 0) ? "<div class='glyphicon glyphicon-remove'></div>" : "<div class='glyphicon glyphicon-ok'></div>",
	        			'format' => 'HTML'
	         	 ),
	        	 array(
	        			'class' => DataColumn::className(), // this line is optional
	        			'attribute' => 'is_reviewer',
	        			'value' => ($model->is_reviewer == 0) ? "<div class='glyphicon glyphicon-remove'></div>" : "<div class='glyphicon glyphicon-ok'></div>",
	        			'format' => 'HTML'
	        	 ),
    			array(
    					'class' => DataColumn::className(), // this line is optional
    					'attribute' => 'is_unregistered_author',
    					'value' => ($model->is_unregistered_author == 1) ? "<div class='glyphicon glyphicon-remove'></div>" : "<div class='glyphicon glyphicon-ok'></div>",
    					'format' => 'HTML',
    					'label' => 'Is Registered Author'
    			),    				
    		]);
    	if($user_can_modify) {
    		$attributes = ArrayHelper::merge($attributes, [
    			array(
    					'class' => DataColumn::className(), // this line is optional
    					'attribute' => 'creator_user_id',
    					'value' => ($model->creator_user_id != null) ? $model->creatorUser->fullName : null,
    					'format' => 'HTML'
    			),
	            'reviewer_interests:ntext',
	        	 /*array(
	        			'class' => DataColumn::className(), // this line is optional
	        			'attribute' => 'user_image',
	        			'value' => Yii::getAlias('@common')."\\images\users\\".$model->user_image,
	        	 		'format' => 'image'
	        	 ),*/ 
    			/*[
    				'class' => DataColumn::className(), // this line is optional
    				'attribute' => 'created_at',
    				'value' => (isset($model->created_at)) ? date("M d, Y, g:i:s A", strtotime($model->created_at)) : null,
    				'format' => 'HTML'
    			],
    			[
    				'class' => DataColumn::className(), // this line is optional
    				'attribute' => 'updated_at',
    				'value' => (isset($model->updated_at)) ? date("M d, Y, g:i:s A", strtotime($model->updated_at)) : null,
    				'format' => 'HTML'
    			],
    			[
    				'class' => DataColumn::className(), // this line is optional
    				'attribute' => 'last_login',
    				'value' => (isset($model->last_login)) ? date("M d, Y, g:i:s A", strtotime($model->last_login)) : null,
    				'format' => 'HTML'
    			],*/    				
	        	'created_at:datetime',
	        	'updated_at:datetime',
	        	'last_login:datetime',            
	        ]);
    	}
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>

</div>
