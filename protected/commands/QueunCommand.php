<?php
/**
 * 队列封装
 * @author linglingqi@meilishuo.com
 * /usr/local/bin/php /home/work/websites/developer/protected/yiic.php Queun
 */
class QueunCommand extends Command {

	public function run($args) {

		ini_set("memory_limit", "10G");
		$times = date('Y-m-d H:i:s');

		//路由处理
		if(!isset($args[0])) {

			$names = $this->getMqName();
			foreach ($names as $name) {

				$class_name = ucfirst($name);
				$request_uri = "Queun $class_name 1";
				MqManager::shell_cmd($request_uri);
			}
		} else {

			$name = $args[0];
			$idx = $args[1];
			//先判断脚本是否正在运行，如果是则推出
			$request_uri = "Queun $name $idx";
			$shell       = MqManager::shell($request_uri);
			$num         = MqManager::shell_proc_num($shell);
			if ($num >= 2) {
				exit;
			}


			$class_name = ucfirst($name) . 'Command';
			if(!class_exists($class_name, false)){
				$class_file = APP_PATH . '/protected/commands/queue/'.$class_name.'.php';
				if(!is_file($class_file)){
					$this->setError('003', "executor [$name] class file not found, times:".$times);
					return false;
				}
				require_once($class_file);
			}

			$_start_time = microtime(true);
			$executor = new $class_name();
			$ret   = $executor->main($name, $idx);
			$errno = $this->getErrno();
			if(false === $ret) {
				$msg = 'ERROR:"'.$this->getErrno().':'.$this->getError().'"';
			} elseif (-2 === $ret) {
				$msg = 'RETRY:"'.$this->getErrno().':'.$this->getError().'"';
			} elseif (true !== $ret) {
				$msg = 'PASS:"'.$this->getErrno().':'.$this->getError().'"';
			} else {
				$msg = 'OK';
			}
			unset($executor);
			$action = $class_name.'.'.$name;
			$log = "DoTask $msg # ";
			$content = 'used time:'. microtime(true) - $_start_time . 'className:'. $name . 'errmsg:'. $msg;
			Log::writeApplog($log, $content);
		}
	}

	public function getMqName() {

		$tmp = Config::get('mqconf.config');
		$config = array_keys($tmp);
		return $config;
	}

	//获取错误信息
	public function errorInfo(){
		return array(
				'error_no'=>$this->_error_no,
				'error_msg'=>$this->_error_msg
		);
	}

	//写入错误信息
	protected function setError($code, $msg) {
		$this->_error_no = intval($code);
		$this->_error_msg= strval($msg);
		return true;
	}

	//获取错误码
	public function getErrno(){
		return $this->_error_no;
	}

	//获取错误信息
	public function getError(){
		return $this->_error_msg;
	}

	/**
	 * 返回错误信息
	 * @return array(error_no, error_msg)
	 */
	public function getErrorInfo(){
		return array(
				'error_no' => $this->_error_no,
				'error_msg' => $this->_error_msg
		);
	}


}
