<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\switchinput\SwitchInput;

$this->title = 'Search';
$this->params['breadcrumbs'][] = $this->title;

\frontend\assets\AppAsset::register($this);
$this->registerJsFile("@web/js/searchScript.js", [ 'depends' => ['frontend\assets\CustomJuiAsset'], 'position' => \yii\web\View::POS_END]);
?>
<div class="serach-section-container">   
    <div class="row">
	    <div class="btn-group col-sm-12">
			<div class="btn-group col-sm-4 col-xs-6 serach-section-btn-group">
				<button type="button" class="<?= ((($params_GET == null || $params_GET->getQueryParam('type') == null) || ($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'volume')) ? 'active' : '') ?> btn btn-default serach-section-btn" value="volume">Browse by Volume</button>				
			</div>
			<div class="btn-group col-sm-4 col-xs-6 serach-section-btn-group">
				<button type="button" class="<?= (($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'issue') ? 'active' : '') ?> btn btn-default serach-section-btn" value="issue">Browse by Issue</button>
			</div>
			<div class="btn-group col-sm-4 col-xs-6 serach-section-btn-group">
				<button type="button" class="<?= (($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'section') ? 'active' : '') ?> btn btn-default serach-section-btn" value="section">Browse by Section</button>
			</div>
			
			<!-- <div class="col-sm-1"></div> -->
			<div class="btn-group col-sm-4 col-xs-6 serach-section-btn-group">
				<button type="button" class="<?= (($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'article') ? 'active' : '') ?> btn btn-default serach-section-btn" value="article">Browse by Article</button>				
			</div>
			<div class="btn-group col-sm-4 col-xs-6 serach-section-btn-group">
				<button type="button" class="<?= (($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'keyword') ? 'active' : '') ?> btn btn-default serach-section-btn" value="keyword">Browse by Keyword</button>
			</div>
			<div class="btn-group col-sm-4 col-xs-6 serach-section-btn-group">
				<button type="button" class="<?= (($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'user') ? 'active' : '') ?> btn btn-default serach-section-btn" value="user">Browse by Author</button>
			</div>
			<!-- <div class="col-sm-1"></div> -->
		</div>
	</div>
	<br>
    <div class="row">
    	<div class="col-sm-1 hidden-xs"></div>
        <div class="serach-section-letters col-sm-8 col-xs-12">
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'A') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="A">A</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'B') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="B">B</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'C') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="C">C</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'D') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="D">D</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'E') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="E">E</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'F') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="F">F</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'G') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="G">G</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'H') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="H">H</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'I') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="I">I</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'J') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="J">J</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'K') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="K">K</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'L') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="L">L</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'M') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="M">M</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'N') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="N">N</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'O') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="O">O</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'P') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="P">P</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'Q') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="Q">Q</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'R') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="R">R</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'S') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="S">S</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'T') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="T">T</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'U') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="U">U</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'V') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="V">V</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'W') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="W">W</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'X') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="X">X</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'Y') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="Y">Y</span>
			<span class="<?= (($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'Z') ? 'letter-serach-active' : '') ?> letter-serach" data-letter="Z">Z</span>
			<span class="<?= ((($params_GET == null || $params_GET->getQueryParam('letter') == null) || ($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'All')) ? 'letter-serach-active' : '') ?> letter-serach" data-letter="All">All</span>
        </div>
        <div class="serach-section-switch col-sm-2 col-xs-12">
		<?php
			echo SwitchInput::widget([
					'name' => 'show_details', 
					'value' => ($params_GET != null && $params_GET->getQueryParam('details') != null && $params_GET->getQueryParam('details') == 0) ? 0 : 1,
					'pluginOptions' => [
							'size' => 'mini',
							'handleWidth' => 60,
							'onText' => 'Show Details',
							'offText' => 'Hide Details'
					],
					'options' => [
							'id' => 'show_details_id',
					],
			]);
		?>
        </div>
        <div class="col-sm-1 hidden-xs"></div>
    </div>
	<!-- <br> -->
	<div class="row serach-section-text">
		<div class="col-xs-1"></div>
		<div class="col-xs-10 input-group add-on">
			<input class="form-control" placeholder="Search" id="serach-term" type="text" value="<?= (($params_GET != null && $params_GET->getQueryParam('text') != null) ? $params_GET->getQueryParam('text') : '') ?>">
			<div class="input-group-btn">
				<button class="btn btn-default" id="btn-serach-section" type="submit"><i class="glyphicon glyphicon-search" data-toggle="tooltip" title="Click to search!"></i></button>
			</div>
		</div>
		<div class="col-xs-1"></div>
	</div>
	<hr>
	<div class="row">
		<div class="serach-section-result col-sm-12">			
			
			<?php 
			if(($params_GET == null || $params_GET->getQueryParam('type') == null) || ($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'volume')) {
				echo $this->render('_search_volume', [
					'volumes_result' => $volumes_result,
					'params_GET' => $params_GET
				]);
			}
			
			if($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'issue') {
				echo $this->render('_search_issue', [
					'issues_result' => $issues_result,
					'params_GET' => $params_GET
				]);				
			}
			
			if($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'section') {
				echo $this->render('_search_section', [
					'sections_result' => $sections_result,
					'params_GET' => $params_GET
				]);
			}
			
			if($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'article') {
				echo $this->render('_search_article', [
					'articles_result' => $articles_result,
					'params_GET' => $params_GET
				]);
			}
			
			if($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'keyword') {
				echo $this->render('_search_keyword', [
					'keywords_result' => $keywords_result,
					'params_GET' => $params_GET
				]);
			}
			
			if($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'user') {
				echo $this->render('_search_user', [
					'users_result' => $users_result,
					'params_GET' => $params_GET
				]);
			}			
			?>			
		</div>
	</div>
	<hr>
	<div class='hidden'><span id="current_base_url"><?= $current_base_url ?></span></div>	
</div>
