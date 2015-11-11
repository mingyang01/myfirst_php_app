<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

$config = array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'../',
    'name'=>'developer',
    // preloading 'log' component
    'preload'=>array('log'),
    // autoloading model and component classes
    'import'=>array(
        'framework.components.*',
        'framework.extensions.*',
        'framework.extensions.smarty.sysplugins.*',
        'framework.extensions.yii-mail.*',

        'application.models.*',
        'application.components.*',
        'application.managers.*',
        'application.extensions.*',
    ),
    'modules'=>array(

    ),
    // application components
    'components'=>array(
        'authManager' => array(
            // Path to SDbAuthManager in srbac module if you want to use case insensitive
            //access checking (or CDbAuthManager for case sensitive access checking)
            'class' => 'CDbAuthManager',
            // The database component used
            'connectionID' => 'db_eel',
            // The itemTable name (default:authitem)       授权项表
            'itemTable' => 'developer_AuthItem',
            // The assignmentTable name (default:authassignment)    权限分配表
            'assignmentTable' => 'developer_AuthAssignment',
            // The itemChildTable name (default:authitemchild)     任务对应权限表
            'itemChildTable' => 'developer_AuthItemChild',
            'defaultRoles' => array('default')
        ),

        'curl' => array(
            'class' => 'Curl',
            'options' => array()
        ),
        'session' => array(
            'autoStart'=>true,
            'class'=>'CCacheHttpSession',
            'sessionName'=>'developer',
            'cacheID'=>'sessionCache',
            'cookieMode'=>'only',
            'timeout' => 7200,
            //'cookieParams' => array('domain' => "focus.meiliworks.com"),
        ),
        'sessionCache' => array(
            'class'=>'CRedisCache',
            'hostname'=>'127.0.0.1',
            'port'=>6379,
            'database'=>0,
        ),
        'cache'=>array(
            'class'=>'CRedisCache',
            'hostname'=>'127.0.0.1',
            'port'=>6379,
            'database'=>0,
         ),
        // uncomment the following to enable URLs in path-format
        'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName' => false,
            'rules'=>array(
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
        ),
        'user'=>array(
            // enable cookie-based authentication
            'class'=>'User',
            'allowAutoLogin'=>true,
        ),
        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                ),
                */
            ),
        ),
        'smarty'=>array(
            'class'=>'framework.extensions.CSmarty',
        ),
        'mail' => array(
            'class' => 'framework.extensions.yii-mail.YiiMail',
            'transportType'=>'smtp',
            'viewPath' => 'application.views.mail',
        ),
        'sphinx' => array(
        		'class' => 'Sphinx',
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/params.php'),
);

$database = require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'db.php');
if (!empty($database)) {
    $config['components'] = CMap::mergeArray($config['components'],$database);
}

if(function_exists("focus_load_local_config")) {
    $config = focus_load_local_config($config);
}

return $config;
