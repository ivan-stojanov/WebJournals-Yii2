<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Submissions';
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
                       	<div class="accordion-group" id="OnlineSubmissions">
                           	<div class="accordion-heading">
                          		<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                               		<em class="icon-fixed-width glyphicon glyphicon-plus"></em>Online Submissions
                           		</a>
                           	</div>
                           	<div style="height: 0px;" id="collapseOne" class="accordion-body collapse">
                           		<div class="accordion-inner">
                           			<br>
									<p>Already have a Username/Password for Open Journal Systems Demonstration Journal?
									<br><?= Html::a('GO TO LOGIN', ['/site/login']) ?></p>									
									
									<p>Need a Username/Password?
									<br><?= Html::a('GO TO REGISTRATION', ['/site/signup']) ?></p>
									
									<p>Registration and login are required to submit items online and to check the status of current submissions.</p>
                           		</div>
                       		</div>
        				</div>
        				<hr>
                        <div class="accordion-group" id="SubmissionPreparationChecklist">
                        	<div class="accordion-heading">
                            	<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                               		<em class="icon-fixed-width glyphicon glyphicon-plus"></em>Submission Preparation Checklist
                           		</a>
                           	</div>
                          	<div style="height: 0px;" id="collapseTwo" class="accordion-body collapse">
                           		<div class="accordion-inner">
                           			<br>
									<p>As part of the submission process, authors are required to check off their submission's compliance with all of the following items, and submissions may be returned to authors that do not adhere to these guidelines.</p>									
									
									<p>
									    <ol>
							                <li>The submission has not been previously published, nor is it before another journal for consideration (or an explanation has been provided in Comments to the Editor).</li>
							                <li>The submission file is in OpenOffice, Microsoft Word, RTF, or WordPerfect document file format.</li>
							                <li>Where available, URLs for the references have been provided.</li>
							                <li>The text is single-spaced; uses a 12-point font; employs italics, rather than underlining (except with URL addresses); and all illustrations, figures, and tables are placed within the text at the appropriate points, rather than at the end.</li>
							                <li>The text adheres to the stylistic and bibliographic requirements outlined in the <?= Html::a('Author Guidelines', ['/site/']) ?>, which is found in About the Journal.</li>
							                <li>If submitting to a peer-reviewed section of the journal, the instructions in <?= Html::a('Ensuring a Blind Review', ['/site/']) ?> have been followed.</li>
									    </ol>
									</p>                          		
								</div>
                          	</div>
                       	</div>
                       	<hr>
                       	<div class="accordion-group" id="PrivacyStatement"> 
                            <div class="accordion-heading">
                            	<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
                                	<em class="icon-fixed-width glyphicon glyphicon-plus"></em>Privacy Statement
                              	</a>
                            </div>
                            <div style="height: 0px;" id="collapseThree" class="accordion-body collapse">
                             	<div class="accordion-inner">
                             		<br>
                             		<p>The names and email addresses entered in this journal site will be used exclusively for the stated purposes of this journal and will not be made available for any other purpose or to any other party.<p>
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