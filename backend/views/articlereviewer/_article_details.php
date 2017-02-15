<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use common\models\Article;
?>    
    
<p>
<?php 
	echo "&nbsp;";
    echo Html::a('View in PDF', ['pdfview', 'id' => $model->article_id], ['class' => 'btn btn-success']);
?>
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
       			: null))))),
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
    ]);    
?>
    
<?= DetailView::widget([
    'model' => $model,
    'attributes' => $attributes,
]) ?>