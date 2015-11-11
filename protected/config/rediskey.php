<?php

/**
 * redis key 的配置
 */
return array(
		'auth_username'=>array('key'=>'auth_username_%s', 'expire'=>'86400'),
		'depart'=>array('key'=>'depart', 'expire'=>'86400'),
		'userMap'=>array('key'=>'userMap', 'expire'=>'1800'),
		'AllStaff'=>array('key'=>'AllStaff' , 'expire'=>'86400'),
		'user_name'=>array('key'=>'user_name_%s', 'expire'=>'2592000'),
		'Depart-users'=>array('key'=>'Depart-users', 'expire'=>'86400'),
		'all_users_info'=>array('key'=>'all_users_info', 'expire'=>'86400'),
		'Menu'=>array('key'=>'Menu_%s_%s', 'expire'=>'86400'),
	    'removeAuth'=>array('key'=>'removeAuth', 'expire'=>'120'),
		//newcomm下面的缓存设置
		'user_info_byid'=>array('key'=>'user_info_byid_%s', 'expire'=>'86400'), //根据ID获取用户信息
		'user_info_byname'=>array('key'=>'user_info_byname_%s', 'expire'=>'86400'),//根据用户邮箱前缀获取用户信息
		'depart_users_list'=>array('key'=>'depart_users_list_%s', 'expire'=>'86400'),
		'depart_info_list'=>array('key'=>'depart_info_list_%s', 'expire'=>'600'), //根据部门ID获取部门信息  部门ID
		'shell_stop'=>array('key'=>'shell_stop_%s_%s_%s_%s', 'expire'=>'600'),
		//记录访问log的缓存添加
		'url_to_function'=>array('key'=>'url_to_function_%s_%s', 'expire'=>'300'), //url转功能 域名和路径后缀
		'auth_check'=>array('key'=>'auth_check_%s_%s', 'expire'=>'60'), //权限验证
		'business_get_by_host'=>array('key'=>'business_get_by_host_%s', 'expire'=>'60'),  //根据域名号获取业务号
		'warn_mail'=>array('key'=>'warn_mail', 'expire'=>'86400'), //报警邮件；防止太频繁10分钟发一次
		'depart_all_list'=>array('key'=>'depart_all_list', 'expire'=>'600'),
		'depart_by_name'=>array('key'=>'depart_by_name_%s', 'expire'=>'600'),
		//队列key
		'mq_Visitelog'=>array('key'=>'mq_Visitelog'),
);
