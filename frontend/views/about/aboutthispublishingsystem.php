<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About this Publishing System';
$this->params['breadcrumbs'][] = ['label' => 'About the Journal', 'url' => '../site/about'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <hr>
    	
    <div class="row">
	    <div class="container col-md-12">
    		<p>This journal uses Open Journal Systems 2.4.6.0, which is open source journal management and publishing software developed, supported, and freely distributed by the Public Knowledge Project under the GNU General Public License.</p>
    		<br>
    		<div class="col-md-12 col-md-offset-2">
    			<img src="../images/edprocesslarge.png"/>
    		</div>
		</div>
	</div>  
</div>