<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Report Duplicate User';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if(isset($post_msg)){ ?>
    <div class="alert alert-dismissable <?php echo "alert-".$post_msg["type"];?>" id="reportduplicate-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
	    <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>-->
	    <strong><span id="reportduplicate-section-alert-msg"><?php echo $post_msg["text"]; ?></span></strong>
	</div>
<?php } else { ?>
	<div class="alert alert-dismissable alert-danger" id="reportduplicate-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
	 	<!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>-->
		<strong><span id="reportduplicate-section-alert-msg"><?php echo "There is no available info about current status!"; ?></span></strong>
	</div>
<?php } ?>

<!-- 
<div class="site-reportduplicate">
    <h1> //Html::encode($this->title) </h1>
    <hr>


</div>
-->
