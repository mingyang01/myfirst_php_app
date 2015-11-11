<?php
define('YII_DEBUG',true);
if(file_exists(dirname(__FILE__)."/protected/config/local/main.php")) {
    require_once(dirname(__FILE__)."/protected/config/local/main.php");
}

// change the following paths if necessary
define('FRAMEWORK', '/home/work/framework');

// define('CHECKACCESS', 'ON');
define('PROJECT', 'developer');
define('MAGIC', 1);
require_once(FRAMEWORK.'/yii/yii.php');
Yii::setPathOfAlias('framework',FRAMEWORK);

error_reporting(E_ERROR);

if (YII_DEBUG) {
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
} else {
    // error_reporting(0);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',1);
}

//
$config = dirname(__FILE__).'/protected/config/main.php';
$application = Yii::createWebApplication($config);
$application -> run();
