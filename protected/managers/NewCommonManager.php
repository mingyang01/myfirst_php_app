<?php
/**
 * 对用户和部门等基本数据的一个新的封装
 * @author linglingqi@meilishuo.com
 * @date 2015-09-25
 */
class NewCommonManager extends Manager {

	/**
	 * 根据用户uid获取用户信息
	 * @param unknown $uid
	 */
    public static function getUsersByuid($uid) {

    	$redis = new Redisdb();
    	$key = 'user_info_byid';
    	$params = array('id'=>$uid);

    	$result = $redis->get($key, $params);
    	if ($result === false) {
    		$result = self::_get('/user/show', $params);
    		$redis->set($key, $params, json_encode($result));
    	} else {
    		$result = json_decode($result, true);
    	}

    	return $result;
    }

    /**
     * 根据用户邮箱前缀获取用户信息
     * @param unknown $username
     */
    public static function getUserByName($username) {

    	$params = array('mail'=>$username);
    	$redis = new Redisdb();
    	$key = 'user_info_byname';

    	$result = $redis->get($key, $params);
    	if ($result === false) {
    		$result = self::_get('/user/show', $params);
    		$redis->set($key, $params, json_encode($result));
    	} else {
    		$result = json_decode($result, true);
    	}
    	return $result;
    }

    public static function getUserLeader($uid) {

    	$user = self::getUsersByuid($uid);
    	$leader = $user['direct_leader'];
    	return $leader;
    }

    public static function getUserDepartId($uid) {
    	$user = self::getUsersByuid($uid);
    	if (isset($user['parent_id_2'])) {
    		return $user['parent_id_2'];
    	}
    	return $user['departid'];
    }

    /**
     * 获取部门下的用户
     * @param unknown $depart_id
     */
    public static function getDepartUsers($depart_id) {

    	$params = array('depart_id'=>$depart_id);
    	$redis = new Redisdb();
    	$key = 'depart_users_list';
    	$result = $redis->get($key, $params);
    	if ($result === false) {
    		$result = self::_get('/user/show_depart_user', $params);
    		$redis->set($key, $params, json_encode($result));
    	} else {
    		$result = json_decode($result, true);
    	}
    	return $result;
    }

    /**
     * 根据部门ID获取部门信息
     * @param unknown $depart_id 部门ID
     */
    public static function getDepartByDid($depart_id) {

    	$params = array('depart_id'=>$depart_id);
    	$redis = new Redisdb();
    	$key = 'depart_info_list';
    	$result = $redis->get($key, $params);
    	if ($result === false) {
    		$result = self::_get('/user/show_depart', $params);
    		$redis->set($key, $params, json_encode($result));
    	} else {
    		$result = json_decode($result, true);
    	}
    	return $result;

    }

    /**
     * 通过depart ID获取权限平台的部门角色
     */
    public static function getDepartRoleByDid($depart_id) {

    	//获取部门下的用户；然后返回所对应的部门名称和部门id
    	$users = self::getDepartUsers($depart_id);
    	$department = '';
    	if($users){
    		$department = self::getUserDepart($users[0]['id']);
    	}
		return $department;
    }

    /**
     * 根据部门角色名称获取部门ID
     */
    public static function getDepartidByDepartRole($depart_role) {

    	$offset = strpos($depart_role, '-');
    	if ($offset === false) {
    		$offset = strpos($depart_role, '－');
    	}

    	$depart_name = substr($depart_role, $offset+1);

    	$id = self::getDepartIdByName($depart_name);
		return $id;
    }

    public static function getSecondeDepartInfo() {

    	$departs = self::getfirstDepartInfo(1);
    	$data = array();
    	foreach($departs as $val) {
    		if ($val['sub_depart']) {
    			foreach ($val['sub_depart'] as $second) {
    				unset($second['sub_depart']);
    				$data[$second['depart_id']] = $second;
    				$data[$second['depart_id']]['parent_depart_name'] = $val['depart_name'];
    				$data[$second['depart_id']]['parent_depart_id'] = $val['depart_id'];
    				$data[$second['depart_id']]['deep'] = 3;
    			}
    		} else {
    			$data[$val['depart_id']] = $val;
    			$data[$val['depart_id']]['last'] = 2;
    		}
    	}
    	return  $data;
    }

    public static function getfirstDepartInfo($show_child=0) {

    	$departs = self::getDepartsList(1);
    	$data = array();
    	foreach($departs as $val) {
    		if ($val['sub_depart']) {
    			foreach ($val['sub_depart'] as $second) {
    				if (!$show_child) {
    					unset($second['sub_depart']);
    				}
    				$data[$second['depart_id']] = $second;
    				$data[$second['depart_id']]['parent_depart_name'] = $val['depart_name'];
    				$data[$second['depart_id']]['parent_depart_id'] = $val['depart_id'];
    				$data[$second['depart_id']]['deep'] = 2;
    			}
    		} else {
    			$data[$val['depart_id']] = $val;
    			$data[$val['depart_id']]['deep'] = 1;
    		}
    	}
    	return $data;
    }

