<?php
class FunctionManager extends Manager {

	public static $function_share = 1;	//共享功能
	public static $function_nomal = 0;	//普通功能
	public static $function_special = 2;  //特殊功能

	//type 决定功能类型
	const NOMAL_TEXT = "普通";
	const SHARE_TEXT = "共享";
	const SPECIAL_TEXT = "特殊";

	//功能的审核状态
	const FUN_STATUS_UNSUBMIT = 0; //功能未提交审核
	const FUN_STATUS_UNCHECK = 1;  //功能未审核
	const FUN_STATUS_CHECK = 2; //功能已审核
	const FUN_STATUS_DEL = 3; //功能已删除
	const FUN_STAUTS_SELECT_DEFAULT = 99; //默认查询的时候不判断功能状态

	public static $file = array(
			'funname'		=>	'funname',
			'description'	=>	'description',
			'unix'			=>	'unix',
			'url'			=>	'url',
			'sign'			=>	'sign',
			'type'			=>	'type',
			'status'		=>	'status'
	);

	public static $where = array(
			'id'		=>		'id',
			'business'	=>		'business',
			'funname'	=>		'funname',
			'item'		=>		'item'
	);

    // 获取项目功能
    function getFunctions($business,$funname='', $status='all') {
        $where = '';
        if($funname) {
            $where =" and funname like '%$funname%' ";
        }
        if ($status != 'all') {
        	if ($status == 'nomal') {
        		$where .= " and status !=". self::FUN_STATUS_DEL;
        	}
        }
        $sql = "select id, funname name,item from developer_function where business='$business'".$where;
        $db = yii::app()->sdb_eel;
        $results = $db->createCommand($sql)->queryAll();
        return $results;
    }

    // 获取项目开发者
    static function getDevelopers($business) {
        $db = yii::app()->sdb_eel;
        $sql = "select developer from developer_business where business= '$business' ";
        $results = $db->createCommand($sql)->queryScalar();
        return explode(',', $results);
    }

    static function isDeveloper($business, $username) {
        return in_array($username, self::getDevelopers($business));
    }

   function isDeveloperByid($business, $id) {

   		$comm = new CommonManager();
        $username = NewCommonManager::getUsernameByuid($id);
        return in_array($username, self::getDevelopers($business));
    }

    function getFunction($id) {
        $db = yii::app()->sdb_eel;
        $sql = "select * from developer_function where id= '$id' ";
        $results = $db->createCommand($sql)->queryAll();
        return $results;
    }


    //获得用户已有权限
    function getAuthOfFunction($businees = NULL,$id)
    {
        $where = '';
        if(empty($businees)) {
            $where = "where business = '$businees' ";
        }
        $sql = "select id, funname name from developer_function ".$where;
        $db = yii::app()->sdb_eel;
        $results = $db->createCommand($sql)->queryAll();
        foreach ($results as $key => $value) {
            # code...
        }

    }

    public function getFunctionItem($business = '' ,$funname = '')
    {
        $sql = "select funname, item from developer_function where business='$business' and funname='$funname' ";
        $db = yii::app()->sdb_eel;
        $results = $db->createCommand($sql)->queryAll();
        if($results)
        {
            $results = $results[0];
            return $results['item'];
        }
        else
        {
            return '';
        }

    }

    public function checkStatus($business = '',$funname = '')
    {
        $db = yii::app()->db_eel;
        $sql = "select status from developer_function where business= '$business' and funname = '$funname' ";
        $results = $db->createCommand($sql)->queryAll();
        if($results)
        {
            $results = $results[0];
            return $results['status'];
        }
        else
        {
            return '';
        }
    }

    public function getFunname($sign = '')
    {
        $db = yii::app()->db_eel;
        $sql = "select funname from developer_function where sign = '$sign' ";
        $results = $db->createCommand($sql)->queryAll();
        if($results)
        {
            $results = $results[0];
            return $results['funname'];
        }
        else
        {
            return '';
        }
    }

    public function getAllFun()
    {
        $db = yii::app()->sdb_eel;
        $sql = "select * from developer_function";
        $results = $db->createCommand($sql)->queryAll();
        $temp = array();
        foreach ($results as $key => $value) {
            $temp[$value['item']] = $value['funname'];
        }
        return $temp;
    }

