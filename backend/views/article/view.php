<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="article-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->article_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->article_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('View in PDF', ['pdfview', 'id' => $model->article_id], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        	'title:ntext',
        	//'article_id',
        	//'section_id',
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'section_id',
        		'label' => 'Section title',
        		'value' => $model->section->title,
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
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'label' => 'Reviewers',
        		'value' => $article_reviewers_string,
        		'format' => 'HTML'
        	],
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'label' => 'Authors',
        		'value' => $article_authors_string,
        		'format' => 'HTML'
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
        ],
    ]) ?>

</div>
