
<?php
class MenuManager extends Manager
{
    /**
     * [getMenu description]
     * @param  [type]
     * @return [type]
     */
    public static function getMenu($business, $uid, $domain=true) {
        $where = '';
        $url = "CONCAT('http://' , b.description, a.url) url";
        if($business) {
            $where .= " where a.business='$business'";
        }

        if(!$domain || $domain == 'false')
            $url = " a.url url";
        $whole = array();
        $db = Yii::app()->db_eel;
        $results = array();
        $sql = "select b.business , b.item point , b.url, first, second, third
        from developer_menu a left join(
select b.`cname` business , a.id id, $url, a.item
from developer_function a, developer_business b where a.business = b.business) b
on a.function = b.id" . $where;

        $items = $db->createCommand($sql)->queryAll();

        $auth = new AuthManager;
        foreach ($items as $key=>$item) {

            if(!$auth->checkAccess($item['point'],$uid,'true')) {
                 unset($items[$key]);
                 continue;
            }

            if(!isset($results[$item["second"]])) {
                $results[$item["second"]] = array();
                $results[$item["second"]]["name"] = $item["second"];
                $results[$item["second"]]["url"] = $item["url"];
                $results[$item["second"]]["child"] = array();
                $results[$item["second"]]["business"] = $item["business"];
            }

            if (trim($item["third"])) {
                $results[$item["second"]]["child"][] = array("name"=>$item["third"], "url"=>$item["url"], "child"=>array());
                if (count($results[$item["second"]]["child"]) > 1) {
                    $results[$item["second"]]["url"] = '#';
                    unset($items[$key]);
                }
            }
        }

        foreach ($items as $key => $value) {
            if(!isset($whole[$value["first"]])) {
                $whole[$value["first"]] = array();
                $whole[$value["first"]]["name"] = $value["first"];
                $whole[$value["first"]]["url"] = $value["url"];
                $whole[$value["first"]]["child"] = array();
                $whole[$value["first"]]["business"] = $value["business"];
            }

            if (trim($value["second"])) {
                $whole[$value["first"]]["child"][] = $results[$value["second"]];
            }
        }
       // var_dump($whole);

        return array_values($whole);
    }

    public static function getTest($business, $uid, $domain=true,$status=true) {
        $where = '';
        $url = "CONCAT('http://' , b.description, a.url) url";
        if($business) {
            $where .= " where a.business='$business'";
        }

        if(!$domain || $domain == 'false')
            $url = " a.url url";
        $whole = array();
        $db = Yii::app()->db_eel;
        $results = array();
        $sql = "select b.business ,a.id, b.item point , b.url, first, second, third
        from developer_menu a left join(
select b.`cname` business , a.id id, $url, a.item
from developer_function a, developer_business b where a.business = b.business) b
on a.function = b.id" . $where;
        $items = $db->createCommand($sql)->queryAll();
        $have = array();
        $auth = new AuthManager();
        $redis = new Redisdb();
        $rekey = "Menu";

        if(!$status || $status == 'false') {
            if ($redis->exists($rekey, array($business, $uid))) {
            	return json_decode($redis->get($rekey, array($business, $uid)),true);
            }
        }

        foreach ($items as $key=>$item) {
                if(!$auth->checkAccess($item['point'],$uid)) {
                $have[$item['id']] = $item['id'];
                unset($items[$key]);
                continue;
            }

            if(!isset($results[$item["second"]])) {
                    $results[$item["second"]] = array();
                    $results[$item["second"]]["name"] = $item["second"];
                    $results[$item["second"]]["url"] = $item["url"];
                    $results[$item["second"]]["child"] = array();
                    $results[$item["second"]]["business"] = $item["business"];
                }


                if (trim($item["third"])) {
                    $results[$item["second"]]["child"][] = array("name"=>$item["third"], "url"=>$item["url"], "child"=>array());
                    if (count($results[$item["second"]]["child"]) > 1) {
                        $results[$item["second"]]["url"] = '#';
                        unset($items[$key]);
                    }
                }

        }

        foreach ($items as $key => $value) {
            if(!isset($whole[$value["first"]])) {
                $whole[$value["first"]] = array();
                $whole[$value["first"]]["name"] = $value["first"];
                $whole[$value["first"]]["url"] = $value["url"];
                $whole[$value["first"]]["child"] = array();
                $whole[$value["first"]]["business"] = $value["business"];
            }

            if (trim($value["second"])) {
                $whole[$value["first"]]["child"][] = $results[$value["second"]];
            }
        }
        $redis->set($rekey, array($business, $uid), json_encode($whole));
        return array_values($whole);
    }

