<?php

use common\models\Issue;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Section */
/* @var $form yii\widgets\ActiveForm */
?>

<?php if(isset($post_msg)){ ?>
    <div class="alert alert-dismissable <?php echo "alert-".$post_msg["type"];?>" id="homepage-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    <strong><span id="homepage-section-alert-msg"></span><?php echo $post_msg["text"]; ?></strong>
	</div>
<?php } ?>

<h1><?= Html::encode($this->title) ?></h1>
<hr>

<div class="section-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($modelSection, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelSection, 'issue_id')->dropDownList(
    		ArrayHelper::map(Issue::find()->all(), 'issue_id', 'title'),
    		['prompt' => 'Select Issue']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton($modelSection->isNewRecord ? 'Create' : 'Update', ['class' => $modelSection->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
