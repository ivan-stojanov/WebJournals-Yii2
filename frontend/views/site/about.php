<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About the Journal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <hr>
        
	
    <div class="row">
    
    	<div class="col-md-6">
    		<h3>People</h3>
    		<div class="container col-md-6">
				<div class="list-group">
			  		<?= Html::a('Editorial Team', ['/about/editorial-team'], ['class'=>'list-group-item']) ?>
				</div>
			</div>    		
    	</div>   
    	
    	<div class="col-md-6">
    		<h3>Policies</h3>
    		<div class="container col-md-6">
				<div class="list-group">
					<?= Html::a('Section Policies', ['/about/policies'], ['class'=>'list-group-item']) ?>
					<?= Html::a('Open Access Policy', ['/about/policies'], ['class'=>'list-group-item']) ?>
				</div>
			</div>       		
    	</div>

    	<div class="col-md-6">
    		<h3>Submissions</h3>
    		<div class="container col-md-6">
				<div class="list-group">
 			    	<?= Html::a('Online Submissions', ['/about/submissions'], ['class'=>'list-group-item']) ?>
					<?= Html::a('Submission Preparation Checklist', ['/about/submissions'], ['class'=>'list-group-item']) ?>
					<?= Html::a('Privacy Statement', ['/about/submissions'], ['class'=>'list-group-item']) ?>
				</div>
			</div>       		
    	</div>  
    	
		<div class="col-md-6">
    		<h3>Other</h3>
    		<div class="container col-md-6">
				<div class="list-group">
 			    	<?= Html::a('Site Map', ['/about/site-map'], ['class'=>'list-group-item']) ?>
			    	<?= Html::a('About this Publishing System', ['/about/about-this-publishing-system'], ['class'=>'list-group-item']) ?>
				</div>
			</div>       		
    	</div>  
    </div>

    

    <?php /*<code><?=// __FILE__ ?></code> */ ?>
</div>
