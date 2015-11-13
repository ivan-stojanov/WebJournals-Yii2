<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->id;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            'id',
            'username',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'email:email',
            'status',
            'created_at',
            'updated_at',
            'first_name',
            'last_name',
            'gender',
            'salutation',
            'middle_name',
            'initials',
            'affiliation:ntext',
            'signature:ntext',
            'orcid_id',
            'url:url',
            'phone',
            'fax',
            'mailing_address:ntext',
            'bio_statement:ntext',
            'send_confirmation',
            'is_admin',
            'is_editor',
            'is_reader',
            'is_author',
            'is_reviewer',
            'reviewer_interests:ntext',
            'user_image',
            'last_login',
            'country',
        ],
    ]) ?>

</div>
