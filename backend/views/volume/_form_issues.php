<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\JuiAsset;
use yii\web\JsExpression;
use kartik\file\FileInput;
use wbraganca\dynamicform\DynamicFormWidget;
use common\models\Image;

/* @var $this yii\web\View */
/* @var $model common\models\Volume */
/* @var $form yii\widgets\ActiveForm */

\backend\assets\AppAsset::register($this);
$this->registerJsFile("@web/js/volumeScript.js", [ 'depends' => ['\yii\web\JqueryAsset'], 'position' => \yii\web\View::POS_END]);
?>

<hr>

<div class="issues-form">
	<div id="panel-option-values" class="panel panel-default">
	    <div class="panel-heading">
	        <h3 class="panel-title"><i class="fa fa-check-square-o"></i> Issues</h3>
	    </div>
	    
		<?php DynamicFormWidget::begin([
	        'widgetContainer' => 'dynamicform_wrapper',
	        'widgetBody' => '.form-options-body',
	        'widgetItem' => '.form-options-item',
	        'min' => 1,
	        'insertButton' => '.add-item',
	        'deleteButton' => '.delete-item',
	        'model' => $modelsIssue[0],
	        'formId' => 'dynamic-form',
	        'formFields' => [
	            'title',
	            'cover_image'
	        ],
	    ]); ?>
	    
	    <table class="table table-bordered table-striped margin-b-none">
	        <thead>
	            <tr>
	                <th style="width: 90px; text-align: center"></th>
	                <th class="required">Issue title</th>
	                <th style="width: 188px;">Cover image</th>
	                <th style="width: 90px; text-align: center">Actions</th>
	            </tr>
	        </thead>
	        <tbody class="form-options-body">
	            <?php foreach ($modelsIssue as $index => $modelIssue): ?>
	                <tr class="form-options-item">
	                    <td class="sortable-handle text-center vcenter" style="cursor: move;">
	                        <i class="fa fa-arrows"></i>
	                    </td>
	                    <td class="vcenter">
	                        <?= $form->field($modelIssue, "[{$index}]title")->label(false)->textInput(['maxlength' => 128]); ?>
	                    </td>
	                    <td>
	                        <?php if (!$modelIssue->isNewRecord): ?>
	                            <?= Html::activeHiddenInput($modelIssue, "[{$index}]id"); ?>
	                            <?= Html::activeHiddenInput($modelIssue, "[{$index}]image_id"); ?>
	                            <?= Html::activeHiddenInput($modelIssue, "[{$index}]deleteImg"); ?>
	                        <?php endif; ?>
	                         <?php
	                            $modelImage = Image::findOne(['image_id' => $modelIssue->cover_image]);
	                            $initialPreview = [];
	                            if ($modelImage) {
	                                $pathImg = Yii::$app->fileStorage->baseUrl . '/' . $modelImage->path;
	                                $initialPreview[] = Html::img($pathImg, ['class' => 'file-preview-image']);
	                            }
	                        ?>
	                        <?= $form->field($modelIssue, "[{$index}]cover_image")->label(false)->widget(FileInput::classname(), [
	                            'options' => [
	                                'multiple' => false,
	                                'accept' => 'image/*',
	                                'class' => 'optionvalue-img'
	                            ],
	                            'pluginOptions' => [
	                                'previewFileType' => 'image',
	                                'showCaption' => false,
	                                'showUpload' => false,
	                                'browseClass' => 'btn btn-default btn-sm',
	                                'browseLabel' => ' Pick image',
	                                'browseIcon' => '<i class="glyphicon glyphicon-picture"></i>',
	                                'removeClass' => 'btn btn-danger btn-sm',
	                                'removeLabel' => ' Delete',
	                                'removeIcon' => '<i class="fa fa-trash"></i>',
	                                'previewSettings' => [
	                                    'image' => ['width' => '138px', 'height' => 'auto']
	                                ],
	                                'initialPreview' => $initialPreview,
	                                'layoutTemplates' => ['footer' => '']
	                            ]
	                        ]) ?>
	                       
	                    </td>
	                    <td class="text-center vcenter">
	                        <button type="button" class="delete-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
	                    </td>
	                </tr>
	            <?php endforeach; ?>
	        </tbody>
	        <tfoot>
	            <tr>
	                <td colspan="3"></td>
	                <td><button type="button" class="add-item btn btn-success btn-sm"><span class="fa fa-plus"></span> New</button></td>
	            </tr>
	        </tfoot>
	    </table>
	    <?php DynamicFormWidget::end(); ?>
	</div>
</div>