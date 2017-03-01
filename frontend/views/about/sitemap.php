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
				<li><h4><?= Html::a('Home', ['/site/index']) ?></h4></li>
<?php /* 
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
*/ ?> 			    
				<li>
					<h4><?= Html::a('About', ['/site/about']) ?></h4>
					<ul>
					 	<li><?= Html::a('Site Map', ['/about/sitemap']) ?></li>
					</ul>
				</li>
				<li><h4><?= Html::a('Contact Us', ['/site/contact']) ?></h4></li>
				<li>
					<h4><?= Html::a('Search', ['/search/index']) ?></h4>
					<ul>
					 	<li><?= Html::a('Browse by Volume', ['/search/index?type=volume']) ?></li>
					 	<li><?= Html::a('Browse by Issue', ['/search/index?type=issue']) ?></li>
					 	<li><?= Html::a('Browse by Section', ['/search/index?type=section']) ?></li>
					 	<li><?= Html::a('Browse by Article', ['/search/index?type=article']) ?></li>
					 	<li><?= Html::a('Browse by Keyword', ['/search/index?type=keyword']) ?></li>
					 	<li><?= Html::a('Browse by Author', ['/search/index?type=user']) ?></li>
					</ul>
				</li>
				<li><h4><?= Html::a('Current', ['/site/current']) ?></h4></li>
				<li><h4><?= Html::a('Archive', ['/site/archive']) ?></h4></li>
				<li><h4><?= Html::a('Blog', ['/site/announcement']) ?></h4></li>
			</ul>
		</div>
	</div>

</div>

<script>
	/*function openHelpPopupWindow(){
		var popup_w = 600;
		var popup_h = 400;
		var left = (screen.width/2)-(popup_w/2);
		var top = (screen.height/2)-(popup_h/2);
		window.open('http://pkp.sfu.ca/ojs/', 'name','width=' + popup_w + ', height=' + popup_h + ', left=' + left + ', top=' + top);
	}*/
</script>