<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;

/* @var $this yii\web\View */
/* @var $model common\models\Issue */

$this->title = $model->title;
?>
<div class="issue-view">

    <h2><i>Issue: </i><?php echo Html::encode($this->title) ?></h2>
	<hr>
	
    <p>
        <?= Html::a('Update Issue', ['update', 'id' => $model->issue_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete Issue', ['delete', 'id' => $model->issue_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    
    <?php 
    
   		$issue = $model;
	    $issueImagesPath = Yii::$app->urlManagerCommon->createUrl('images/issues/cover.jpg');
	    if(isset($issue->cover_image) && ($issue->cover_image > 0) && isset($issue->coverimage)){
	    	$modelImage = $issue->coverimage;
	    
	    	if ($modelImage) {
	    		$issueImagesPath = Yii::$app->urlManagerCommon->createUrl('images/issues') . DIRECTORY_SEPARATOR . $model->volume_id . DIRECTORY_SEPARATOR;
	    		$issueImagesPath = $issueImagesPath . $modelImage->path;
	    	}
	    }
    
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'issue_id',            
            'title:ntext',
        	// 'volume_id',
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'volume_id',
        		'label' => 'Volume title',
        		'value' => $model->volume->title,
        		"format" => "HTML",
        	],
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'is_special_issue',
        		'value' => ($model->is_special_issue == 0) ? "<div class='glyphicon glyphicon-remove'></div>" : "<div class='glyphicon glyphicon-ok'></div>",
        		'format' => 'HTML'
        	],
            'published_on:datetime',
    		[
    			'class' => DataColumn::className(), // this line is optional
    			'attribute' => 'cover_image',
    			'value' => "<div><img class='volume-view-image' src='".$issueImagesPath."'/></div>",
    			'format' => 'HTML'
    		],
            'special_title:ntext',
            'special_editor',            
            // 'sort_in_volume',
            'created_on:datetime',
            'updated_on:datetime',
            // 'is_deleted',
        ],
    ]) ?>

</div>
