<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\DataColumn;

/* @var $this yii\web\View */
/* @var $model app\models\UnregisteredUserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="unregistered-user-search">

	<?php if(isset($post_msg)){ ?>
	    <div class="alert alert-dismissable <?php echo "alert-".$post_msg["type"];?>" id="homepage-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
		    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		    <strong><span id="homepage-section-alert-msg"></span><?php echo $post_msg["text"]; ?></strong>
		</div>
	<?php } ?>
	
	<h1><?php echo "Unregistered User List" ?></h1>
	<hr>
 
 	<?php /* ?>
	    <p>
	        <?= Html::a('Create Unregistered User', ['create'], ['class' => 'btn btn-success']) ?>
	    </p>
    <?php */

	$columns = [
            ['class' => 'yii\grid\SerialColumn'],

            //'unregistered_user_id',
            //'user_creator_id',
            'username',
            'email:email',
			array(
					'class' => DataColumn::className(), // this line is optional
					'label' => 'Full Name',
					'attribute' => 'first_name',
					'value' => function($model) {
						return $model->first_name  . " " .$model->middle_name  . " " . $model->last_name;
					},
			),
            // 'first_name',
            // 'last_name',
            // 'middle_name',
            // 'gender',
            // 'initials',
            // 'mailing_address:ntext',
            // 'country',
            // 'created_on',
            // 'updated_on',
            // 'is_deleted',

            //'class' => 'yii\grid\ActionColumn'],
        ];
	
		if(Yii::$app->session->get('user.is_admin') == true) {
			$columns[] = ['class' => 'yii\grid\ActionColumn'];
		} else {
			$columns[] = ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'];
		}    
	?>
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    		'rowOptions' => function ($model, $index, $widget, $grid){    		
	    		if(Yii::$app->user->id == $model->user_creator_id){
	    			return ['class' => 'success'];
	    		} else {
	    			return [];
	    		}
    		},
    ]); ?>
