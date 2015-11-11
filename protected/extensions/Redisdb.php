<?php
/**
 * Redis对象封装
 *
 * @Autor:linglingqi@meilishuo.com
 * @Date: 2015-09-17
 */
class Foo_Redis{
	public function __call($method, $args){
		return false;
		}
}

class Redisdb {
    /**
     * @var Redis
     */
    private $server_type = 'slave';
    private $server_name = '';

    const REDIS_SERVER_DEFAULT = 'defalut_redis';

    //redis做队列对应的配置
    const REDIS_QUEUE_DEFAULT = 'mcq_redis_default';

    private $redis_instance = array();

    public function  __construct($server_name=self::REDIS_SERVER_DEFAULT){
        $this->server_name  = $server_name;
    }

    public function setServerType($type){
        $this->server_type = $type;
    }

    public function set($key, array $args, $value){
        $redis = $this->getRedis('master');
        $config = $this->getCacheConfig($key, $args);

        if (!isset($config['expire'])) {
        	throw new Exception('设置缓存需要给定有效时间');
        }
        return $redis->setex($config['cache_key'], $config['expire'], $value);
    }

    public function get($key, array $args=array()){
        $redis = $this->getRedis($this->server_type);
        $config = $this->getCacheConfig($key, $args);
        return $redis->get($config['cache_key']);
    }

    public function del($key, $args=array()){
        $redis = $this->getRedis('master');
        return $redis->del($this->getCacheKey($key, $args));
    }

    public function incrBy($key, $args, $value){
        $redis = $this->getRedis('master');
        return $redis->incrBy($this->getCacheKey($key, $args), $value);
    }

    public function decrBy($key, $args, $value){
        $redis = $this->getRedis('master');
        return $redis->decrBy($this->getCacheKey($key, $args), $value);
    }

    /**
     * @param       $key
     * @param array $args
     * @param array $values
     * @return mixed
     */
    public function lPush( $key, array $args, array $values){
        return $this->callRedisMethod('lPush', $key, $args, $values);
    }

    /**
     * 获取队列的长度
     */
    public function llen($key, array $args=array()) {

    	$redis = $this->getRedis($this->server_type);
    	$data = $redis->lLen($this->getCacheKey($key, $args));
    	return $data;
    }


    /**
     * 从尾部读取队列数据
     * @param unknown $key
     */
    public function rPop($key, array $args=array()) {

    	$redis = $this->getRedis($this->server_type);
    	$data = $redis->rPop($this->getCacheKey($key, $args));
    	return json_decode($data, true);
    }

    /**
     * @param       $key
     * @param array $args
     * @param array $values
     * @return mixed
     */
    public function rPush($key, array $args, array $values){
        return $this->callRedisMethod('rPush', $key, $args, $values);
    }

    /**
     * @param       $key
     * @param array $args
     * @param       $start
     * @param       $end
     * @return array
     */
    public function lRange($key, array $args,  $start,  $end){
        $redis = $this->getRedis($this->server_type);
        $config = $this->getCacheConfig($key, $args);
        $list = $redis->lRange($config['cache_key'], $start, $end);
        $result = array();
        foreach($list as $item){
            $result[] = json_decode($item, true);
        }
        return $result;
    }

    /**
     * 获取list的大小
     * @param       $key
     * @param array $args
     */
    public function lSize($key, array $args){
        $redis = $this->getRedis($this->server_type);
        return $redis->lSize($this->getCacheKey($key, $args));
    }

    public function lTrim( $key, array $args, $start, $stop){
        return $this->callRedisMethod('lTrim', $key, $args, array($start, $stop));
    }

    /**
     * @param       $key
     * @param array $args
     * @return bool
     */
    public function exists($key, array $args=array()){
        $redis = $this->getRedis($this->server_type);
        return $redis->exists($this->getCacheKey($key, $args));
    }

    public function ttl($key, array $args=array()){
        $redis = $this->getRedis($this->server_type);
        return $redis->ttl($this->getCacheKey($key, $args));
    }

