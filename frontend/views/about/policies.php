<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Editorial Policies';
$this->params['breadcrumbs'][] = ['label' => 'About the Journal', 'url' => '../site/about'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <hr>
    
	<div class="container">
	    <div class="row">
		    <div class="col-md-12">
		     	<div id="accordion-first" class="clearfix">
    	            <div class="accordion" id="accordion2">
                       	<div class="accordion-group" id="SectionPolicies">
                           	<div class="accordion-heading">
                          		<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                               		<em class="icon-fixed-width glyphicon glyphicon-plus"></em>Section Policies
                           		</a>
                           	</div>
                           	<div style="height: 0px;" id="collapseOne" class="accordion-body collapse">
                           		<div class="accordion-inner">
                           			<br>
                               		<div class="row">
                               			<div class="col-md-12">
                               				<h4>Articles</h4>
                               				<div class="col-md-4">
                               					<em class="icon-fixed-width glyphicon glyphicon-ok"></em>  Open Submissions
                               				</div>
                               				<div class="col-md-4 glyphicon">
                               					<em class="icon-fixed-width glyphicon glyphicon-ok"></em>  Indexed
                               				</div>
                               				<div class="col-md-4 glyphicon">
                               					<em class="icon-fixed-width glyphicon glyphicon-ok"></em>  Peer Reviewed
                               				</div>
                               			</div>
                               			<div class="col-md-12">&nbsp;</div>
                               			<div class="col-md-12">
                               				<h4>Multimedia</h4>
                               				<div class="col-md-4">
                               					<em class="icon-fixed-width glyphicon glyphicon-remove"></em>  Open Submissions
                               				</div>
                               				<div class="col-md-4 glyphicon">
                               					<em class="icon-fixed-width glyphicon glyphicon-ok"></em>  Indexed
                               				</div>
                               				<div class="col-md-4 glyphicon">
                               					<em class="icon-fixed-width glyphicon glyphicon-remove"></em>  Peer Reviewed
                               				</div>                              				
                               			</div>
                               			<div class="col-md-12">&nbsp;</div>
                               			<div class="col-md-12">
                               				<h4>Reviews</h4>
                               				<div class="col-md-4">
                               					<em class="icon-fixed-width glyphicon glyphicon-remove"></em>  Open Submissions
                               				</div>
                               				<div class="col-md-4 glyphicon">
                               					<em class="icon-fixed-width glyphicon glyphicon-ok"></em>  Indexed
                               				</div>
                               				<div class="col-md-4 glyphicon">
                               					<em class="icon-fixed-width glyphicon glyphicon-remove"></em>  Peer Reviewed
                               				</div>                              				
                               			</div>
                               		</div>
                           		</div>
                       		</div>
        				</div>
        				<hr>
                        <div class="accordion-group" id="OpenAccessPolicy">
                        	<div class="accordion-heading">
                            	<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                               		<em class="icon-fixed-width glyphicon glyphicon-plus"></em>Open Access Policy
                           		</a>
                           	</div>
                          	<div style="height: 0px;" id="collapseTwo" class="accordion-body collapse">
                           		<div class="accordion-inner">
                           			<br>
                              		This journal provides immediate open access to its content on the principle that making research freely available to the public supports a greater global exchange of knowledge.
                           		</div>
                          	</div>
                       	</div>
                	</div><!-- end accordion -->
		    	</div>
			</div>
		</div>
	</div>
	
	<hr>
	
</div>