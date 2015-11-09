<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Editorial Team';
$this->params['breadcrumbs'][] = ['label' => 'About the Journal', 'url' => '../site/about'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <hr>
    	
    <div class="row">
	    <div class="container col-md-12">
	    	<div class="col-md-6">
	    		<h3>Editors</h3>
	    		<div class="container col-md-6">
					<div class="list-group">
						<a href="mailto:ivan.stojanov1990@gmail.com" class="list-group-item" title="Send Email to: ivan.stojanov1990@gmail.com">Ivan.S <em class="icon-fixed-width glyphicon glyphicon-envelope pull-right"></em></a>
					</div>
				</div>    		
	    	</div>
		</div>
	</div>  
</div>