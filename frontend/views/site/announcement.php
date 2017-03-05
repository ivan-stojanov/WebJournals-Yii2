<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Announcements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact text-center">
    <h1><?= Html::encode($this->title) ?></h1>
    <hr>

	<?php if(isset($model) && count($model)>0) {?>
	<div>
     	<?php foreach ($model as $modelAnnouncement){
     		$date = date_create($modelAnnouncement->created_on);
       		$id = $modelAnnouncement->announcement_id;
       		$title = (strlen($modelAnnouncement->title) > 40) ? 
       					(substr($modelAnnouncement->title, 0, 40)."...") : ($modelAnnouncement->title);
       		$description = (strlen($modelAnnouncement->description) > 40) ? 
       					(substr($modelAnnouncement->description, 0, 40)."...") : ($modelAnnouncement->description);
       		$created_on = $modelAnnouncement->created_on;
      	?>
       	<div>
       		<h3 class="announcement-front-item"><?php echo $title?></h3><?php /* <a href="update/<?php echo $id?>"></a> */ ?>           	
        </div>
        <div>
        	<p class="announcement-date-label"><?php echo date_format($date,"Y/m/d h:i:s A")?></p>
        </div>
       	<div>
       		<p class="announcement-front-desc"><?php echo $description?></p>          	
        </div>        
        <?php echo Html::a('Read more', ['site/announcementdetails/'.$id], ['class' => 'btn btn-success']) ?>
		<hr>
        <?php } ?>
	</div>
	<?php } else { ?>
		<div class='serach-section-empty-result'>No Announcements are found!</div>		
		<hr>
	<?php }?>
</div>
