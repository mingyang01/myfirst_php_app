<?php
/**
 * Redis对象封装作为队列进行数据处理
 *
 * @Autor:linglingqi@meilishuo.com
 * @Date: 2015-09-17
 */

class Redisqueue {
    /**
     * @var Redis
     */
    private $server_type = 'slave';

    const REDIS_SERVER_DEFAULT = 'defalut_redis';

    //redis做队列对应的配置
    const REDIS_QUEUE_DEFAULT = 'mcq_redis_default';

    private $redis_instance = array();
    PRIVATE $redis = null;

    public function  __construct($server_name=self::REDIS_SERVER_DEFAULT, $type="slave"){

        $key = $server_name;
        if(isset($this->redis_instance[$key])){
        	$this->redis = $this->redis_instance[$key];
        	return true;
        }

        try{
        	$config = $this->getServerConfig($key, $type);
        	$this->redis = new Redis();
        	$this->redis->connect($config['host'], $config['port']);
        	$this->redis_instance[$key] = $this->redis;

        }catch (Exception $e){
        	$title = 'Redis Server访问失败';
        	$content = sprintf('Redis Server连接失败：server name:%s [%s],IP is %s', $this->server_name, $type, Until::getIp());
        	//每隔10分钟发送一次报警
        	$mail = new MailManager();
        	$mail->sendWarnning($content .';'. $e->getmessage());
        }
        return true;

    }

    /**
     * 读队列里面的数据
     */
    public function write($key, array $args, $value) {

    	$q_key = $this->getConfigKey($key, $args);
    	return $this->redis->lpush($q_key, json_encode($value));
    }

    /**
     * 往队列里写数据
     *
     * limit 每次读队列的多少，默认100
     */
    public function read($key, array $args=array(), $limit=100) {

    	$q_key = $this->getConfigKey($key, $args);
    	$result = array();
    	$data = $this->redis->lrange($q_key, 0, $limit);
    	$num = count($data);
    	if ($num>0) {
    		for ($i=0;$i<$num;$i++) {
    			$tmp = $this->redis->rpop($q_key);
    			if (!$tmp) {
    				break;
    			}
    			$result[] = json_decode($tmp, true);
    		}
    	}
		return $result;
    }

    private function getConfigKey($key, $args) {

    	$config = Config::get('rediskey.'. $key);
    	$cache_key = call_user_func_array('sprintf', array_merge(array($config['key']), $args));
    	return $cache_key;
    }

    private  function getServerConfig($name, $type) {

    	$config = Config::get('redisconf.'. $name);
    	if ($config && $config[$type]) {
    		return $config[$type];
    	}else {
    		throw new Exception("redis配置信息没有获取到,报警服务器ip是". Until::getIp());
    	}
    }
}