    public  function getMenuTest($business, $uid, $domain=true,$status=true,$cbusiness='global') {

        $where = '';
        $url = "CONCAT('http://' , b.description, a.url) url";
        if($business) {
            $where .= " where a.business='$business'";
        }

        if(!$domain || $domain == 'false')
            $url = " a.url url";
        $whole = array();
        $db = Yii::app()->db_eel;
        $results = array();
        $sql = "select b.work, b.business ,a.id, b.item point , b.url, first, second, third
        from developer_menu a left join(
select b.`cname` business , b.`business` work ,a.id id, $url, a.item
from developer_function a, developer_business b where a.business = b.business) b
on a.function = b.id" . $where." order by rank desc";
        $items = $db->createCommand($sql)->queryAll();

        $function = new FunctionManager();
        $businesses = $is_developer = array();
        if ($business == 'global') {
        	foreach ($items as $val) {
        		$businesses[$val['work']] = $val['work'];
        		$is_developer[$val['work']] = $function->isDeveloperByid($val['work'], $uid);
        	}
        }

        $have = array();
        $auth = new AuthManager();
        $col = new CollectManager();
        //$remove = new RemoveAuthManager();
        $haveItem = $auth->getNewAuthFunction($uid);
        $developerFlag = $function->isDeveloperByid($business,$uid);
        $superFlag = $auth->isSupper($uid);
        $collect = $col->collet($uid,$domain, $cbusiness);
        if ($business == 'global') {
        	$white_lists = $auth->whitelists();
        } else {
        	$white_lists = $auth->whitelists($business);
        }

        foreach ($items as $key=>$item) {
        	$mkey = $item["first"] .'/'. $item["second"];
            // $removeAuth = $remove->getRemoveAuth($uid, $item['point']);
            // if($removeAuth){
            //     unset($items[$key]);
            //     continue;
            // }
        	$is_strick = $auth->strickFunction($item['point']);
        	if((!$superFlag && !$developerFlag && !isset($white_lists[$item['point']])) || $is_strick) {
        		if ($business == 'global' && $is_developer[$item['work']]) {
        			if(!isset($results[$mkey])) {
        				$results[$mkey] = array();
        				$results[$mkey]["name"] = $item["second"];
        				$results[$mkey]["url"] = $item["url"];
        				$results[$mkey]["child"] = array();
        				$results[$mkey]["business"] = $item["work"];
                        $results[$mkey]["id"] = $item["id"];
        			}

        			if (trim($item["third"])) {
        				$results[$mkey]["child"][] = array("id"=>$item["id"],"name"=>$item["third"], "url"=>$item["url"], "child"=>array(),"business"=>$item["work"]);
        				if (count($results[$mkey]["child"]) > 1) {
        					$results[$mkey]["url"] = '#';
        					unset($items[$key]);
        				}
        			}
        			continue;
        		} else {
        			if(!in_array($item['point'], $haveItem)) {
        				unset($items[$key]);
        				continue;
        			}
        		}
        	}

        	if(!isset($results[$mkey])) {
        		$results[$mkey] = array();
        		$results[$mkey]["name"] = $item["second"];
        		$results[$mkey]["url"] = $item["url"];
        		$results[$mkey]["child"] = array();
        		$results[$mkey]["business"] = $item["work"];
                $results[$mkey]["id"] = $item["id"];
        	}


        	if (trim($item["third"])) {
        		$results[$mkey]["child"][] = array("id"=>$item["id"],"name"=>$item["third"], "url"=>$item["url"], "child"=>array(),"business"=>$item["work"]);
        		if (count($results[$mkey]["child"]) > 1) {
        			$results[$mkey]["url"] = '#';
        			unset($items[$key]);
        		}
        	}

        }


        foreach ($items as $key => $value) {
            if(!isset($whole[$value["first"]])) {
                $whole[$value["first"]] = array();
                $whole[$value["first"]]["name"] = $value["first"];
                $whole[$value["first"]]["url"] = $value["url"];
                $whole[$value["first"]]["child"] = array();
                $whole[$value["first"]]["business"] = $value["work"];
                $whole[$value["first"]]["id"] = $value["id"];
            }

            if (trim($value["second"])) {
            	$vkey = $value["first"] .'/'. $value["second"];
                $whole[$value["first"]]["child"][] = $results[$vkey];
            }
        }
        $whole['收藏夹']["name"] = "收藏夹";
        $whole['收藏夹']["url"] = "#";
        $whole['收藏夹']["child"] = array();
        $whole['收藏夹']["business"] = $business;
        $length = count($collect);
        if($length==0){
            $whole['收藏夹']["child"] [] = array('name'=>'暂无收藏','url'=>"#",'child'=>array());
        }
        foreach ($collect as $key => $value) {
             $whole['收藏夹']["child"] [] = $value;
        }
        array_unshift($whole, array_pop($whole));
        //菜单的log
        $ip = Yii::app()->request->userHostAddress;
        $logmsg = "\t\n[".date('Y-m-d H:i:s')."][{$ip}] renturn menu:-count--".count($whole)."-menu:--".json_encode($whole)."\t\n";
        $date = date('Y-m-d');
        if($uid==2139){
            file_put_contents("/home/work/websites/developer/protected/runtime/menu-{$date}.log",$logmsg,FILE_APPEND);
        }
        return array_values($whole);
    }

