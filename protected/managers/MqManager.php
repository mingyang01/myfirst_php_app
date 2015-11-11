<?php
/**
 * 对mq封装使用的一些公用方法
 * @author linglingqi@meilishuo.com
 * @date 2015-10-12
 */
class MqManager extends Manager {

	//PHP bin所在路径
	protected static $php = '/home/service/php/bin/php';

	public static $file_path ='/home/work/logs/developer';

	/**
	 * 检查是否是否该停了
	 *
	 * @param string $type   类别crontab/mcq
	 * @param string $action 方法名称
	 * @param int    $idx    线程号
	 *
	 * @return mixed false|string
	 *
	 * @author chengxuan
	 */
	public static function checkStop($type, $action, $idx) {

		$ini_mc = 'shell_stop';
		$hostname = Until::getIp();

		$mc = new Redisdb();
		$result = $mc->get($ini_mc, array($type, $action, $idx, $hostname));
		if ($result !== false) {
			$mc->del($ini_mc, array($type, $action, $idx, $hostname));
		}
		return $result;
	}


	/**
	 * 发送让进程停止的通知（通过mc传递）
	 *
	 * @param string $type          类别
	 * @param string $action_name	mcq 的 action name
	 * @param string $idx			进程编号
	 * @return bool
	 */
	public static function sendStop($type, $action_name, $idx) {

		$hostname = Until::getIp();

		$mc = new Redisdb();
		return $mc->set(
				'shell_stop',
				array($type, $action_name, $idx, $hostname),
				'stop'
		);
	}

	/**
	 * 执行Shell命令
	 *
	 * @param	string	$request_uri
	 */
	public static function shell_cmd($request_uri) {
		$cmd = self::shell($request_uri) . " > /dev/null &";
		Until::execute($cmd);
	}

	/**
	 * 获取Shell执行命令
	 *
	 * @param	string	$request_uri
	 * @return	string
	 */
	public static function shell($request_uri) {
		return self::$php . ' ' . APP_PATH . "/protected/yiic.php {$request_uri}";
	}

	/**
	 * 检查指定shell命令进程数
	 *
	 * @param	string	$shell	shell命令
	 * @return	int
	 */
	public static function shell_proc_num($shell) {
		$shell = str_replace(array('-', '"'), array('\-', ''), $shell);
		$shell = preg_quote($shell);

		$cmd = "ps -ef | grep -v 'grep' |grep \"{$shell}\"| wc -l";
		return trim(Until::execute($cmd));
	}

	/**
	 * 检测自身进程，不允许多个进程同时运行
	 *
	 * @return void
	 */
	public static function check_self_proc() {
		$_cmd = "ps -ef | grep -v 'grep' | grep -v 'sudo' |grep php| grep '{$GLOBALS['argv'][0]}'|grep request_uri=\"/" . $this->getRequest()->getRequestUri() . "\" |grep -v \"/bin/sh \\-c\" | wc -l";
		$_num = Until::execute($_cmd);
		if ($_num > 1) {
			throw new Exception(100001, 'The process has runing.');
		}
	}

}