    public static function getsecondDepartList() {

    	$departs = self::getfirstDepartInfo();
    	$data = array();
    	foreach($departs as $val) {
    		if ($val['sub_depart']) {
    			foreach ($val['sub_depart'] as $second) {
    				$data[$second['depart_id']] = $second['depart_name'];
    			}
    		} else {
    			$data[$val['depart_id']] = $val['depart_name'];
    		}
    	}
    	return  $data;
    }

    public static function getDepartRole(){

    	$depart = self::getSecondeDepartInfo();

    	$var = array();
    	foreach ($depart  as $key => $value) {
    		$users = self::getDepartUsers($value['depart_id']);
    		if($users){
    			$department = self::getUserDepart($users[0]['id']);
    			$var[$value['depart_id']] =  $department;
    		}
    	}
    	return $var;
    }

    public static function getUserDepart($uid) {

    	$user = self::getUsersByuid($uid);
    	return $user['depart'];
    }

    public static function getUserDepartByName($name) {
    	$user = self::getUserByName($name);
    	return $user['depart'];
    }

    /**
     * 获取所有的部门信息
     */
    public static function getDepartsList() {

    	$params = array();
    	$redis = new Redisdb();
    	$key = 'depart_all_list';
    	$result = $redis->get($key);
    	if ($result === false) {
    		$result = self::_get('/user/show_depart', $params);
    		$redis->set($key, $params, json_encode($result));
    	} else {
    		$result = json_decode($result, true);
    	}
    	return $result;
    }

    /**
     * 获取所有的部门ID和部门名称
     */
    public static function getAllName($results, &$data) {

    	foreach ($results as $key => $value) {

    		if(empty($value['sub_depart'])) {
    			$data[$value['depart_id']] = $value['depart_name'];
    			continue;
    		} else {
    			$data[$value['depart_id']] = $value['depart_name'];
    			$results = $value['sub_depart'];
    			self::getAllName($results, $data);
    		}

    	}
    	return $data;
    }

        /**
         * 根据部门名词获取部门id
         */
        public static function getDepartIdByName($name) {

        	$list = self::getDepartsList();
        	$data = array();
        	$data = self::getAllName($list, $data);
        	foreach ($data as $key=>$val) {
        		if ($val == $name) {
        			$info = $key;
        		}
        	}
    		return $info;
        }

    //     /**
    //      * 获取部门的已有逻辑下的格式
    //      * @param unknown $uid
    //      */
    //     public static function getsuperDepart($depart_id) {

    // 		$depart = self::getDepartByDid($depart_id);
    // 		$second_list = self::getsecondDepartList();
    // 		if (in_array($depart['depart_name'], $second_list)) {
    // 			return $depart['depart_name'];
    // 		} else {
    // 			$parent_depart = self::getDepartByDid($depart['sup_depart_id']);
    // 			if (in_array($parent_depart, $second_list)) {
    // 				return $parent_depart['depart_name'] .'-'. $depart['depart_name'];
    // 			} else {
    // 				$super =  self::getDepartByDid($parent_depart['sup_depart_id']);
    // 				return $super['depart_name'] .'-'. $parent_depart['depart_name'];
    // 			}
    // 		}

    //     }


    public static function getUsernameByuid($uid) {

    	$user = self::getUsersByuid($uid);
    	$mail = explode('@',$user['mail']);
    	return $mail[0];
    }

    public static function getUserRealnameByuid($uid) {
    	$user = self::getUsersByuid($uid);
    	return $user['name'];
    }

    private static function _url($url) {

    	$params = Yii::app()->params;
    	$token = $params['TOKEN'];
    	$api = $params['SPEED'];
    	return $api . $url ."?token=$token";
    }

    private static function _get($url, $params, $timeout=30) {

    	$url = self::_url($url) . '&' . http_build_query($params);
    	$curl = Yii::app()->curl;
		$result = $curl->get($url, '', $timeout);
		return self::_formateReturn($result);
    }

    private static function _post($url, $params, $timeout=30) {

    	$url = self::_url($url) . '&' . http_build_query($params);
    	$curl = Yii::app()->curl;
    	$result = $curl->post($url, $params, '', $timeout);
    	return self::_formateReturn($result);
    }

    private static function _formateReturn($result) {

    	$data = array();
    	if($result['http_code'] == 200 && $result['body']) {
    		$data = json_decode($result['body'], true);
    		if ($data['code'] == 200) {
    			$data = $data['data'];
    		}
    	}
    	return $data;
    }
}
