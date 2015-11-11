<?php
class AuthManager extends Manager {

	const ROLE_TYPE = 2;    //角色的类型为2
	const CHILD_TYPE = 1;  //子主题

    public function checkAccess($operation, $uid,$strtolower =true, $params=array())
    {

        if(!$strtolower || $strtolower == 'false')
        {
            $operation = strtolower($operation);
        }
        $auth = yii::app()->authManager;
        $business = explode('/', $operation);
        $business = $business[0];

        $isstrick = self::strickFunction($operation);
        //权限严格验证部分功能
        if (!$isstrick) {
        	if($this->superRule($uid) || $this->function->isDeveloper($business,$this->getUserName($uid)) || $this->whitelistRule($operation))
        	{
        		return true;
        	}
        }

        $access=$auth->checkAccess($operation,$uid);
        //$access=Yii::app()->getAuthManager()->checkAccess($operation,$uid);
        return $access;
    }

    function whitelistRule($operation)
    {
        $db = yii::app()->sdb_eel;
        $sql = "select controller, action from developer_whitelist";
        $results = $db->createCommand($sql)->queryAll();
        $allwhitelist = array();

        foreach ($results as $key => $value) {
            $allwhitelist [] = strtolower($value['controller']."/".$value['action']);
        }
        if(in_array(strtolower($operation), $allwhitelist))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

     public function  superRule($uid)
    {
        $auth = yii::app()->authManager;
        if($auth->checkAccess('super', $uid))
        {
            return true;
        }
    }


    public function getRoleUsers($role)
    {
        $db = Yii::app()->sdb_eel;
        $sql = "select userid from developer_AuthAssignment where itemname='$role' ";
        $selected = $db->createCommand($sql)->queryColumn();
        return $selected;
    }

    function getSid($umail) {

        $name = explode("@",$umail);
        $comm = new CommonManager();
        $results = $comm->getUser($name[0]);
        if ($results) {
        	$sid = $results['id'];
            return $sid;
        } else {
            return '';
        }

    }

    function getId($name) {
    	$comm = new CommonManager();
        $results = $comm->getUser($name);
        if ($results) {
        	$sid = $results['id'];
            return $sid;
        } else {
            return '';
        }
    }

    function getDepart($username) {

        $output = Yii::app()->curl->get("http://api.speed.meilishuo.com/user/show?token=e98cfc1a4f23ae1699919c505ae2ba04&mail=".$username);
        $results = (array)json_decode($output['body']);
        $results = $results['data'];
        if(!empty($results->depart))
        {
            $depart = $results->depart;
        }
        else
        {
            $depart = "";
        }
        return $depart;
    }

    function getLeader($username)
    {
        $output = yii::app()->curl->get("http://api.speed.meilishuo.com/user/show?token=e98cfc1a4f23ae1699919c505ae2ba04&mail=".$username);
        $results = (array)json_decode($output['body']);
        $results = $results['data'];
        $depart = $results->direct_leader;
        if($depart=='143'){
            return '172';
        }
        return $depart;
    }
    function getName($uid) {

    	$redis = new Redis();
    	$redis->connect('127.0.0.1');
        //$redis->connect('172.16.12.117');
    	$redis->select(9);

    	$redis_key = sprintf("auth_username_%s",$uid);
    	if ($redis->exists($redis_key)) {
    		return json_decode($redis->get($redis_key), true);
    	}
        $output = yii::app()->curl->get("http://api.speed.meilishuo.com/user/show?token=e98cfc1a4f23ae1699919c505ae2ba04&id=".$uid);
        $results = (array)json_decode($output['body']);
        $results = $results['data'];
        $depart = $results->name;
        $redis->setex($redis_key, "86400", json_encode($depart));
        return $depart;
    }
    function getUserName($uid)
    {
        $output = yii::app()->curl->get("http://api.speed.meilishuo.com/user/show?token=e98cfc1a4f23ae1699919c505ae2ba04&id=".$uid);
        $results = (array)json_decode($output['body']);
        if(!empty($results))
        {
            $results = $results['data'];
        }
        if(isset($results->mail))
        {
            $mail = $results->mail;
            $name = explode('@', $mail);
            $name = $name[0];
            return $name;
        }


    }
    function getDepartid($username)
    {
        $output = yii::app()->curl->get("http://api.speed.meilishuo.com/user/show?token=e98cfc1a4f23ae1699919c505ae2ba04&mail=".$username);
        $results = (array)json_decode($output['body']);
        $results = $results['data'];
        $depart = $results->departid;
        return $depart;
    }

    function getDeparName($username) {
    	$output = yii::app()->curl->get("http://api.speed.meilishuo.com/user/show?token=e98cfc1a4f23ae1699919c505ae2ba04&mail=".$username);
    	$results = (array)json_decode($output['body']);
    	$results = $results['data'];
    	$depart = $results->depart;
    	return $depart;
    }

    function getDepartByName($status=true)
    {
        $redis = new Redis();
        //$redis->connect('172.16.12.117');
        $redis->connect('127.0.0.1');
        $redis->select(9);

        $redis_key = "depart";
        if($status&&$status!=='false')
        {
            if ($redis->exists($redis_key)) {
                return json_decode($redis->get($redis_key), true);
            }
        }
        $depart = array();
        $output = yii::app()->curl->get("http://api.speed.meilishuo.com/user/show_depart?token=e98cfc1a4f23ae1699919c505ae2ba04");
        $results = (array)json_decode($output['body'], true);
        $results = $results['data'];
        $data = array();

        $data = $this->getAllName($results, $data);
        $redis->set($redis_key, json_encode($data));
        return $data;
    }
    function getAllName($results, &$data)
    {
        foreach ($results as $key => $value) {
            if(empty($value['sub_depart']))
            {
                $data[$value['depart_id']] = $value['depart_name'];
                continue;
            }
            else
            {
                $results = $value['sub_depart'];
                $this->getAllName($results, $data);
            }

        }
        return $data;
    }

    public function getItemByType($type)
    {
        $db = yii::app()->db_eel;
        $sql = "select * from developer_AuthItem where type='$type' ";
        $variable = $db ->createCommand($sql)->queryAll();
        $results =array();
        foreach ($variable as $key => $value) {
             $resultss[] = $value['name'];
        }

        return $resultss;
    }

    public function isSupper($uid)
    {
        $authManager = yii::app()->authManager;
        $variable = $authManager->getAuthItems('2',$uid);
        $roleList = array();
        foreach ($variable as $key => $value) {
            $roleList [] = $key;
        }
        return in_array('super', $roleList);
    }

    /**
     * 获取用户管理的管理员
     */
    public function userRoles($uid) {

    	$authManager = yii::app()->authManager;
    	$roleList = $authManager->getAuthItems('2',$uid);
    	$roles = array();
    	$depart = self::getDepartByName();
    	foreach($depart as $key=>$val) {
    		if ($roleList[$val]) {
    			$roles[$key]=$val;
    		}
    	}
    	return $roles;
    }

	public function getAuthFunction($uid)
    {
        $authManager = yii::app()->authManager;
        $variable = $authManager->getAuthItems(null,$uid);
        //var_dump($this->getItemChild($variable));exit();
        $haveItem = array();
        $this->getItemChild($variable,$haveItem);
        return $haveItem;

    }

	function getItemChild($variable,&$haveItem) {
        $authManager = yii::app()->authManager;
        $roleArray = $this->auth->getItemByType('2');
        $taskArray = $this->auth->getItemByType('1');

        foreach ($variable as $key => $have) {
            if(in_array($key,$taskArray))
            {
                $haveItem []= $key;
                continue;

            }
            else
            {
                if(in_array($key,$roleArray))
                {
                    //var_dump($key);
                    $var = $authManager->getItemChildren($key);
                    $this->getItemChild($var,$haveItem);
                }
            }
        }

    }


    public function getNewAuthFunction($uid) {
    	$authManager = yii::app()->authManager;
    	$variable = $authManager->getAuthItems(null,$uid);
    	//var_dump($this->getItemChild($variable));exit();
    	$haveItem = array();
    	$roleArray = $this->auth->getItemByType('2');
    	$taskArray = $this->auth->getItemByType('1');

    	$this->getNewItemChild($variable, array_flip($taskArray), array_flip($roleArray), $haveItem);
    	return $haveItem;

    }

    function getNewItemChild($variable, $taskArray, $roleArray, &$haveItem) {

    	$authManager = yii::app()->authManager;
    	foreach ($variable as $key => $have) {
    		if(isset($taskArray[$key])) {
    			$haveItem []= $key;
    			continue;

    		} else {
    			if(isset($roleArray[$key])) {
    				//var_dump($key);
    				$var = $authManager->getItemChildren($key);
    				$this->getNewItemChild($var, $taskArray, $roleArray, $haveItem);
    			}
    		}
    	}

    }

    /**
     * 获取用户的管理员权限
     */
    public function getDepartManage($depart) {

    	$db = yii::app()->sdb_eel;
    	$sql = "select a.userid from developer_AuthAssignment a, developer_AuthItem b where b.name=a.itemname and b.type=2 and b.name='". $depart ."'";
    	$result = $db->createCommand($sql)->queryColumn();
    	return $result;
    }

    public function getDepartFunctions($depart) {

    	$haveItem = array();
    	$variable = array($depart=>1);
    	self::getItemChild($variable, $haveItem);
		return $haveItem;
    }

    /**
     * 获取风控管理员
     */
    public function getAdminOfFengkong() {
    	$fengkong_item = "规则与风控部－规则与风控/超级管理员";
    	$db = yii::app()->db_eel;
    	$sql = "select * from `developer_AuthAssignment` where itemname='" .$fengkong_item ."'";
    	$result = $db->createCommand($sql)->queryAll();
    	return $result;
    }

    /**
     * 获取超级管理员
     */
    public function getSuper() {

    	$db = yii::app()->db_eel;
    	$sql = "select userid,itemname from `developer_AuthAssignment` where itemname in (select name from `developer_AuthItem` where type=2 and name='super')";
    	$role = $db->createCommand($sql)->queryAll();
    	$admin = array();
    	foreach($role as $val) {
    		if (!$val['itemname'] || !$val['userid']) {
    			continue;
    		}
    	}
    	return $role;
    }

    /**
     * 获取部门有权限的功能
     */
    public function getDepartFunction($depart, $where='') {

    	$db = yii::app()->sdb_eel;
    	$sql = "select b.business cname, b.id, b.type, b.business,b.funname,b.description,b.item from `developer_AuthItemChild` a,
    		`developer_function` b where a.parent='$depart' and b.item=a.child and b.status!=". FunctionManager::FUN_STATUS_DEL ;
    	$where && $sql= $sql.$where;
    	$role = $db->createCommand($sql)->queryAll();
    	return $role;
    }


    /**
     * 根据功能名称获取对该功能有权限的用户
     * @param  $function
     */
    public function getAccessUserByFunction($function_id) {

     	$admin = $haveItem = $role_admin = $all_admin = $usename =array();

    	$function = new FunctionManager();
    	$funs = $function->getFunction($function_id);
    	$business = $funs[0]['business'];
    	$role = $funs[0]['item'];
    	//获取开发者
    	$developer = $function->getDevelopers($business);

    	//对他有access权限的
    	$variable = array($role=>1);
    	 self::getItemChild($variable, $haveItem);
    	 if ($haveItem) {
    	 	foreach ($haveItem as $val) {
    	 		$role_admin = array_merge(self::getRoleUsers($val), $role_admin);
    	 	}
    	 }
    	 if ($role_admin) {
    	 	foreach ($role_admin as $aval) {
    	 		$tmp = self::getUserName($aval);
    	 		if ($tmp) {
    	 			$usename[] = $tmp;
    	 		}
    	 	}
    	 }
    	 $result = array_merge($usename, $developer);
    	 return array_unique($result);
    }

    /**
     * 获取用户有权限的业务
     * @param  $uid
     */
    public function getUserAuthBusiness($uid) {
    	$db = yii::app()->sdb_eel;
    	$sql = "select itemname from developer_AuthAssignment where userid=". $uid;
    	$itemname = $db->createCommand($sql)->queryColumn();

    	$bus = new BusinessManager();
    	$businesses = $bus->getAllBusinessId();
    	$userbus = array();
    	foreach ($itemname as $val) {
    		$str = strpos($val, '/');
    		$tmp ='';
    		if ($str) {
    			$tmp = substr($val, 0, $str);
    			if (in_array($tmp, $businesses)) {
    				$userbus[$tmp] = $tmp;
    				continue;
    			}
    		} else {
    			$tmp = $val;
    		}
    		$item = new ItemManager();
    		$child = $item->getChileItem($val);
    		if ($child) {
    			foreach($child as $cval) {
    				$str = strpos($cval, '/');
    				$tmp ='';
    				if ($str) {
    					$tmp = substr($cval, 0, $str);
    					if (in_array($tmp, $businesses)) {
    						$userbus[$tmp] = $tmp;
    					}
    				}
    			}
    		}
    	}
    	return $userbus;
    }

    public function delLeader($users) {
    	$leader = array('xiwang', 'yingliu', 'yirongxu', 'wenzhuoli');
    	foreach ($users as $key=>$val) {
    		if (in_array($val, $leader)) {
    			unset($users[$key]);
    			continue;
    		}
    	}
    	return $users;
    }

    //权限收回时的最小的判断，只判断是否有直接赋值
    public function isAssign($item ,$userid) {

    	$db = yii::app()->sdb_eel;
    	$sql = "select * from developer_AuthAssignment where userid=$userid and itemname='$item'";
    	$result = $db->createCommand($sql)->queryRow();
    	return $result;
    }

    /**
     * 获取角色的有权限用户
     */
    public function getItemAssigns($item) {

    	if (!is_array($item)) {
    		return false;
    	}

    	$db = yii::app()->sdb_eel;
    	$sql = "select * from developer_AuthAssignment where itemname in ('". implode("','", $item) ."')";
    	$result = $db->createCommand($sql)->queryAll();
    	return $result;
    }

    /**
     * 获取白名单
     */
    public function  whitelists($project='') {

        $db = yii::app()->sdb_eel;
        if ($project) {
        	$sql = "select controller, action from developer_whitelist where project='$project'";
        } else {
        	$sql = "select controller, action from developer_whitelist";
        }

        $results = $db->createCommand($sql)->queryAll();
        $allwhitelist = array();

        foreach ($results as $key => $value) {
            $tmp = strtolower($value['controller']."/".$value['action']);
            $allwhitelist [$tmp] = $tmp;
        }
        return $allwhitelist;
    }

    //判断不是严格控制的权限
    public function strickFunction($operate) {

    	$stricks = ConfigManage::strickCheckFunction();
    	if (isset($stricks[$operate])) {
    		return true;
    	}else {
    		return false;
    	}
    }

}
