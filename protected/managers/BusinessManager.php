<?php
/**
 * 业务相关的操作
 * @author linglingqi
 * @version 2015-05-19
 */
class BusinessManager extends Manager {
	/**
	 * 获取所有的业务信息
	 * @return unknown
	 */
    public function getALLBusiness() {

    	$db = Yii::app()->sdb_eel;
    	$sql = "select * from `developer_business` order by id desc";
    	$results = $db->createCommand($sql)->queryAll();
    	return $results;
    }

    /**
     * 获取业务号
     */
    public function getAllBusinessId() {
    	$businesses = self::getALLBusiness();
    	$ids = array();
    	foreach ($businesses as $val) {
    		$ids[$val['business']] = $val['business'];
    	}
    	return $ids;
    }

    /**
     * 根据业务号获取业务信息
     * @param unknown $business
     * @return unknown
     */
    public function getInfoByBusiness($business) {

    	$db = Yii::app()->sdb_eel;
    	$sql = "select * from `developer_business` where business='$business'";
    	$results = $db->createCommand($sql)->queryRow();
    	return $results;
    }

    /**
     * 获取用户是开发者的功能
     */
    public function getUserBusiness($username) {

    	$db = Yii::app()->sdb_eel;
    	$sql = "select * from `developer_business` where developer like '%$username%'";
    	$results = $db->createCommand($sql)->queryAll();
    	foreach($results as $key=>$val) {
    		$developer = explode(',', $val['developer']);
			if(!in_array($username, $developer)) {
				unset($results[$key]);
			}
    	}
    	$results = ArrFomate::hashmap($results, 'business');
    	return $results;
    }

    /**
     * 根据名称获取业务号
     */
    public function getBusinessByName($name) {

    	$db = Yii::app()->sdb_eel;
    	$sql = "select * from `developer_business` where cname='$name'";
    	$results = $db->createCommand($sql)->queryRow();
    	return $results;
    }

    /**
     * 根据名称获取业务号
     */
    public function getBusinessByHost($host) {

    	$redis = new Redisdb();
    	$key = 'business_get_by_host';
    	$results = $redis->get($key,array($host));
    	if ($results===false) {
    		$db = Yii::app()->sdb_eel;
    		$sql = "select * from `developer_business` where description='$host'";
    		$results = $db->createCommand($sql)->queryRow();
    		$redis->set($key, array($host), json_encode($results));
    	}else {
    		$results = json_decode($results, true);
    	}

    	return $results;
    }
}