    public function FunctionItemToFunction()
    {
        $sql = "select funname, item from developer_function";
        $db = yii::app()->sdb_eel;
        $variable = $db->createCommand($sql)->queryAll();
        $results = array();
        foreach ($variable as $key => $value) {
            $results[$value['item']] = $value['funname'];
        }
        return $results;
    }
    public function urlToFunctionName($url,$sid,$business) {

    	$url = trim($url);
        $sql = "select funname,business from developer_function where business='$business' and url like '$url%' ";
        $db = yii::app()->sdb_eel;
        $collectColmun = $this->work->getCollect($sid,$business);
        $variable = $db->createCommand($sql)->queryAll();
        $results = array();
        $reData = array();
        if($variable)
        {
            $results = $variable[0];
            $reData['business'] = $results['business'];
            $reData ['funname']= $results['funname'];
            $reData ['status'] = in_array($results['funname'], $collectColmun[$results['business']]);
            $reData['menu'] = '';

            return $reData;
        } else {
            return '';
        }
    }

    public function getMenuByUrl($url,$sid,$business) {

    	$url = trim($url);
        $sql = "select f.funname, f.url, m.first,m.second,m.third,m.business from developer_function f,developer_menu m where f.business='$business' and f.id=m.function and f.url like '$url%' ";
        $db = yii::app()->sdb_eel;
        $collectColmun = $this->work->getCollect($sid,$business);
        $variable = $db->createCommand($sql)->queryAll();

        $reMenu = array();
        if($variable)
        {
            foreach ($variable as $key => $value) {
                $reMenu['business'] = $value['business'];
                $reMenu['funname'] = $value['funname'];
                $reMenu ['status'] = in_array($value['funname'], $collectColmun[$business]);
                $reMenu['menu']['first']= $value['first'];
                $reMenu['menu']['second'] = $value['second'];
                $reMenu['menu']['third'] = $value['third'];
            }
            return $reMenu;
        }
        else
        {
        	return '';
        }

    }

    /**
     * 修改功能信息  ；除了item不能修改，其余的都能修改
     */
    public function updateFunction($id, $business, $funname='', $url='', $sign='', $desc='') {

    	$db = yii::app()->db_eel;
    	$results = array();

    	$is_exist = self::getFunctionInfoById($id);
    	if (!$is_exist) {
    		throw new Exception('功能不存在，请检查功能ID！');
    	} else{
    		$url =  empty($url) ? $is_exist['url'] : $url;
			$sign = empty($sign) ? $is_exist['sign'] : $sign;
    		$new_funname = empty($funname)? $is_exist['funname'] : $funname;
    	}
    	if ($funname) {
    		$select = "select id,funname from developer_function where business='". $business ."' and funname='". $funname ."'";
    		$data = $db->createCommand($select)->queryAll();
    		if ($data && $data[0]['id']!=$id) {
    			throw new Exception($business.'业务下功能名重复，请检查后再添加！');
    		}
    	}
    	$sql = "update developer_function set funname = ?, description =?,unix=?, url=?, sign=? where id= ?";
    	$results = $db->createCommand($sql)->execute(
    			array($new_funname,$desc,date('Y-m-d H:m:s'), $url, $sign, $id)
    	);

    	return $results;
    }

    /**
     * 根据ID获取功能信息
     */
    public function getFunctionInfoById($id) {
    	$db = yii::app()->db_eel;
    	$select = "select * from developer_function where id='$id'";
    	$data = $db->createCommand($select)->queryRow();
    	return $data;
    }

    /**
     * 根据条件获取功能列表
     */
    public function getFunctionList($business,$funname='') {

    	$db = yii::app()->db_eel;
    	$sql = "select * from developer_function where business = '$business' ";
    	if($funname) {
    		$sql = "select * from developer_function where business = '$business' and  funname like '%$funname%' ";
    	}
    	$total = $db->createCommand($sql)->queryAll();
    	$total = count($total);

    	$where = "";
    	if ($business) {
    		$where = " where business = '$business'";
    		if($funname) {
    			$where = " where business = '$business' and funname like '%".$funname."%'";
    		}
    	}
    	$sql = "select * from developer_function". $where;
    	$results = $db->createCommand($sql)->queryAll();
    	foreach ($results as $key => $value) {
    		$results[$key]['option']=$value['status'];
    	}
    	$results = array("rows"=>$results,"total"=>$total);
    	return $results;
    }

