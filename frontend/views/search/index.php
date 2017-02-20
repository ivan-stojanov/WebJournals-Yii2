<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Search';
$this->params['breadcrumbs'][] = $this->title;

\frontend\assets\AppAsset::register($this);
$this->registerJsFile("@web/js/searchScript.js", [ 'depends' => ['frontend\assets\CustomJuiAsset'], 'position' => \yii\web\View::POS_END]);
?>
<div class="serach-section-container">
    
    <div class="row">
	    <div class="btn-group col-sm-12">
			<div class="btn-group col-sm-4 serach-section-btn-group">
				<button type="button" class="active btn btn-default serach-section-btn" value="volume">Browse by Volume</button>				
			</div>
			<div class="btn-group col-sm-4 serach-section-btn-group">
				<button type="button" class="btn btn-default serach-section-btn" value="issue">Browse by Issue</button>
			</div>
			<div class="btn-group col-sm-4 serach-section-btn-group">
				<button type="button" class="btn btn-default serach-section-btn" value="section">Browse by Section</button>
			</div>
			
			<!-- <div class="col-sm-1"></div> -->
			<div class="btn-group col-sm-4 serach-section-btn-group">
				<button type="button" class="btn btn-default serach-section-btn" value="article">Browse by Article</button>				
			</div>
			<div class="btn-group col-sm-4 serach-section-btn-group">
				<button type="button" class="btn btn-default serach-section-btn" value="keyword">Browse by Keyword</button>
			</div>
			<div class="btn-group col-sm-4 serach-section-btn-group">
				<button type="button" class="btn btn-default serach-section-btn" value="user">Browse by User</button>
			</div>
			<!-- <div class="col-sm-1"></div> -->
		</div>
	</div>
	<br>
    <div class="row">
        <div class="serach-section-letters col-xs-12">
			<span class="letter-serach" data-letter="A">A</span>
			<span class="letter-serach" data-letter="B">B</span>
			<span class="letter-serach" data-letter="C">C</span>
			<span class="letter-serach" data-letter="D">D</span>
			<span class="letter-serach" data-letter="E">E</span>
			<span class="letter-serach" data-letter="F">F</span>
			<span class="letter-serach" data-letter="G">G</span>
			<span class="letter-serach" data-letter="H">H</span>
			<span class="letter-serach" data-letter="I">I</span>
			<span class="letter-serach" data-letter="J">J</span>
			<span class="letter-serach" data-letter="K">K</span>
			<span class="letter-serach" data-letter="L">L</span>
			<span class="letter-serach" data-letter="M">M</span>
			<span class="letter-serach" data-letter="N">N</span>
			<span class="letter-serach" data-letter="O">O</span>
			<span class="letter-serach" data-letter="P">P</span>
			<span class="letter-serach" data-letter="Q">Q</span>
			<span class="letter-serach" data-letter="R">R</span>
			<span class="letter-serach" data-letter="S">S</span>
			<span class="letter-serach" data-letter="T">T</span>
			<span class="letter-serach" data-letter="U">U</span>
			<span class="letter-serach" data-letter="V">V</span>
			<span class="letter-serach" data-letter="W">W</span>
			<span class="letter-serach" data-letter="X">X</span>
			<span class="letter-serach" data-letter="Y">Y</span>
			<span class="letter-serach" data-letter="Z">Z</span>
			<span class="letter-serach letter-serach-active" data-letter="All">All</span>
        </div>
    </div>
	<br>
	<div class="row">
		<div class="serach-section-text col-sm-12">
			<div class="col-sm-1"></div>
			<div class="col-sm-10 input-group add-on">
				<input class="form-control" placeholder="Search" id="serach-term" type="text">
				<div class="input-group-btn">
					<button class="btn btn-default" id="btn-serach-section" type="submit"><i class="glyphicon glyphicon-search"></i></button>
				</div>
			</div>
			<div class="col-sm-1"></div>
		</div>
	</div>
	<hr>
	
</div>
