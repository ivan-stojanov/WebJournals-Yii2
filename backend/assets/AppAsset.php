<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    	'css/adminPanel.css',
    		
    	'thirdParty/bootstrap/dist/css/bootstrap.min.css',
    	'thirdParty/metisMenu/dist/metisMenu.min.css',	
   		'thirdParty/timeline.css',
   		'thirdParty/sb-admin-2.css',
    	'thirdParty/morrisjs/morris.css',
    	'thirdParty/font-awesome/css/font-awesome.min.css',
    ];
    public $js = [
    	'js/jquery.tablednd.0.7.min.js',
    		
    	'thirdParty/metisMenu/dist/metisMenu.min.js',
    	'thirdParty/raphael/raphael-min.js',
    	'thirdParty/morrisjs/morris.min.js',
    	'thirdParty/sb-admin-2.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    	'yii\bootstrap\BootstrapPluginAsset',    		
    	'yii\web\JqueryAsset',
    ];
}