    /**
     * 获取功能类型
     */
    public function getFunctionKinds() {

    	$db = yii::app()->sdb_eel;
    	$sql = "select distinct type from developer_function";
    	$types = $db->createCommand($sql)->queryColumn();
    	$result = array();
    	foreach ($types as $val) {
    		if ($val == $this::$function_special) {
    			$result[$val] = self::SPECIAL_TEXT;
    		} elseif ($val == $this::$function_share) {
    			$result[$val] = self::SHARE_TEXT;
    		} else {
    			$result[$val] = self::NOMAL_TEXT;
    		}
    	}
    	return $result;
    }

    /**
     * 根据功能item获取功能信息
     */
    public function getFunctionByItem($item) {

    	$db = yii::app()->sdb_eel;
    	$sql = "select * from developer_function where item='{$item}'";
    	$result = $db->createCommand($sql)->queryRow();
    	return $result;
    }

    // 获取项目功能
    function getFunctionCount($business,$funname='') {

    	$where = '';
    	if($funname) {
    		$where =" and funname like '%$funname%' ";
    	}
    	$sql = "select count(*) from developer_function where business='$business'".$where;
    	$db = yii::app()->sdb_eel;
    	$results = $db->createCommand($sql)->queryColumn();
    	return $results[0];
    }

    // 获取项目功能
    function getFunctionByPage($business,$funname='', $status='all', $page=1, $count=20) {

    	$where = '';
    	$offsset = ($page -1) * $count;
    	$limit = " limit $offsset, $count";

    	if($funname) {
    		$where =" and funname like '%$funname%' ";
    	}
    	if ($status != 'all') {
    		if ($status == 'nomal') {
    			$where .= " and status !=". self::FUN_STATUS_DEL;
    		}
    	}
    	$sql = "select  id, funname name,item from developer_function where business='$business'".$where. $limit;
    	$db = yii::app()->sdb_eel;
    	$results = $db->createCommand($sql)->queryAll();
    	return $results;
    }

    /**
     * 根据url获取功能信息
     */
    public function getFunctionsByUrl($url) {

    	$db = yii::app()->sdb_eel;
    	$sql = "select * from developer_function where url='$url'";
    	$results = $db->createCommand($sql)->queryAll();
    	return $results;
    }

    public function getfunctionByItems($items) {

    	if (!is_array($items)) {
    		$items = array($items);
    	}

    	$results = array();
    	if(count($items)>50) {
    		$chunks = array_chunk($items, 100);
    		foreach ($chunks as $ids) {
    			$tmp = array();
    			$where_str = implode("','", $ids);
		    	$db = yii::app()->sdb_eel;
		    	$sql = "select * from developer_function where item in ('". $where_str ."')";
		    	$tmp = $db->createCommand($sql)->queryAll();
		    	$results = array_merge($results, $tmp);
    		}
    	} else {
    		$where_str = implode("','", $items);

    		$db = yii::app()->sdb_eel;
    		$sql = "select * from developer_function where item in ('". $where_str ."')";
    		$results = $db->createCommand($sql)->queryAll();
    	}

    	return $results;
    }

    /**
     * 根据功能名称和业务号获取功能的详细信息
     */
    public function getFunctionInfo($business, $funname, $status='all') {

    	$db = yii::app()->sdb_eel;
    	//不区分获取功能的状态
    	if ($status == 'all') {
    		$sql = "select * from `developer_function` where business='$business' and funname='$funname'";
    	} elseif($status == 'nomal') { //获取功能的非删除状态；
    		$sql = "select * from `developer_function` where business='$business' and funname='$funname' and status!=". self::FUN_STATUS_DEL;
    	}
    	return $db->createCommand($sql)->queryRow();
    }

