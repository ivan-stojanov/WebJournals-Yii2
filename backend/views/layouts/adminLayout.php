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

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Open Journal Systems',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems[] = ['label' => 'Home', 'url' => ['/site/index']];
    $menuItems[] = ['label' => 'My Profile', 'url' => ['/user/profile']];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = [
            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();    
    $currentControllerId = $this->context->id;
    $currentActionId = $this->context->action->id;

    $adminMenuItems[] = [
				            'url' => Url::to(['/admin/home']),
    						'active' => (($currentControllerId == 'admin' && $currentActionId == 'home') || 
    									 ($currentControllerId == 'admin' && $currentActionId == 'homecontent')),
				            'label' => 'Home',
				            'icon' => 'home',
				        ];    
    $adminMenuItems[] = [
				    		'url' => Url::to(['/announcement/index']),
    						'active' => (($currentControllerId == 'announcement' && $currentActionId == 'index') 	|| 
    									 ($currentControllerId == 'announcement' && $currentActionId == 'create') 	||
    									 ($currentControllerId == 'announcement' && $currentActionId == 'view') 	||
    									 ($currentControllerId == 'announcement' && $currentActionId == 'update')),
				    		'label' => 'Announcements',
				    		'icon' => 'book',
    ];    
    $adminMenuItems[] = [
    		'url' => Url::to(['/user/index']),
    		'active' => (($currentControllerId == 'user' && $currentActionId == 'index') 	||
    				($currentControllerId == 'user' && $currentActionId == 'create') 	||
    				($currentControllerId == 'user' && $currentActionId == 'view') 	||
    				($currentControllerId == 'user' && $currentActionId == 'update') 	||
    				($currentControllerId == 'user' && $currentActionId == 'profile')),
    		'label' => 'Users',
    		'icon' => 'user',
    ];
    /*$adminMenuItems[] = [
				            'label' => 'Help',
				            'icon' => 'question-sign',
				            'items' => [
				                ['label' => 'About', 'icon'=>'info-sign', 'url'=>Url::to(['/site/error']), 'active' => ($currentActionId == 'error'),],
				                ['label' => 'Contact', 'icon'=>'phone', 'url'=>'#'],
				            ],
				        ];*/
	?>
    <div class="container adminContainer">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?php if(!Yii::$app->user->getIsGuest()){?>
	        <div class='col-lg-2 col-md-2' >
	            <!-- here your left admin menu -->            
				<?php
					echo SideNav::widget([
					    'type' => SideNav::TYPE_DEFAULT,
						'encodeLabels' => false,
					    'heading' => 'Options',
					    'items' => $adminMenuItems,
					]);
				?>
	        </div>
        <?php } ?>
        <div class='col-lg-10 col-md-10' >
            <!-- here your content page-->
            <?= $content ?>
        </div> 
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
