<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
 
/* @var $this yii\web\View */
/* @var $model common\models\HomepageSection */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Homepage content';
?>

<div class="alert alert-dismissable hidden-div" id="homepage-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong><span id="homepage-section-alert-msg"></span></strong>
</div>

<?php if(isset($model)) {?>

<div class="homepage-section-form">

    <?php $form = ActiveForm::begin(); ?>

	<?php echo $form->field($model, 'section_content')->widget(TinyMce::className(), [
	    'options' => ['rows' => 15],
	    'language' => 'en_GB',
	    'clientOptions' => [
	    	'browser_spellcheck' => true,
			'theme' => "modern",
		    'plugins' => [
		        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
		        "searchreplace wordcount visualblocks visualchars code fullscreen",
		        "insertdatetime media nonbreaking save table contextmenu directionality",
		        "emoticons template paste textcolor colorpicker textpattern imagetools"
		    ],
		    'toolbar1' => "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
		    'toolbar2' => "print preview media | forecolor backcolor emoticons",
		    'image_advtab' => true,			    
	    ]
	]);?>

    <div class="form-group">
     	<?php echo Html::a('Back', 'home', ['class' => 'btn btn-success']) ?>
        <?php echo Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>      
    </div>

    <?php ActiveForm::end(); ?>
</div>	

<?php } else { ?>
	<p>No Homepage content page exists</p>
<?php }?>
<hr>