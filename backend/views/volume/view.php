<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;

/* @var $this yii\web\View */
/* @var $model common\models\Volume */

$this->title = $model->title;
?>
<div class="volume-view">

	
    <h2><i>Volume: </i><?php echo Html::encode($this->title) ?></h2>
    <hr>

    <p>
        <?= Html::a('Update Volume', ['update', 'id' => $model->volume_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete Volume', ['delete', 'id' => $model->volume_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'volume_id',
            'title:ntext',
            'year',
            'created_on:datetime',
            'updated_on:datetime',
        	/*[
        			'class' => DataColumn::className(), // this line is optional
        			'attribute' => 'is_deleted',
        			'value' => ($model->is_deleted == 0) ? "<div class='glyphicon glyphicon-remove'></div>" : "<div class='glyphicon glyphicon-ok'></div>",
        			'format' => 'HTML'
        	],*/
        ],
    ]) ?>
    
    <h2><i>Issue(s):</i></h2>
    <hr>
    
    <?php     
    	foreach ($model->issues as $index => $issue){
    		
    		$issueImagesPath = Yii::$app->urlManagerCommon->createUrl('images/issues/cover.jpg');
    		if(isset($issue->cover_image) && ($issue->cover_image > 0) && isset($issue->coverimage)){
    			$modelImage = $issue->coverimage;
    		
    			if ($modelImage) {
    				$issueImagesPath = Yii::$app->urlManagerCommon->createUrl('images/issues') . DIRECTORY_SEPARATOR . $model->volume_id . DIRECTORY_SEPARATOR;
    				$issueImagesPath = $issueImagesPath . $modelImage->path;
    			}
    		} 
	?>	
    		<p>
    		    <?= Html::a('View Issue', ['issue/view/'.$issue->issue_id], ['class' => 'btn btn-success']) ?>
    		    <?= Html::a('Update Issue', ['issue/update/'.$issue->issue_id], ['class' => 'btn btn-primary']) ?>
    		</p>
    <?php 		
    		echo DetailView::widget([
    			'model' => $issue,
    			'attributes' => [
    				'title:ntext',
    				[
    					'class' => DataColumn::className(), // this line is optional
    					'attribute' => 'cover_image',
    					'value' => "<div><img class='volume-view-image' src='".$issueImagesPath."'/></div>",
    					'format' => 'HTML'
    				],
    				'created_on:datetime',
    				'updated_on:datetime',
		        ],
		    ]);
    	}
    ?>

</div>
