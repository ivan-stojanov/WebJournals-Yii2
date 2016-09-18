<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\jui\Sortable;

/* @var $this yii\web\View */
/* @var $model common\models\Volume */
/* @var $form yii\widgets\ActiveForm */

\backend\assets\AppAsset::register($this);
$this->registerJsFile("@web/js/sectionScript.js", [ 'depends' => ['backend\assets\CustomJuiAsset'], 'position' => \yii\web\View::POS_END]);

?>

<hr>

<div class="articles-form">
	<div id="panel-option-values" class="panel panel-default">
	    <div class="panel-heading">
	        <h3 class="panel-title"><i class="fa fa-check-square-o"></i> Articles</h3>
	    </div>
	    
		<?php DynamicFormWidget::begin([
	        'widgetContainer' => 'dynamicform_wrapper',
	        'widgetBody' => '.form-options-body',
	        'widgetItem' => '.form-options-item',
	        'min' => 1,
	        'insertButton' => '.add-item',
	        'deleteButton' => '.delete-item',
	        'model' => $modelsArticle[0],
	        'formId' => 'dynamic-form',
	        'formFields' => [
	            'title'
	        ],
	    ]); ?>
	    
	    <table class="table table-bordered table-striped margin-b-none">
	        <thead>
	            <tr>
	                <th style="width: 90px; text-align: center"></th>
	                <th class="required">Article title</th>
	                <th style="width: 90px; text-align: center">Actions</th>
	            </tr>
	        </thead>
	        <tbody class="form-options-body">
	            <?php foreach ($modelsArticle as $index => $modelArticle): ?>
	                <tr class="form-options-item">
	                    <td class="sortable-handle text-center vcenter" style="cursor: move;">
	                        <i class="fa fa-arrows"></i>
	                    </td>
	                    <td class="vcenter">
	                        <?= $form->field($modelArticle, "[{$index}]title")->label(false)->textInput(['maxlength' => 128]); ?>
	                        <?php if (!$modelArticle->isNewRecord): ?>
	                            <?= Html::activeHiddenInput($modelArticle, "[{$index}]article_id"); ?>
	                            <?= Html::activeHiddenInput($modelArticle, "[{$index}]is_deleted"); ?>
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
