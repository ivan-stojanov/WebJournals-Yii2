<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
//include('./../web/css/plusMinusCarousel.css');
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
    
    $currentControllerId = $this->context->id;
    $currentActionId = $this->context->action->id;
    
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'About', 'url' => ['/site/about']],
    	['label' => 'Contact Us', 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
    	$menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
        $menuItems[] = ['label' => 'Register', 'url' => ['/site/signup']];        
    } else {
    	$menuItems[] = ['label' => 'User Panel', 'url' => Yii::$app->urlManagerBackEnd->createUrl('site/index'),];
    }
    $menuItems[] = ['label' => 'Search', 'url' => ['/search/index']];
    $menuItems[] = ['label' => 'Current', 'url' => ['/site/current']];
    $menuItems[] = ['label' => 'Archive', 'url' => ['/site/archive']];
    $menuItems[] = ['label' => 'Blog', 'url' => ['/site/announcement'], 
				    'active' => (($currentControllerId == 'site' && $currentActionId == 'announcement') 	||
					    		 ($currentControllerId == 'site' && $currentActionId == 'announcementdetails')),
				   ];
    //$menuItems[] = ['label' => 'Contact', 'url' => ['/site/contact']];
    if (!(Yii::$app->user->isGuest)) {
         $menuItems[] = [
            'label' => 'Logout',// (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }   
    
   
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

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
