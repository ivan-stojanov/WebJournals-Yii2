<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
?>
<div class="contact-form">
    <p>Dear Admin,</p>

    <p>The contact form on the site "<?= \Yii::$app->name ?>" has been submitted.</p>
    
    <p>These are the details related to the submitted message:</p>
    
    <p>
    	Sender name: <?= $contactForm->name ?><br>
    	Sender email: <?= $contactForm->email ?><br>
    	Message subject: <?= $contactForm->subject ?><br><br>
    	Message body: <?= $contactForm->body ?>
    </p>
</div>