    /**
     * @param       $key
     * @param array $args
     * @param array $values array(array('score'=>'xxx', 'value'=>'xxx'),xxxx)
     * @return mixed
     */
    public function zAddMulti($key, array $args, array $values){
        $data = array();
        foreach($values as $item){
            $data[] = (float)$item['score'];
            $data[] = $item['value'];
        }
        return $this->callRedisMethod('zAdd', $key, $args, $data);
    }

    /**
     * @param       $key
     * @param array $args
     * @param array $values array('score1'=>'v1', 'score2'=>'v2')
     * @return mixed
     */
    public function zAdd($key, array $args, array $values){
        $data = array();
        foreach($values as $score=>$v){
            $data[] = (float)$score;
            $data[] = $v;
        }
        return $this->callRedisMethod('zAdd', $key, $args, $data);
    }

    /**
     * @param       $key
     * @param array $args
     * @param       $value
     * @return mixed
     */
    public function zRem($key, array $args, $value){
        !is_array($value) && ($value  = array($value));
        return $this->callRedisMethod('zRem', $key, $args, $value);
    }

    /**
     * @param       $key
     * @param array $args
     * @param       $start
     * @param null  $end
     * @return int
     */
    public function zRemByScore($key, array $args, $start, $end=null){
        $redis = $this->getRedis('master');
        return $redis->zRemRangeByScore($this->getCacheKey($key, $args), $start, $end===null ? $start: $end);
    }


    /**
     * @param       $key
     * @param array $args
     * @param int   $start
     * @param       $end
     * @param boolean  $with_score
     * @return array
     */
    public function zRange($key, array $args, $start=0, $end=-1, $with_score=false){
        $redis = $this->getRedis($this->server_type);
        $list = $redis->zRange($this->getCacheKey($key, $args), $start, $end, $with_score);
        return $this->decodeZSet($list, $with_score);
    }

    /**
     * @param       $key
     * @param array $args
     * @param int   $start
     * @param       $end
     * @param null  $with_score
     * @return array
     */
    public function zRevRange($key, array $args, $start=0, $end=-1, $with_score=null){
        $redis = $this->getRedis($this->server_type);
        $list = $redis->zRevRange($this->getCacheKey($key, $args), $start, $end, $with_score);
        return $this->decodeZSet($list, $with_score);
    }


    public function zSize($key, array $args=array()){
        $redis = $this->getRedis($this->server_type);
        return $redis->zSize($this->getCacheKey($key, $args));
    }

    /**
     * @param       $key
     * @param array $args
     * @param       $page
     * @param       $limit
     * @param bool  $with_score
     * @return array
     */
    public function zRangeByPage($key, array $args, $page, $limit,  $with_score=false){
        return $this->zRangeByScore($key, $args, '-inf', '+inf', array('page'=>$page, 'count'=>$limit),$with_score);
    }

    /**
     * @param       $key
     * @param array $args
     * @param       $page
     * @param       $limit
     * @param bool  $with_score
     * @return array
     */
    public function zRevRangeByPage($key, array $args, $page, $limit, $with_score=false){
        return $this->zRevRangeByScore($key, $args, '+inf', '-inf',  array('page'=>$page, 'count'=>$limit), $with_score);
    }

    /**
     * @param       $key
     * @param array $args
     * @param       $start
     * @param       $end
     * @param array $pager
     * @param bool  $with_score
     * @return array
     */
    public function zRangeByScore($key, array $args, $start, $end, $pager=array(), $with_score=false){
        $redis = $this->getRedis($this->server_type);
        $options = array();
        if($pager){
            $options['limit'] = array(($pager['page']-1)*$pager['count'], $pager['count']);
        }
        if($with_score){
            $options['withscores'] = true;
        }
        $list = $redis->zRangeByScore($this->getCacheKey($key, $args), $start, $end, $options);
        return $this->decodeZSet($list, $with_score);
    }

