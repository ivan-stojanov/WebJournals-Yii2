<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Announcement */

$this->title = $model->title;
$shortTitle = (strlen($model->title) > 40) ? (substr($model->title, 0, 40)."...") : ($model->title);
$date = date_create($model->created_on);
?>
<div class="announcement-view text-center">

    <h3 title="<?php echo $model->title?>"><?= Html::encode($shortTitle) ?></h3>
    <p class="announcement-date-label">Published On</p>
    <p class="announcement-date-value"><?php echo date_format($date,"Y-m-d h:i:s A")?></p>
	
	<p class="announcement-content-value"><?php echo $model->content?></p>

</div>

<div class="form-group">
	<?php echo Html::a('Back', ['announcement/index'], ['class' => 'btn btn-success']) ?>
	<?php echo Html::a('Update', ['announcement/update/'.$model->announcement_id], ['class' => 'btn btn-primary']) ?>
</div>