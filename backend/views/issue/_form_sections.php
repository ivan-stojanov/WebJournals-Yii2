<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\file\FileInput;
use wbraganca\dynamicform\DynamicFormWidget;
use common\models\Image;
use yii\jui\Sortable;

/* @var $this yii\web\View */
/* @var $model common\models\Volume */
/* @var $form yii\widgets\ActiveForm */

\backend\assets\AppAsset::register($this);
$this->registerJsFile("@web/js/issueScript.js", [ 'depends' => ['backend\assets\CustomJuiAsset'], 'position' => \yii\web\View::POS_END]);

?>

<hr>
<?php /*
echo Sortable::widget([
    'items' => [
        'Item 1',
        ['content' => 'Item2'],
        [
            'content' => 'Item3',
            'options' => ['tag' => 'li'],
        ],
    ],
    'options' => ['tag' => 'ul'],
    'itemOptions' => ['tag' => 'li'],
    'clientOptions' => ['cursor' => 'move'],
]); */
?>

<div class="sections-form">
	<div id="panel-option-values" class="panel panel-default">
	    <div class="panel-heading">
	        <h3 class="panel-title"><i class="fa fa-check-square-o"></i> Sections</h3>
	    </div>
	    
		<?php DynamicFormWidget::begin([
	        'widgetContainer' => 'dynamicform_wrapper',
	        'widgetBody' => '.form-options-body',
	        'widgetItem' => '.form-options-item',
	        'min' => 1,
	        'insertButton' => '.add-item',
	        'deleteButton' => '.delete-item',
	        'model' => $modelsSection[0],
	        'formId' => 'dynamic-form',
	        'formFields' => [
	            'title'
	        ],
	    ]); ?>
	    
	    <table class="table table-bordered table-striped margin-b-none">
	        <thead>
	            <tr>
	                <th style="width: 90px; text-align: center"></th>
	                <th class="required">Section title</th>
	                <th style="width: 90px; text-align: center">Actions</th>
	            </tr>
	        </thead>
	        <tbody class="form-options-body">
	            <?php foreach ($modelsSection as $index => $modelSection): ?>
	                <tr class="form-options-item">
	                    <td class="sortable-handle text-center vcenter" style="cursor: move;">
	                        <i class="fa fa-arrows"></i>
	                    </td>
	                    <td class="vcenter">
	                        <?= $form->field($modelSection, "[{$index}]title")->label(false)->textInput(['maxlength' => 128]); ?>
	                        <?php if (!$modelSection->isNewRecord): ?>
	                            <?= Html::activeHiddenInput($modelSection, "[{$index}]section_id"); ?>
	                            <?= Html::activeHiddenInput($modelSection, "[{$index}]is_deleted"); ?>
	                        <?php endif; ?>	                       
	                    </td>
	                    <td class="text-center vcenter">
	                        <button type="button" class="delete-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
	                    </td>
	                </tr>
	            <?php endforeach; ?>
	        </tbody>
	        <tfoot>
	            <tr>
	                <td colspan="2"></td>
	                <td><button type="button" class="add-item btn btn-success btn-sm"><span class="fa fa-plus"></span> New</button></td>
	            </tr>
	        </tfoot>
	    </table>
	    <?php DynamicFormWidget::end(); ?>
	</div>
</div>
