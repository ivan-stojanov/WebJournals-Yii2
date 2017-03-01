<?php

/* @var $this yii\web\View */

$this->title = 'Admin panel';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Weclome <?= \Yii::$app->user->identity->fullName; ?>!</h1>

        <p class="lead">You have successfully logged in into your OJS account.</p>

        <br/><br/>
        
        <p class="lead">
        	Your current roles:
        	<i>
	        	<?= (Yii::$app->session->get('user.is_admin') == true) ? "admin; " : "" ?>
	        	<?= (Yii::$app->session->get('user.is_editor') == true) ? "editor; " : "" ?>
	        	<?= (Yii::$app->session->get('user.is_reviewer') == true) ? "reviewer; " : "" ?>
	        	<?= (Yii::$app->session->get('user.is_author') == true) ? "author; " : "" ?>
        	</i>
        </p>
        <p style="font-size: medium;">
        	<u>If there is any change with your roles, please sign out and log in again!</u>
        </p>
    </div>

    <div class="body-content">
    
    <?php /*$form = \yii\widgets\ActiveForm::begin(); ?>
    <?= $form->field(null, 'message')->widget(\yii\redactor\widgets\Redactor::className()) */?>

        <div class="row">
            <div class="col-lg-4">
                <h2>Announcements</h2>

                <p>If you want to see the list of announcement on the site, ordered by the date that they were pulished 
                (most recent at the top) please visit the corresponding section on the public page.</p>

                <p><a class="btn btn-default" href="<?= \Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/announcement']); ?>">Announcements &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Archive</h2>

                <p>If you want to see the list of volumes with issues that contains the published articles (except the ones that belongs 
                to the current issue), please visit the corresponding section on the public page.</p>

                <p><a class="btn btn-default" href="<?= \Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/archive']); ?>">Archive &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Contact the Admin</h2>

                <p>If you want to contact the Admin, you can do that by submitting the contact form. In order to do that
                please visit the corresponding section on the public page.</p>

                <p><a class="btn btn-default" href="<?= \Yii::$app->urlManagerFrontEnd->createAbsoluteUrl(['site/contact']); ?>">Contact &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