    /**
     * @param       $key
     * @param array $args
     * @param       $start
     * @param       $end
     * @param array $pager
     * @param bool  $with_score
     * @return array
     */
    public function zRevRangeByScore($key, array $args, $start, $end, $pager=array(), $with_score=false){
        $redis = $this->getRedis($this->server_type);
        $options = array();
        if($pager){
            $options['limit'] = array(($pager['page']-1)*$pager['count'], $pager['count']);
        }
        if($with_score){
            $options['withscores'] = true;
        }

        $list = $redis->zRevRangeByScore($this->getCacheKey($key, $args), $start, $end, $options);
        return $this->decodeZSet($list, $with_score);
    }

    public function isSetMethod($method){
        return strpos('set,setex,setnx,del,delete,incr,incrByFloat,incrBy,decr,decrBy,lPush,rPush,lPushx,rPushx,lPop,rPop,blPop,brPop,lSet,lTrim,listTrim,lRem,lRemove,lInsert,sAdd,sRem,sRemove,sMove,sPop,sInterStore,sDiffStore,rename,renameKey,renameNx,expire,pExpire,expireAt,pExpireAt,setRange,setBit,persist,mset,msetnx,rpoplpush,brpoplpush,zAdd,zRem,zDelete,zRevRange,zRemRangeByScore,zDeleteRangeByScore,zRemRangeByRank,zDeleteRangeByRank,zIncrBy,zUnion,zInter,hSet,hSetNx,hDel,hIncrBy,hIncrByFloat,hMset,hMGet,evaluate,evalSha,evaluateSha,restore,migrate', $method) !== false;
    }

    /**
     * getCacheConfig
     * @param       $key
     * @param array $args
     * @return array
     */
    public  function getCacheKey($key, $args=array()) {
        $config = Config::get('rediskey.'.$key);
        $cache_key = call_user_func_array('sprintf', array_merge(array($config['key']), $args));
        return $cache_key;
    }

    /**
     * getCacheConfig
     * @param       $key
     * @param array $args
     * @return array
     */
	public  function getCacheConfig($key, $args=array()) {
        $config = Config::get('rediskey.'.$key);
        $cache_key = call_user_func_array('sprintf', array_merge(array($config['key']), $args));
        $config['cache_key'] = $cache_key;
        return $config;
    }

    /**
     * @param string $type
     * @return Redis|null
     */
    public function getRedis($type='slave'){
        $key = $this->server_name.'-'.$type;
        if(isset($this->redis_instance[$key])){
            return $this->redis_instance[$key];
        }

        $redis = null;
        try{
            $config = $this->getServerConfig($this->server_name, $type);
            $redis = new Redis();
            $redis->connect($config['host'], $config['port']);
            $this->redis_instance[$key] = $redis;

        }catch (Exception $e){
            $title = 'Redis Server访问失败';
            $content = sprintf('Redis Server连接失败：server name:%s [%s],IP is %s', $this->server_name, $type, Until::getIp());
            //每隔10分钟发送一次报警
            MailManager::sendWarnning($e->getmessage());
            $redis = new Foo_Redis();
        }
        return $redis;
    }

    private  function getServerConfig($name, $type) {

    	$config = Config::get('redisconf.'. $name);
    	if ($config && $config[$type]) {
    		return $config[$type];
    	}else {
    		throw new Exception("redis配置信息没有获取到,报警服务器ip是". Until::getIp());
    	}
    }

    private  function callRedisMethod($method, $key, $args, $values){
        $redis = $this->getRedis('master');
        $config = $this->getCacheConfig($key, $args);
        $data = array();
        foreach($values as $v){
            $data[] = json_encode($v);
        }

        array_unshift($data, $config['cache_key']);
        $ret = call_user_func_array(array($redis, $method), $data);
        if ($config['expire']) {
            $this->setExpire($redis, $config['cache_key'], $config['expire']);
        }
        return $ret;
    }

    private function setExpire(Redis $redis, $key, $expire){
        if($expire){
            $redis->expire($key, $expire);
        }

    }

    /**
     * decode zset
     * @param $list
     * @param $with_score
     * @return array
     */
    private function decodeZSet($list, $with_score){
        $result = array();
        if($with_score){
            foreach($list as $key=>$value){

                $result[json_decode($key, true)] = $value;
            }
        }else{
            foreach($list as $value){
                $result[] = json_decode($value, true);
            }
        }
        return $result;
    }

}