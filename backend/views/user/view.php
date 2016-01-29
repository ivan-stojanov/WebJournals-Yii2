<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'User Details';
?>
<div class="user-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
            'phone',
            'fax',
            'mailing_address:ntext',
        	'country',
            'bio_statement:ntext',
        	'url:url',
        	'salutation',
        	'affiliation:ntext',
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
            'reviewer_interests:ntext',
        	 array(
        			'class' => DataColumn::className(), // this line is optional
        			'attribute' => 'user_image',
        			'value' => Yii::getAlias('@common')."\\images\users\\".$model->user_image,
        	 		'format' => 'image'
        	 ),        	
        	'created_at:datetime',
        	'updated_at:datetime',
        	'last_login:datetime',            
        ],
    ]) ?>

</div>
