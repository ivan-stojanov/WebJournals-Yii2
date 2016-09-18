<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;

/* @var $this yii\web\View */
/* @var $model common\models\Section */

$this->title = $model->title;
?>
<div class="section-view">

    <h2><i>Section: </i><?php echo Html::encode($this->title) ?></h2>
    <hr>

    <p>
        <?= Html::a('Update Section', ['update', 'id' => $model->section_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete Section', ['delete', 'id' => $model->section_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
		 <?= Html::a('View Volume', ['volume/view', 'id' => $model->issue->volume_id], ['class' => 'btn btn-default']) ?>
		 <?= Html::a('View Issue', ['issue/view', 'id' => $model->issue_id], ['class' => 'btn btn-info']) ?>
    </p>   

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'section_id',
            //'issue_id',
            'title:ntext',
            //'sort_in_issue',
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'created_on',
        		'value' => (isset($model->created_on)) ? date("M d, Y, g:i:s A", strtotime($model->created_on)) : null,        			
        		'format' => 'HTML'
        	],
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'updated_on',
        		'value' => (isset($model->updated_on)) ? date("M d, Y, g:i:s A", strtotime($model->updated_on)) : null, 
        		'format' => 'HTML'
        	],
            //'is_deleted',
        ],
    ]) ?>
    
    <?php 
    	if(isset($model->articles) && (count($model->articles) > 0)){
    ?>
			<h2><i>Article(s):</i></h2>
	    	<hr>    		    
    <?php
    		foreach ($model->articles as $index => $article){
    ?>
				<p>
					<?= Html::a('View Article', ['article/view/'.$article->article_id], ['class' => 'btn btn-success']) ?>
    				<?= Html::a('Update Article', ['article/update/'.$article->article_id], ['class' => 'btn btn-primary']) ?>
				</p>
    <?php
			    echo DetailView::widget([
			    	'model' => $article,
			    	'attributes' => [			    			
			    		'title:ntext',
			    		//'created_on:datetime',
			    		//'updated_on:datetime',
			    		[
			    			'class' => DataColumn::className(), // this line is optional
			    			'attribute' => 'created_on',
			    			'value' => (isset($article->created_on)) ? date("M d, Y, g:i:s A", strtotime($article->created_on)) : null,
			    			'format' => 'HTML'
			    		],
			    		[
			    			'class' => DataColumn::className(), // this line is optional
			    			'attribute' => 'updated_on',
			    			'value' => (isset($article->updated_on)) ? date("M d, Y, g:i:s A", strtotime($article->updated_on)) : null,
			    			'format' => 'HTML'
			    		],
			    	],
			    ]);    
    		}    
    	} else {
    ?>
    		<hr>
    		<h2>There are no any <i>Articles</i> for this <i>Section</i> yet!</h2>
	    	<hr>
    <?php		
    	}
    ?>    

</div>
