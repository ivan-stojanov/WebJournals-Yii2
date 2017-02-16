<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\Article;
use common\models\ArticleReviewer;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $model->title;

\backend\assets\AppAsset::register($this);
$this->registerJsFile("@web/js/articleScript.js", [ 'depends' => ['backend\assets\CustomJuiAsset'], 'position' => \yii\web\View::POS_END]);
?>
<div class="article-view">

	<div class="alert alert-dismissable hidden-div" id="article-section-alert"> <?php /*alert-danger alert-success alert-warning */ ?>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong><span id="article-section-alert-msg"></span></strong>
	</div>	
	
	<?php 
		if($model->status == Article::STATUS_REJECTED) {
	?>
		<div class="alert alert-dismissable alert-danger">
			<strong><span>Article has been rejected!</span></strong>
		</div>	
	<?php
		} else if($model->status == Article::STATUS_ACCEPTED_FOR_PUBLICATION) {
	?>
		<div class="alert alert-dismissable alert-warning">
			<strong><span>Article has been accepted for publication! Editors/Admins should publish it!</span></strong>
		</div>	
	<?php
		} else if($model->status == Article::STATUS_PUBLISHED) {
	?>
		<div class="alert alert-dismissable alert-success">
			<strong><span>Article has been published!</span></strong>
		</div>					
	<?php
		}
	?>

    <h1><?= Html::encode($this->title) ?></h1>    

    <p>
	<?php 
	    if($user_can_modify && $model->status != Article::STATUS_REJECTED && $model->status != Article::STATUS_PUBLISHED) {
	    	$updateBtnClasses = 'btn btn-primary';	    	
	    	if($model->status != Article::STATUS_SUBMITTED && $model->status != Article::STATUS_IMPROVEMENT
	    			&& ($model->status != Article::STATUS_ACCEPTED_FOR_PUBLICATION || !$isAdminOrEditor)) {
	    		$updateBtnClasses .= ' disabled';
	    	}
	        echo Html::a('Update', ['update', 'id' => $model->article_id], [
	        	'class' => $updateBtnClasses	        		
	        ]);
	        if($model->status != Article::STATUS_ACCEPTED_FOR_PUBLICATION) {
		        echo "&nbsp;";
		        $deleteBtnClasses = 'btn btn-danger';
		        if($model->status != Article::STATUS_SUBMITTED) {
		        	$deleteBtnClasses .= ' disabled';
		        }
		        echo Html::a('Delete', ['delete', 'id' => $model->article_id], [
		            'class' => $deleteBtnClasses,
		            'data' => [
		                'confirm' => 'Are you sure you want to delete this item?',
		                'method' => 'post',
		            ],
		        ]);  
	        }
	    }
	    echo "&nbsp;";
        echo Html::a('View in PDF', ['pdfview', 'id' => $model->article_id], ['class' => 'btn btn-success']);
		if($isAdminOrEditor) {
			$reviewBtnClasses = 'btn btn-warning';
			if($model->status != Article::STATUS_SUBMITTED && $model->status != Article::STATUS_IMPROVEMENT) {
				$reviewBtnClasses .= ' disabled hidden';
			} else {
				echo "&nbsp;";
			}
			echo Html::a('Move for Review', ['moveforreview', 'id' => $model->article_id], [
				'class' => $reviewBtnClasses,
				'data' => [
					'confirm' => 'Are you sure you want to move this article into \'review\' state?',
					'method' => 'post',
				],
			]);
			
			$reviewrequiredBtnClasses = 'btn btn-warning';
			if($model->status != Article::STATUS_UNDER_REVIEW) {
				$reviewrequiredBtnClasses .= ' disabled hidden';
			} else {
				echo "&nbsp;";
			}
			echo Html::a('Move to Review Required stage', ['moveforreviewrequired', 'id' => $model->article_id], [
					'class' => $reviewrequiredBtnClasses,
					'data' => [
							'confirm' => 'Are you sure you want to disable the reviews and move this article into \'review required\' state?',
							'method' => 'post',
					],
			]);			

			$publishedBtnClasses = 'btn btn-warning';
			if($model->status != Article::STATUS_ACCEPTED_FOR_PUBLICATION) {
				$publishedBtnClasses .= ' disabled hidden';
			} else {
				echo "&nbsp;";
			}
			echo Html::a('Publish', ['moveforpublish', 'id' => $model->article_id], [
					'class' => $publishedBtnClasses,
					'data' => [
							'confirm' => 'Are you sure you want to \'publish\' this article?',
							'method' => 'post',
					],
			]);
		}
    ?>
    </p>
    
    <?php    
    	echo "<hr>";
        if($isAdminOrEditor != null && $modelCurrentUserAsReviewer != null) {        	
        	echo "<div id='editor-review-section'>";
	?>	
			<h2><i>Editor Review:</i></h2>
	<?php
			$form = ActiveForm::begin();
			echo $form->field($modelCurrentUserAsReviewer, 'short_comment')->dropDownList(
					ArticleReviewer::$STATUS_REVIEW,
					['prompt' => 'Select Status']
					);
			echo $form->field($modelCurrentUserAsReviewer, 'long_comment');
			echo Html::button('Accept for publication', ['id' => 'accept-article-btn', 'data-articleid' => $modelCurrentUserAsReviewer->article_id, 'data-reviewerid' => $modelCurrentUserAsReviewer->reviewer_id, 'class' => 'btn btn-success']);
			echo "&nbsp;&nbsp;";
			echo Html::button('Reject', ['id' => 'reject-article-btn', 'data-articleid' => $modelCurrentUserAsReviewer->article_id, 'data-reviewerid' => $modelCurrentUserAsReviewer->reviewer_id, 'class' => 'btn btn-danger']);
			echo "&nbsp;&nbsp;";
			echo Html::button('Move back for improvement', ['id' => 'improvement-article-btn', 'data-articleid' => $modelCurrentUserAsReviewer->article_id, 'data-reviewerid' => $modelCurrentUserAsReviewer->reviewer_id, 'class' => 'btn btn-warning']);
			ActiveForm::end();				
			echo "<hr>";
			echo "</div>";
    	}   
   
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
    //if($user_can_modify) {
    	$attributes = ArrayHelper::merge($attributes, [
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'status',
        		'value' =>
        			($model->status == Article::STATUS_SUBMITTED) ? 
        				"<div class='glyphicon glyphicon-book'> Submitted</div> (Article can still be edited)"
        			: (($model->status == Article::STATUS_UNDER_REVIEW) ?
        				"<div class='glyphicon glyphicon-eye-open'> Under review</div> (Article can not be edited)"
        			: (($model->status == Article::STATUS_REVIEW_REQUIRED) ?
        				"<div class='glyphicon glyphicon-eye-open'> Review required</div> (Article can not be edited)"
					: (($model->status == Article::STATUS_IMPROVEMENT) ?
        				"<div class='glyphicon glyphicon-edit'> Improvement</div> (Article can be edited again)"
        			: (($model->status == Article::STATUS_ACCEPTED_FOR_PUBLICATION) ?
        				"<div class='glyphicon glyphicon-ok-circle'> Accepted for publication</div> (Article can not be edited)"
        			: (($model->status == Article::STATUS_PUBLISHED) ?
        				"<div class='glyphicon glyphicon-ok'> Published</div> (Article can not be edited)"
        			: (($model->status == Article::STATUS_REJECTED) ?
        				"<div class='glyphicon glyphicon-remove'> Rejected</div> (Article can not be edited)"
        			: null)))))),
        		//	($model->is_archived == 0) ? "<div class='glyphicon glyphicon-remove'></div>" : "<div class='glyphicon glyphicon-ok'></div>",
        		'format' => 'HTML'
        	]
    	]);
    //}
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
        		'value' => $article_reviewers['string'],
        		//'format' => 'HTML'
        	],
    		[
    			'class' => DataColumn::className(), // this line is optional
    			'label' => 'Editors',
    			'value' => $article_editors['string'],
    			//'format' => 'HTML'
    		]    			
    	]);
    }
    	$attributes = ArrayHelper::merge($attributes, [    
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'label' => 'Authors',
        		'value' => $article_authors['string'],
        		//'format' => 'HTML'
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
    
    <?php    	
    	if($modelsArticleReviewer != null) {
    ?>
			<h2><i>Submited Review(s):</i></h2>    	  		    
	<?php    		
    	    foreach ($modelsArticleReviewer as $index => $modelArticleReviewer){
    	    	echo DetailView::widget([
    	    		'model' => $modelArticleReviewer,
    	    		'attributes' => [
    	    			[
    	    				'class' => DataColumn::className(), // this line is optional
    	    				'attribute' => 'reviewer_id',
    	    				'value' => $modelArticleReviewer->reviewer->fullName." <".$modelArticleReviewer->reviewer->email.">",
    	    				//'format' => 'HTML'
    	    			],
    	    			[
    	    				'class' => DataColumn::className(), // this line is optional
    	    				'attribute' => 'short_comment',
    	    				'value' => ArticleReviewer::$STATUS_REVIEW[$modelArticleReviewer->short_comment],
    	    				'format' => 'HTML'
    	    			],
    	    			'long_comment',
    	    			[
    	    				'class' => DataColumn::className(), // this line is optional
    	    				'attribute' => 'created_on',
    	    				'value' => (isset($modelArticleReviewer->created_on)) ? date("M d, Y, g:i:s A", strtotime($modelArticleReviewer->created_on)) : null,
    	    				'format' => 'HTML'
    	    			],
    	    			[
    	    				'class' => DataColumn::className(), // this line is optional
    	    				'attribute' => 'updated_on',
    	    				'value' => (isset($modelArticleReviewer->updated_on)) ? date("M d, Y, g:i:s A", strtotime($modelArticleReviewer->updated_on)) : null,
    	    				'format' => 'HTML'
    	    			],
    	    		],
    	    	]);
    	    }
    	}
    	
	?>

</div>