    /**
     * 根据功能名称和业务号删除功能
     */
    public function DelFunciton($business, $funname) {

    	if (!$business || !$funname) {
    		return false;
    	}
    	$db = yii::app()->db_eel;
    	$transaction=$db->beginTransaction();
    	try {
			$info = self::getFunctionInfo($business, $funname);
			if ($info) {
				//先查询；然后修改功能表；然后删除菜单
				$sql = "update `developer_function` set `status`=? where `funname`=? and `business`=?";
				$result = $db->createCommand($sql)->execute(array(self::FUN_STATUS_DEL, $funname, $business));
				if ($result) {
					$menu = new MenuManager();
					$menu->deleteMenu($info['id']);
				}

				//取消收藏
				$collect = new CollectManager();
				try {
					$collect->deleteCollect($business, $funname);
				} catch(Exception $e) {

				}

				$transaction->commit();
			}
    	} catch (Exception $e) {
    		$transaction->rollBack();
    		throw new Exception('功能删除失败，请重试');
    	}
    	return  true;
    }

    /**
     * 根据ID删除功能信息
     */
    public function DelFunctionByID($id) {

    	$db = yii::app()->db_eel;
    	$transaction=$db->beginTransaction();

    		$sql = "update `developer_function` set `status`=? where `id`=?";
    		$result = $db->createCommand($sql)->execute(array(self::FUN_STATUS_DEL, $id));
    		if ($result) {
    			$menu = new MenuManager();
    			$menu->deleteMenu($id);
    		}
    		$function = self::getFunction($id);
    		//取消收藏
    		$collect = new CollectManager();
    		try {
    			$collect->deleteCollect($function[0]['business'], $function[0]['funname']);
    		} catch(Exception $e) {

    		}
    		$transaction->commit();

    	return  true;
    }

    /**
     * 根据条件修改功能信息
     * @param 条件；数组
     * @param 修改的值；数组
     */
    public function updateFunctionNew($where, $data) {

    	$where_str = '';
    	foreach($where as $wkey=>$wval) {
    		if (isset(self::$where[$wkey])) {
	    		if ($where_str) {
	    			$where_str .= " and ". $wkey ."='". $wval ."'";
	    		} else {
	    			$where_str .= $wkey ."='". $wval ."'";
	    		}
    		}
    	}
    	if (!$where_str) {
    		return false;
    	}
    	$where_str = " where ". $where_str;

    	$set = '';
    	foreach ($data as $fkey=>$fval) {
    		if (!isset(self::$file[$fkey])) {
    			throw new Exception('请检查修改');
    		} else {
    			if ($set) {
    				$set .= " , ". $fkey ."='". $fval ."'";
    			} else {
    				$set .= $fkey ."='". $fval ."'";
    			}
    		}
    	}

    	$db = yii::app()->db_eel;
    	$sql = "update `developer_function` set ". $set . $where_str;
    	return $db->createCommand($sql)->execute();
    }

    /**
     * 添加功能
     * @param  $business 业务号
     * @param  $funname   功能名称
     * @param  $point	 权限点
     * @param  $url		URL
     * @param  $desc	描述
     */
    public function Insert($business, $funname, $point, $url, $desc='') {

    	$flag = self::getFunctionInfo($business, $funname);//self::getFunctionByItem($point);
    	$sign = ltrim($url, '/');
    	if(!empty($flag) && $flag['status'] != self::FUN_STATUS_DEL) {

    		//存在没有删除的功能，不允许重复添加
    		throw new Exception("功能已不在，不能重复添加");
    	} else{
    		if (!empty($flag) && $flag['status'] == FunctionManager::FUN_STATUS_DEL) {

    			//存在已经删除的功能；修改其功能状态
    			$functionid[0] = $flag['id'];
    			$where = array('id'=> $flag['id']);
    			$data = array(
    					'status'		=> FunctionManager::FUN_STATUS_UNCHECK,
    					'description'	=>	$desc,
    					'url'			=>	$url,
    					'sign'			=>	$sign,
    					'unix'			=>	date('Y-m-d H:m:s'),
    			);
    			$results = self::updateFunctionNew($where, $data);
    		} else {

    			$db = yii::app()->db_eel;
    			$sql = "insert into  developer_function (business, funname, description,unix, url, sign, item) values (?,?,?,?,?,?,?)";
    			$results = $db->createCommand($sql)->execute(array($business,$funname,$desc,date('Y-m-d H:m:s'), $url, $sign, $point));
    			$sql = "select id from developer_function where item = '$point' ";
    			$functionid = $db->createCommand($sql)->queryColumn();
    		}
    	}
    	return $functionid;
    }

