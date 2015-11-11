<?php

Yii::setPathOfAlias('framework', '/home/work/framework/');
$config = array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'developer',
    // preloading 'log' component
    'preload'=>array('log'),
    // autoloading model and component classes
    'import'=>array(
        'framework.components.*',
        'framework.extensions.*',
        'framework.extensions.smarty.sysplugins.*',
        'framework.extensions.yii-mail.*',
        'framework.extensions.phpexcel.*',

        'application.models.*',
        'application.components.*',
        'application.managers.*',
        'application.extensions.*'
    ),
    'modules'=>array(
        // uncomment the following to enable the Gii tool
        /*
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'Enter Your Password Here',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'=>array('127.0.0.1','::1'),
        ),
        */
    ),
    // application components
    'components'=>array(
        //curl
        'curl' => array(
            'class' => 'framework.extensions.Curl',
            'options' => array()
        ),

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
        'cache'=>array(
            'class'=>'CRedisCache',
            'hostname'=>'172.16.12.117',
            'port'=>6379,
            'database'=>0,
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
        )
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/params.php'),
);

$database = require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'db.php');
if (!empty($database)) {
    $config['components'] = CMap::mergeArray($config['components'],$database);
    //Yii::app()->setComponents($database);
}

if(function_exists("focus_load_local_config")) {
    $config = focus_load_local_config($config);
}

return $config;


