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
				<button type="button" class="<?= ((($params_GET == null || $params_GET->getQueryParam('type') == null) || ($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'volume')) ? 'active' : '') ?> btn btn-default serach-section-btn" value="volume">Browse by Volume</button>				
			</div>
			<div class="btn-group col-sm-4 serach-section-btn-group">
				<button type="button" class="<?= (($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'issue') ? 'active' : '') ?> btn btn-default serach-section-btn" value="issue">Browse by Issue</button>
			</div>
			<div class="btn-group col-sm-4 serach-section-btn-group">
				<button type="button" class="<?= (($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'section') ? 'active' : '') ?> btn btn-default serach-section-btn" value="section">Browse by Section</button>
			</div>
			
			<!-- <div class="col-sm-1"></div> -->
			<div class="btn-group col-sm-4 serach-section-btn-group">
				<button type="button" class="<?= (($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'article') ? 'active' : '') ?> btn btn-default serach-section-btn" value="article">Browse by Article</button>				
			</div>
			<div class="btn-group col-sm-4 serach-section-btn-group">
				<button type="button" class="<?= (($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'keyword') ? 'active' : '') ?> btn btn-default serach-section-btn" value="keyword">Browse by Keyword</button>
			</div>
			<div class="btn-group col-sm-4 serach-section-btn-group">
				<button type="button" class="<?= (($params_GET != null && $params_GET->getQueryParam('type') != null && $params_GET->getQueryParam('type') == 'user') ? 'active' : '') ?> btn btn-default serach-section-btn" value="user">Browse by User</button>
			</div>
			<!-- <div class="col-sm-1"></div> -->
		</div>
	</div>
	<br>
    <div class="row">
        <div class="serach-section-letters col-xs-12">
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
			<span class="<?= ((($params_GET == null || $params_GET->getQueryParam('letter') == null) || ($params_GET != null && $params_GET->getQueryParam('letter') != null && $params_GET->getQueryParam('letter') == 'A')) ? 'letter-serach-active' : '') ?> letter-serach" data-letter="All">All</span>
        </div>
    </div>
	<br>
	<div class="row">
		<div class="serach-section-text col-sm-12">
			<div class="col-sm-1"></div>
			<div class="col-sm-10 input-group add-on">
				<input class="form-control" placeholder="Search" id="serach-term" type="text" value="<?= (($params_GET != null && $params_GET->getQueryParam('text') != null) ? $params_GET->getQueryParam('text') : '') ?>">
				<div class="input-group-btn">
					<button class="btn btn-default" id="btn-serach-section" type="submit"><i class="glyphicon glyphicon-search" data-toggle="tooltip" title="Click to search!"></i></button>
				</div>
			</div>
			<div class="col-sm-1"></div>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="serach-section-result col-sm-12">			
			
			<?php 
			if($volumes_result != null && count($volumes_result)>0) {
				foreach ($volumes_result as $volume_index => $volume_item) {
				echo "<div class='row'>";
					echo "<span class='serach-section-result-volume'>".$volume_item->title."</span>";
					echo "<br/>";
					$volume_issues = null;
					if($volume_item->issues != null)
						$volume_issues = $volume_item->issues;
					if($volume_issues != null && count($volume_issues)>0) {
						foreach ($volume_issues as $issue_index => $issue_item) {
							echo "<span class='serach-section-result-issue'>".$issue_item->title."</span>";
							echo "<br/>";
							$issue_sectons = null;
							if($issue_item->sections != null)
								$issue_sectons = $issue_item->sections;
							if($issue_sectons != null && count($issue_sectons)>0) {
								foreach ($issue_sectons as $section_index => $section_item) {
									echo "<span class='serach-section-result-section'>".$section_item->title."</span>";
									echo "<br/>";
									$secton_articles = null;
									if($section_item->articles != null)
										$secton_articles = $section_item->articles;
									if($secton_articles != null && count($secton_articles)>0) {									
										foreach ($secton_articles as $article_index => $article_item) {
											echo "<ul class='serach-section-result-article'>";
											echo "	<li>";
											echo "		<span>".$article_item->title."</span>";
											echo "		<br>";
											echo "		<i>Authors: </i><span class='serach-section-result-users'>".$article_item->title."</span>";
											echo "	</li>";
											echo "</ul>";
										}
									}
								}
							}
						}
					}
					echo "</div>";
					if($volume_index < (count($volumes_result)-1)) {
						echo "<hr style='border-top: 3px dashed #eee;' />";
					}					
				}
			}
			?>

			<?php /* HTML TEMPLATE --- START 
			<div class="row">
				<span class="serach-section-result-volume">Volume Name 1</span>
				<br/>
				<span class="serach-section-result-issue">Issue Name 11</span>
				<br/>
				<span class="serach-section-result-section">Section Name 1x1</span>
				<br/>
				<ul class="serach-section-result-article">
					<li>
						<span>Article Name 1x1x1</span>
						<br/>
						<i>Authors: </i><span class="serach-section-result-users">User Name 1x1x1x1; User Name 1x1x1x2; User Name 1x1x1x3</span>
					</li>
				</ul>
				<ul class="serach-section-result-article">
					<li>
						<span>Article Name 1x1x2</span>
						<br/>
						<i>Authors: </i><span class="serach-section-result-users">User Name 1x1x2x1; User Name 1x1x2x2; User Name 1x1x2x3</span>
					</li>
				</ul>				
				<span class="serach-section-result-section">Section Name 1x2</span>
				<ul class="serach-section-result-article">
					<li>
						<span>Article Name 1x2x1</span>
						<br/>
						<i>Authors: </i><span class="serach-section-result-users">User Name 1x2x1x1; User Name 1x2x1x2; User Name 1x2x1x3</span>
					</li>
				</ul>
				<ul class="serach-section-result-article">
					<li>
						<span>Article Name 1x2x2</span>
						<br/>
						<i>Authors: </i><span class="serach-section-result-users">User Name 1x2x2x1; User Name 1x2x2x2; User Name 1x2x2x3</span>
					</li>
				</ul>
				
			</div>
			<hr style="border-top: 3px dashed #eee;" />
			<div class="row">

				<span class="serach-section-result-volume">Volume Name 2</span>
				<br/>
				<span class="serach-section-result-issue">Issue Name 22</span>
				<br/>
				<span class="serach-section-result-section">Section Name 2x1</span>
				<br/>
				<ul class="serach-section-result-article">
					<li>
						<span>Article Name 2x1x1</span>
						<br/>
						<i>Authors: </i><span class="serach-section-result-users">User Name 2x1x1x1; User Name 2x1x1x2; User Name 2x1x1x3</span>
					</li>
				</ul>
				<ul class="serach-section-result-article">
					<li>
						<span>Article Name 2x1x2</span>
						<br/>
						<i>Authors: </i><span class="serach-section-result-users">User Name 2x1x2x1; User Name 2x1x2x2; User Name 2x1x2x3</span>
					</li>
				</ul>				
				<span class="serach-section-result-section">Section Name 2x2</span>
				<ul class="serach-section-result-article">
					<li>
						<span>Article Name 2x2x1</span>
						<br/>
						<i>Authors: </i><span class="serach-section-result-users">User Name 2x2x1x1; User Name 2x2x1x2; User Name 2x2x1x3</span>
					</li>
				</ul>
				<ul class="serach-section-result-article">
					<li>
						<span>Article Name 2x2x2</span>
						<br/>
						<i>Authors: </i><span class="serach-section-result-users">User Name 2x2x2x1; User Name 2x2x2x2; User Name 2x2x2x3</span>
					</li>
				</ul>
				
			</div>
			HTML TEMPLATE --- END */ ?>			
			
		</div>
	</div>
	<hr>
	<div class='hidden'><span id="current_base_url"><?= $current_base_url ?></span></div>	
</div>