    public function export($fname, $data) {

    	$fname = $fname . date('Ymd'). '.xls';

    	$titles = array('功能名称', '用户id', '用户名称', '用户所在部门');
    	$col = array('funname', 'userid', 'name', 'depart');

    	$comm = new CommonManager();
    	$comm->exportHtml($titles, $col, $data, $fname);

    }

    /**
     * 根据URL获取功能信息
     */
    public function getFunnameByUrl($url) {

    	if (!$url) {
    		return array();
    	}

    	$pase_url = parse_url(trim($url));
    	$path = "/".trim($pase_url['path'], '/');
    	$host = $pase_url['host'];

    	//添加缓存
    	$key = 'url_to_function';
    	$redis = new Redisdb();
    	$functions = $redis->get($key, array($path, $host));
    	if ($functions === false) {

    		if ($pase_url['host'] == 'data.meiliworks.com') {
    			$menu = DataapiManager::getDataMenu();
    			$path_arr = explode('/', trim($path,'/'));
    			$menu_id = $path_arr[3];
    			$durl = '/visual/index/'. $path_arr[5];
    			foreach ($menu as $mval) {
    				if ($mval['menu_id']==$menu_id && $mval['url']==$durl) {
    					$tmp['business'] = $mval['buiness'];
    					$tmp['funname'] = $mval['funname'];
    					$tmp['menu'] = $mval['first_menu'] .'-'. $mval['second_menu'];
    					$tmp['url'] = $url;
    					$functions[] = $tmp;
    				}
    			}
    		} else {
    			//对金币判断
    			if (substr($path, 0, 6) == '/jinbi') {
    				$path = substr($path, 6);
    			}
    			$menu = new MenuManager();
    			$bus = new BusinessManager();
    			$functions = $this->getFunctionsByUrl($path);
    			foreach ($functions as $fkey=>$fval) {

    				$business = $bus->getInfoByBusiness($fval['business']);
    				$functions[$fkey]['business'] = $business['cname'];
    				$domain = trim($business['description'], '/');
    				if(strpos($domain, "http://") === false) {
    					$domain = "http://". $domain;
    				}
    				$functions[$fkey]['url'] = $domain. $path;
    				$local_menu = $menu->getMenuByfunctionid($fval['id']);
    				if ($local_menu) {
    					foreach ($local_menu as $lval) {
    						$tmp_m = array();
    						$str = '';
    						$lval['first'] && $str .= $lval['first'];
    						$lval['second'] && $str .= '-'. $lval['second'];
    						!empty($lval['third']) && $str .= '-'. $lval['third'];
    						$tmp_m[] = $str;
    					}
    					$functions[$fkey]['menu'] = implode(';', $tmp_m);
    				}
    			}

    		}
    		$redis->set($key, array($path, $host), json_encode($functions));
    	} else {
    		$functions = json_decode($functions, true);
    	}

    	return $functions;
    }
    //获得某人管理员下的功能
    public function getFunctionOfAdmins($uid){
        $db = yii::app()->sdb_eel;
        $sql = "select distinct(daic.child) from developer_AuthItemChild daic, developer_AuthAssignment daa,developer_AuthItem dai where daa.userid={$uid} and dai.type=2 and daa.itemname=dai.name and daic.parent=dai.name";
        $results = $db->createCommand($sql)->queryAll();
        $variable = array();
        foreach ($results as $key => $value) {
           $variable []= "'".$value['child']."'";
        }
        $type_sql = "select t2.child ,t1.type from developer_AuthItem t1,developer_AuthItemChild t2 where t2.child in ( " .implode(',',$variable)." ) and t1.name=t2.child";
        $type = $db->createCommand($type_sql)->queryAll();
        $search = array();
        foreach ($type as $key => $value) {
            if($value['type']=='1'){
                $search [] =  $value['child'];
            }
        }
        return $search;
    }
}
