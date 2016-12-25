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
<h1>	
	<?php 	
		$params = Yii::$app->request->queryParams;
		if(isset($params) && isset($params['type'])){
        	if($params['type'] == 'admin'){
        		echo "Admin List";
        	} else if($params['type'] == 'author'){
        		echo "Author List";
        	} else if($params['type'] == 'editor'){
        		echo "Editor List";
        	} else if($params['type'] == 'reviewer'){
        		echo "Reviewer List";
        	} else {
        		echo "User List";
        	}
        } else {
        	echo "User List";
        }
	?>	
</h1>
<hr>

	<?php /* ?>
	    <p>
	        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
	    </p>
	<?php */
	
	$columns = [
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
        	 /*array(
        	 		'class' => DataColumn::className(), // this line is optional
        	 		'label' => 'Roles',        	 		
        	 		'value' => function($model) { return "reader/editor/reviewer/author/admin" ;},
        	 		'format'=> 'text',
        	 ),*/         	 
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

            // ['class' => 'yii\grid\ActionColumn'],
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
    ]); 
	 ?>
</div>

<?php 
/*$dir_icons = Yii::getAlias('@common')."\\images\icons\\";
var_dump($dir_icons);
$script = <<< JS
	$('.grid-view:nth-of-type(1) table:nth-of-type(1) tr:nth-of-type(2) td:nth-of-type(5)').text(".$dir_icons.");
JS;
$this->registerJs($script, \yii\web\View::POS_END);*/
?>