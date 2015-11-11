<?php
/**
 * 一个公用的获取公共逻辑的函数
 * @author linglingqi@meilishuo.com
 * @version 2015-09-29
 */
class Until{

	/**
	 * 获取服务器的IP地址
	 */
	public static function getIp() {
		return Yii::app()->request->userHostAddress;
	}

	/**
	 * 获取请求的URL
	 */
	public static function  getUrl() {
		return Yii::app()->request->getUrl();
	}

	/**
	 * 获取请求的域名
	 */
	public static function getDomain() {
		return Yii::app()->request->hostInfo;
	}

	/**
	 * php执行shell命令
	 */
	public static function execute($cmd) {
        return shell_exec($cmd);
    }

}