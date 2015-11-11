<?php
/**
 * 队列封装
 * @author linglingqi@meilishuo.com
 */
class Mq {

	/**
	 * 每次批量读取mcq的数量
	 * @var int
	 */
	protected $max_num = 100;

	/**
	 * 默认获取不到数据睡多长时间
	 * @var int
	 */
	protected $sleep = 1;

	/**
	 * 心跳最小间隔时间，秒
	 * @var int
	 */
	protected $min_beat_time = 60;

	public function main($name, $idx) {

		$last_beat_time = 0;
		$beat_file  = sprintf(MqManager::$file_path .'/mcq_%s_%s.txt',$name, $idx);
		$dir = dirname($beat_file);
		if (!is_dir($dir)) {
			mkdir($dir, 0777, true);
		}

		//开始循环读取mq
		while (true) {
			//检查是否有停止命令
			if (MqManager::checkStop('mcq', $name, $idx) === 'stop') {
				break;
			}

			$last_beat_time = file_exists($beat_file) ? (int)file_get_contents($beat_file) : 0;
			//记录心跳时间
			$now_time = time();
			if ($now_time - $last_beat_time >= $this->min_beat_time) {
				if (file_put_contents($beat_file, $now_time)) {
					$last_beat_time = $now_time;
					echo "\033[34mbeat_time：" . date('Y-m-d H:i:s', $now_time) . "\033[0m\r\n";
				}
			}

			//批量从redis中获取数据，外面包了一层数组
			$redis = new Redisqueue();
			$key = 'mq_'. $name;
			$data = $redis->read($key);

			if (!$data) { //一条数据也没有获取到
				echo "\033[34mRead data empty [{$name}] " . date('Y-m-d H:i:s') . "\033[0m\r\n";
				if(is_int($this->sleep)) {
					sleep($this->sleep);
				}else{
					usleep($this->sleep*1000000);
				}
			} else { //开始处理
				$this->process($data);
			}
		}

		$time = date('Y-m-d H:i:s');
		echo "\r\n\033[31mStoped at : {$time}\033[0m\r\n";
	}

	protected function process($data) {

	}

}
