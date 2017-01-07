<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $model->title;

?>
<div class="article-view">

    <h1><?= Html::encode($this->title) ?></h1>    

    <p>
	    <?php if($user_can_modify) { ?>
	        <?= Html::a('Update', ['update', 'id' => $model->article_id], ['class' => 'btn btn-primary']) ?>
	        <?= Html::a('Delete', ['delete', 'id' => $model->article_id], [
	            'class' => 'btn btn-danger',
	            'data' => [
	                'confirm' => 'Are you sure you want to delete this item?',
	                'method' => 'post',
	            ],
	        ]) ?>    	
	    <?php } ?>
        <?= Html::a('View in PDF', ['pdfview', 'id' => $model->article_id], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?php 
    	$attributes = [
        	'title:ntext',
        	//'article_id',
        	//'section_id',
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'section_id',
        		'label' => 'Section title',
        		'value' => (isset($model->section)) ? $model->section->title : null,
        		'format' => 'HTML'
        	],
            //'abstract:ntext',
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'abstract',        			
        		'value' => Html::a('View in PDF', ['pdfview', 'id' => $model->article_id, 'partial' => 'abstract'], ['class' => 'btn btn-info btn-xs']),
        		//'value' => (($model->abstract) && (isset($model->abstract)) && (strlen($model->abstract) > 0)) ? $model->abstract : null,
        		'format' => 'HTML'
        	],
            //'content:ntext',
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'content',
        		'value' => Html::a('View in PDF', ['pdfview', 'id' => $model->article_id, 'partial' => 'content'], ['class' => 'btn btn-info btn-xs']),
        		//'value' => (($model->content) && (isset($model->content)) && (strlen($model->content) > 0)) ? $model->content : null,
        		'format' => 'HTML'
        	],
            //'pdf_content:ntext',
            //'page_from',
            //'page_to',
            //'sort_in_section',
        ];
    /*if($user_can_modify) {
    	$attributes = ArrayHelper::merge($attributes, [
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'is_archived',
        		'value' => ($model->is_archived == 0) ? "<div class='glyphicon glyphicon-remove'></div>" : "<div class='glyphicon glyphicon-ok'></div>",
        		'format' => 'HTML'
        	]
    	]);
    }*/
    	$attributes = ArrayHelper::merge($attributes, [
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'file_attach',
        		'value' => ($model->file != null) ? "<a href='../@web/uploads/".$model->file->file_name."' download='".$model->file->file_name."'>".$model->file->file_original_name."</a>" : null,
        		'format' => 'HTML'
        	]
    	]);
    if($user_can_modify) {
    	$attributes = ArrayHelper::merge($attributes, [
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'label' => 'Reviewers',
        		'value' => $article_reviewers_string,
        		//'format' => 'HTML'
        	],
    		[
    			'class' => DataColumn::className(), // this line is optional
    			'label' => 'Editors',
    			'value' => $article_editors_string,
    			//'format' => 'HTML'
    		]    			
    	]);
    }
    	$attributes = ArrayHelper::merge($attributes, [    
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'label' => 'Authors',
        		'value' => $article_authors['string'],
        		'format' => 'HTML'
        	],
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'label' => 'Correspondent Author',
        		'value' => (isset($article_correspondent_author)) ? ($article_correspondent_author->fullName." <".$article_correspondent_author->email.">") : null,
        		//'format' => 'HTML'
        	],        		
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'label' => 'Keywords',
        		'value' => $article_keywords_string,
        		'format' => 'HTML'
        	],
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
        ]);    
    ?>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>

</div>
