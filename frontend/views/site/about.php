<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'About the System';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <hr>
        	
    <div class="row">
    
    	<div class="col-md-6">
    		<h3>User Roles</h3>
    		<div class="container col-md-6">
				<div class="list-group">
			  		<span class='list-group-item'>Admin</span>
			  		<span class='list-group-item'>Editor</span>
			  		<span class='list-group-item'>Reviewer</span>
			  		<span class='list-group-item'>Author</span>			  		
				</div>
			</div>    		
    	</div>
    	
    	<div class="col-md-6">
    		<h3>Entities</h3>
    		<div class="container col-md-6">
				<div class="list-group">
			  		<span class='list-group-item'>Volume</span>
			  		<span class='list-group-item'>Issue</span>
			  		<span class='list-group-item'>Section</span>
			  		<span class='list-group-item'>Article</span>			  		
				</div>
			</div>    		
    	</div>    	

    	<div class="col-md-6">
    	</div>
    	
		<div class="col-md-6">
    		<h3>Other</h3>
    		<div class="container col-md-6">
				<div class="list-group">
					<?= Html::a('Site Map', ['/about/sitemap'], ['class' => 'list-group-item']) ?>
				</div>
			</div>       		
    	</div>

    </div>

</div>
