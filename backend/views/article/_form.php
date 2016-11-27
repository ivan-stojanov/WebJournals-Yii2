<?php

use common\models\Section;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\tinymce\TinyMce;
use kartik\select2\Select2;

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
    
    <?= $form->field($modelArticle, 'title')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($modelArticle, 'section_id')->dropDownList(
    		ArrayHelper::map(Section::find()->all(), 'section_id', 'volumeissuesectiontitle'),
    		['prompt' => 'Select Section']
    )->label('Volume name >> Issue name >> Section name') ?>

    <?php echo $form->field($modelArticle, 'abstract')->widget(TinyMce::className(), [
	    'options' => ['rows' => 5],
	    'language' => 'en_GB',
	    'clientOptions' => [
			'theme' => "modern",
		    'plugins' => [
		        "advlist autolink lists link image charmap preview hr anchor pagebreak",
		        "searchreplace wordcount visualblocks visualchars code",
		        "insertdatetime media nonbreaking save table contextmenu directionality",
		        "template paste textcolor colorpicker textpattern imagetools"
		    ],
		    'toolbar1' => "styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
		    'toolbar2' => "undo redo | link image | print preview media | forecolor backcolor emoticons",
		    'image_advtab' => true,
	    	'content_css' => './../../css/myTinyMceLayout.css',
	    ]    	
	]);?> 

    <?php echo $form->field($modelArticle, 'content')->widget(TinyMce::className(), [
	    'options' => ['rows' => 15],
	    'language' => 'en_GB',
	    'clientOptions' => [
			'theme' => "modern",
		    'plugins' => [
		        "advlist autolink lists link image charmap preview hr anchor pagebreak",
		        "searchreplace wordcount visualblocks visualchars code",
		        "insertdatetime media nonbreaking save table contextmenu directionality",
		        "template paste textcolor colorpicker textpattern imagetools"
		    ],
		    'toolbar1' => "styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
		    'toolbar2' => "undo redo | link image | print preview media | forecolor backcolor emoticons",
		    'image_advtab' => true,	
	    	'content_css' => './../../css/myTinyMceLayout.css',
	    ]
	]);?>
	
	<?php /*var_dump($modelArticle->keywords);*/ echo $form->field($modelArticle, 'post_keywords')->widget(Select2::classname(), [ //echo Select2::widget([
	    'name' => 'kv-state-230',    	
	    'data' => $modelKeyword->getKeywordsInAssociativeArray(),
    	'maintainOrder' => true,
	    'options' => ['placeholder' => 'Select a keywords ...', 'multiple' => true],
	    'pluginOptions' => [
	        'allowClear' => true
	    ],
	]);?>
	
    <?php /* <?= $form->field($modelArticle, 'pdf_content')->textarea(['rows' => 6]) ?> */ ?>

    <?php /* <?= $form->field($modelArticle, 'page_from')->textInput(['maxlength' => true]) ?> */ ?>

    <?php /* <?= $form->field($modelArticle, 'page_to')->textInput(['maxlength' => true]) ?> */ ?>

    <?php /* <?= $form->field($modelArticle, 'sort_in_section')->textInput() ?> */ ?>

    <div class="form-group">
        <?= Html::submitButton($modelArticle->isNewRecord ? 'Create' : 'Update', ['class' => $modelArticle->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
