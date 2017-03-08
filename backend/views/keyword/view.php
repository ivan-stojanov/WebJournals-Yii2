<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\DataColumn;
use common\models\ArticleKeyword;
use common\models\ArticleReviewer;
use common\models\ArticleAuthor;
use common\models\ArticleEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Keyword */

$this->title = $modelKeyword->content;
?>
<div class="keyword-view">

    <h2><i>Keyword: </i><?php echo Html::encode($this->title) ?></h2>
	<hr>

    <p>
    	<?php 
    	if($user_can_modify) {
    			echo Html::a('Update Keyword', ['update', 'id' => $modelKeyword->keyword_id], ['class' => 'btn btn-primary']);
    			echo "&nbsp;";
    		if(!$modelKeyword->is_deleted){
    			echo Html::a('Archive Keyword Usage', ['archive', 'id' => $modelKeyword->keyword_id], [
    					'class' => 'btn btn-danger',
    					'data' => [
    							'confirm' => 'Are you sure you want to archive this Keyword? It will be no longer available for further usage!',
    							'method' => 'post',
    					],
    			]);
    		} else {
    			echo Html::a('Unarchive Keyword Usage', ['unarchive', 'id' => $modelKeyword->keyword_id], [
    					'class' => 'btn btn-success',
    					'data' => [
    							'confirm' => 'Are you sure you want to unarchive this Keyword? It will be available for further usage again!',
    							'method' => 'post',
    					],
    			]);    		
    		}    		
    	}    
		?>
    </p>

    <?= DetailView::widget([
        'model' => $modelKeyword,
        'attributes' => [
            //'keyword_id',
            'content:ntext',      		
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'created_on',
        		'value' => (isset($modelKeyword->created_on)) ? date("M d, Y, g:i:s A", strtotime($modelKeyword->created_on)) : null,        			
        		'format' => 'HTML'
        	],
        	[
        		'class' => DataColumn::className(), // this line is optional
        		'attribute' => 'updated_on',
        		'value' => (isset($modelKeyword->updated_on)) ? date("M d, Y, g:i:s A", strtotime($modelKeyword->updated_on)) : null, 
        		'format' => 'HTML'
        	],
            //'is_deleted',
        ],
    ]) ?>
    
    <?php 
    	$count_printed = 0;
    	if(isset($modelKeyword->articles) && (count($modelKeyword->articles) > 0)) {
    		
			echo "<h2><i>Related Article(s):</i></h2>";
			
    		foreach ($modelKeyword->articles as $index => $article) {
    			//$article_reviewers = ArticleReviewer::getReviewersForArticleString($article->article_id);
    			$article_authors = ArticleAuthor::getAuthorsForArticleString($article->article_id);
    			$article_keywords_string = ArticleKeyword::getKeywordsForArticleString($article->article_id)['string'];    
    			$article_editors = ArticleEditor::getEditorsForArticleString($article->article_id); 
    			$article_reviewers = ArticleReviewer::getReviewersForArticleString($article->article_id);    			 
    			
    			$current_user_id = ','.Yii::$app->user->id.',';
    			$isEditor = ((strpos($article_editors['ids'], $current_user_id) !== false) && Yii::$app->session->get('user.is_editor'));
    			$isReviewer = ((strpos($article_reviewers['ids'], $current_user_id) !== false) && Yii::$app->session->get('user.is_reviewer'));
    			$user_can_modify = (strpos($article_authors['ids'], $current_user_id) !== false);
    			$user_can_modify = ($user_can_modify || Yii::$app->session->get('user.is_admin'));
    			$user_can_modify = ($user_can_modify || $isEditor || $isReviewer);
    			
    			if($user_can_modify) {
    				if($index == 0) {
    					echo "<hr>";
    				}    				
    				$count_printed++;

		    		echo "<p>";
		    		echo Html::a('View Article', ['article/view/'.$article->article_id], ['class' => 'btn btn-success']);
		    		if($user_can_modify && !$isReviewer) {
		    			echo Html::a('Update Article', ['article/update/'.$article->article_id], ['class' => 'btn btn-primary']);
		    		}		    			
					echo "</p>";
  				
				    echo DetailView::widget([
				    	'model' => $article,
				    	'options' => ['class' => 'table table-striped table-bordered detail-view keyword-article-table'],
				    	'attributes' => [			    			
				    		'title:ntext',
				    		[
				    			'class' => DataColumn::className(), // this line is optional
				    			'label' => 'Authors',
				    			'value' => $article_authors['string'],
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
    		}    
    	} 
    	if ($count_printed == 0){
    ?>
    		<hr>
    		<h2>There are no any related <i>Articles</i> for this <i>Keyword</i> yet!</h2>
	    	<hr>
    <?php		
    	}
    ?>    

</div>
