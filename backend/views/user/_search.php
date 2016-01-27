<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\grid\DataColumn;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

	<?php if(isset($post_msg)){ ?>
	    <div class="alert alert-dismissable <?php echo "alert-".$post_msg["type"];?>" id="homepage-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
		    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		    <strong><span id="homepage-section-alert-msg"></span><?php echo $post_msg["text"]; ?></strong>
		</div>
	<?php } ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],            
            'username',
            'email:email',
        	 array(
        			'class' => DataColumn::className(), // this line is optional
       				'label' => 'Full Name',
       				'attribute' => 'first_name',
       				'value' => function($model) { return $model->first_name  . " " .$model->middle_name  . " " . $model->last_name ;},
        	 ),
        	 //here add icons with roles
        	 array(
        	 		'class' => DataColumn::className(), // this line is optional
        	 		'label' => '<a>Roles</a>',        	 		
        	 		'value' => function($model) { return $model->first_name  . " " .$model->middle_name  . " " . $model->last_name ;},
        	 		'format'=> 'email',
        	 ),
        	// 'id',
        	// 'auth_key',
        	// 'password_hash',
        	// 'password_reset_token',
            // 'status',
            // 'created_at',
            // 'updated_at',
            // 'first_name',
        	// 'middle_name',
            // 'last_name',
            // 'gender',
            // 'salutation',            
            // 'initials',
            // 'affiliation:ntext',
            // 'signature:ntext',
            // 'orcid_id',
            // 'url:url',
            // 'phone',
            // 'fax',
            // 'mailing_address:ntext',
            // 'bio_statement:ntext',
            // 'send_confirmation',
            // 'is_admin',
            // 'is_editor',
            // 'is_reader',
            // 'is_author',
            // 'is_reviewer',
            // 'reviewer_interests:ntext',
            // 'user_image',
            // 'last_login',
            // 'country',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
