<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Site Map';
$this->params['breadcrumbs'][] = ['label' => 'About the Journal', 'url' => '../site/about'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <hr>
    	
    <div class="row">
	    <div class="container col-md-12">
	    	<ul>
				<li><h4><?= Html::a('Home', ['/site/']) ?></h4></li>
				    <ul>
				        <li><?= Html::a('Open Journal Systems Demonstration Journal', ['/site/']) ?>
				            <ul>
				                <li><?= Html::a('About', ['/site/about']) ?></li>		                
				                <!-- <ul> -->
									<li><?= Html::a('Login', ['/site/login']) ?></li>
									<li><?= Html::a('Register', ['/site/signup']) ?></li>
				                <!-- </ul> -->
				                <li>Search</li>
				                <ul>
									<li>By Author</li>
									<li>By Title</li>
				                </ul>             
				                <li>Issues</li>
				                <ul>
									<li>Current Issue</li>
									<li>Archives</li>
				                </ul>                
				            </ul>
				        </li>
				    </ul>			    
				<li><h4><?= Html::a('Open Journal Systems', 'http://pkp.sfu.ca/ojs/') ?></h4></li>
				<li><h4><a onclick="openHelpPopupWindow()">Help</a></h4></li>
			</ul>
		</div>
	</div>

</div>

<script>
	function openHelpPopupWindow(){
		var popup_w = 600;
		var popup_h = 400;
		var left = (screen.width/2)-(popup_w/2);
		var top = (screen.height/2)-(popup_h/2);
		window.open('http://pkp.sfu.ca/ojs/', 'name','width=' + popup_w + ', height=' + popup_h + ', left=' + left + ', top=' + top);
	}
</script>