    /**
     * developer权限管理，对“角色管理，权限分配”两个功能做特殊处理，实现是管理员就有该角色
     */
    public function developerMenu($uid, $domain=true) {

    	$user = Yii::app()->user;
    	$business = "developer";
    	$special_fun_id = "58,60,134";
    	$auth = new AuthManager();
    	$roles = $auth->userRoles($user->id);
    	$whole = self::getMenuTest($business, $uid, $domain);
    	$helper =  new HelperManager();
    	$whole = $helper->hashmap($whole, 'name');
    	$default = array();
    	if ($roles) {
	    	$db = Yii::app()->db_eel;
	    	$sql = "select  b.business ,b.id, b.item point , b.url, first, second, third from developer_menu a, developer_function b where a.function=b.id and b.id in ($special_fun_id)";
	    	$default = $db->createCommand($sql)->queryAll();
	    	foreach ($default as $key=>$item) {
	    		if(!isset($whole[$item["first"]])) {
	    			$whole[$item["first"]] = array();
	                $whole[$item["first"]]["name"] = $item["first"];
	                $whole[$item["first"]]["url"] = $item["url"];
	                $whole[$item["first"]]["child"] = array();
	                $whole[$item["first"]]["business"] = $item["business"];
	    		}
	    		$whole[$item["first"]]["child"] = $helper->hashmap($whole[$item["first"]]["child"], 'name');
	    		if(!isset($whole[$item["first"]]["child"][$item['second']])) {
	    			$whole[$item["first"]]["child"][$item['second']] = $tmp = array();
	    			$tmp["name"] = $item["second"];
	    			$tmp["url"] = $item["url"];
	    			$tmp["child"] = array();
	    			$tmp["business"] = $item["business"];
	    			$whole[$item["first"]]["child"][$item['second']] = $tmp;
	    		}
	    	}
    	}
    	return $whole;
    }

    /**
     * 根据功能ID删除菜单
     */
    public function deleteMenu($fid) {

    	$db = Yii::app()->db_eel;

    	$sql = "delete from developer_menu where function=?";
    	$ret =  $db->createCommand($sql)->execute(array($fid));

    	$fun = new FunctionManager();
    	$fun_info = $fun->getFunctionInfoById($fid);

    	return $ret;
    }

    /**
     * 根据功能ID获取功能信息
     */
    public function getMenuByfunctionid($fid) {

    	$db = Yii::app()->sdb_eel;
    	$sql = "select * from developer_menu where function=$fid";
    	return $db->createCommand($sql)->queryAll();
    }

}
