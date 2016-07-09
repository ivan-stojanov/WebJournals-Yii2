<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
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

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($modelArticle, 'title')->textarea(['rows' => 6]) ?>

    <?= $form->field($modelArticle, 'abstract')->textarea(['rows' => 6]) ?>

    <?= $form->field($modelArticle, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($modelArticle, 'pdf_content')->textarea(['rows' => 6]) ?>

    <?= $form->field($modelArticle, 'page_from')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelArticle, 'page_to')->textInput(['maxlength' => true]) ?>

    <?= $form->field($modelArticle, 'sort_in_section')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($modelArticle->isNewRecord ? 'Create' : 'Update', ['class' => $modelArticle->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
