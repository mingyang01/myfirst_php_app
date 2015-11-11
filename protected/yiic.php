<?php
// error_reporting(E_ERROR|E_COMPILE_ERROR);

define('APP_PATH', dirname(dirname(__FILE__)));

// 加载开发环境自定义配置文件
if(file_exists(dirname(__FILE__)."/config/local/main.php")) {
    require_once(dirname(__FILE__)."/config/local/main.php");
}
defined('YII_DEBUG') or define('YII_DEBUG',false);
// change the following paths if necessary

$yiic='/home/work/framework/yii/yiic.php';
//$yiic=dirname(__FILE__).'/../framework/yiic.php';
$config=dirname(__FILE__).'/config/console.php';

require_once($yiic);