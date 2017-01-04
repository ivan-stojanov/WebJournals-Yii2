<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */
?>
Dear Admin,

The "contact form" on the site "<?= \Yii::$app->name ?>" was submitted.

These are the details related to the submitted message:

Sender name: <?= $contactForm->name ?>
Sender email: <?= $contactForm->email ?>
Message subject: <?= $contactForm->subject ?>

Message body: <?= $contactForm->body ?>
