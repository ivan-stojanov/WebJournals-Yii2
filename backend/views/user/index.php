<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'auth_key',
            'password_hash',
            'password_reset_token',
            // 'email:email',
            // 'status',
            // 'created_at',
            // 'updated_at',
            // 'first_name',
            // 'last_name',
            // 'gender',
            // 'salutation',
            // 'middle_name',
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
