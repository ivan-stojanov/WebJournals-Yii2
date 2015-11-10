<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Announcements Details';
$this->params['breadcrumbs'][] = ['label' => 'Announcements', 'url' => '../../site/announcement'];

$shortTitle = (strlen($model->title) > 40) ? (substr($model->title, 0, 40)."...") : ($model->title);
$date = date_create($model->created_on);

$this->params['breadcrumbs'][] = $shortTitle;
?>
<div class="announcement-view text-center">

    <h3 class="announcement-front-item" title="<?php echo $model->title?>"><?= Html::encode($shortTitle) ?></h3>
    <p class="announcement-date-label">Published On</p>
    <p class="announcement-date-value"><?php echo date_format($date,"Y-m-d h:i:s A")?></p>
	
	<p class="announcement-content-value"><?php echo $model->content?></p>

</div>

<div class="form-group">
	<?php echo Html::a('Back', ['site/announcement'], ['class' => 'btn btn-success']) ?>
</div>
