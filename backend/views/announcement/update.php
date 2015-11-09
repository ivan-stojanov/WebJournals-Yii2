<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Announcement */

$this->title = 'Update Announcement';
$shortTitle = (strlen($model->title) > 40) ? (substr($model->title, 0, 40)."...") : ($model->title);
?>
<div class="announcement-update">

    <h2 title="<?php echo'Update Announcement: '.$model->title?>">
    	<?= Html::encode('Update Announcement: '.$shortTitle) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
