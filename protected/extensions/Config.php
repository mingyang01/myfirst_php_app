<?php
/**
 * 配置信息读取类
 *
 * @author  linglingqi@meilishuo.com
 */
class Config {

    private static $config_values = array();

    /**
     * 读取配置信息, 找不到配置文件时，抛出异常
     *
     * @param string $path 节点路径，第一个是文件名，使用点号分隔。如:"rediskey","rediskey.test"
     *
     * @return array/string    成功返回数组或string
     */
    static public function get($path) {

        if (isset(self::$config_values[$path])) {
            return self::$config_values[$path];
        }

        $arr         = explode('.', $path, 2);
        $config_path = APP_PATH . '/protected/config/' . $arr[0] . '.php';
        $config_value = array();
		if (file_exists($config_path)) {

			$config = require($config_path);
			$config_value = isset($config[$arr[1]]) ? $config[$arr[1]] : array();
			$config_value = is_object($config_value) ? $config_value->toArray() : $config_value;
		}else {
			throw new Exception("读取的配置信息不存在{$path}", 2001);
		}
        self::$config_values[$path] = $config_value;

        return $config_value;
    }


    /**
     * 读取配置信息，找不到配置时，使用默认值
     *
     * @param  string $path 节点路径，第一个是文件名，使用点号分隔。如:"rediskey","rediskey.test"
     *  @param object $default $path 不存在时使用
     * @return array/string    返回配置value或者$default
     */
    static public function getWithDefault($path, $default=null) {
        $conf = $default;
        try {
            $conf = self::get($path);
        } catch (Exception_System $e){
            //ignore
        }
        return $conf;
    }

}
