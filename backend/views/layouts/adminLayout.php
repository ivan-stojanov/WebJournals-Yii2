<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use kartik\sidenav\SideNav;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href='<?php echo Url::to(['/site/index']); ?>'>Open Journal System</a>
            </div>
            
            <ul class="nav navbar-top-links navbar-right">
            	<li>
               		<a href='<?php echo Url::to(['/site/index']); ?>'>
                    	<div>
                        	<strong>Home</strong>
                        </div>
                    </a>
                </li>
                <li>
               		<a href='<?php echo Url::to(['/user/profile']); ?>'>
                    	<div>
                        	<strong>My Profile</strong>
                        </div>
                    </a>
                </li>
                <?php if (Yii::$app->user->isGuest) { ?>
                <li>
               		<a href='<?php echo Url::to(['/site/login']); ?>'>
                    	<div>
                        	<strong>Login</strong>
                        </div>
                    </a>
                </li>
                <?php } else { ?>
                <li>
               		<a href='<?php echo Url::to(['/site/logout']); ?>' data-method='POST'>
                    	<div>
                        	<strong><?php echo "Logout ( ". Yii::$app->user->identity->username ." )"; ?></strong>
                        </div>
                    </a>                    
                </li>                
                <?php } ?>
            </ul>
            <!-- /.navbar-header -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                    	<!-- 
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
	                                <button class="btn btn-default" type="button">
	                                    <i class="fa fa-search"></i>
	                                </button>
	                            </span>
                            </div>
                            <!-- /input-group 
                        </li>-->
                        <li>
                            <a href='<?php echo Url::to(['/site/index']); ?>'><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href='<?php echo Url::to(['/admin/home']); ?>'><i class="fa fa-home fa-fw"></i> Manage Home Page</a>
                        </li>
                        <li>
							<a href='#'><i class="fa fa-book fa-fw"></i> Announcements<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li>
                                    <a href='<?php echo Url::to(['/announcement/index']); ?>'><i class="fa fa-list fa-fw"></i> List All</a>
                                </li>
                                <li>
                                    <a href='<?php echo Url::to(['/announcement/create']); ?>'><i class="fa fa-plus fa-fw"></i> Create New</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->                            
                        </li>
                        <li>
                            <a href='#'><i class="fa fa-users fa-fw"></i> Users<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li>
                                    <a href='<?php echo Url::to(['/user/index']); ?>'><i class="fa fa-list fa-fw"></i> List All</a>
                                </li>
                                <li>
                                    <a href='<?php echo Url::to(['/user/create']); ?>'><i class="fa fa-plus fa-fw"></i> Create New</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						<!-- 
                        <li>
                            <a href="forms.html"><i class="fa fa-edit fa-fw"></i> Forms</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="panels-wells.html">Panels and Wells</a>
                                </li>
                                <li>
                                    <a href="buttons.html">Buttons</a>
                                </li>
                                <li>
                                    <a href="notifications.html">Notifications</a>
                                </li>
                                <li>
                                    <a href="typography.html">Typography</a>
                                </li>
                                <li>
                                    <a href="icons.html"> Icons</a>
                                </li>
                                <li>
                                    <a href="grid.html">Grid</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level 
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Third Level <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level
                                </li>
                            </ul>
                            <!-- /.nav-second-level 
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="blank.html">Blank Page</a>
                                </li>
                                <li>
                                    <a href="login.html">Login Page</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level 
                        </li>-->
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav> 
           
	<?php 
	    $currentControllerId = $this->context->id;
	    $currentActionId = $this->context->action->id;
	?>
	
    <div id="page-wrapper">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?php if(!Yii::$app->user->getIsGuest()){?>        
        	<!-- here your content page-->
            <?= $content ?>
        <?php } ?>
        
    </div>
</div>

<div class="clear"></div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
