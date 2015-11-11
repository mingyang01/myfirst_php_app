<?php
class MenuTreeManager extends Manager
{
	public function getMenuTree($business = '' ,$uid = '')
	{
		if($business) {
            $where .= " where a.business='$business'";
        }

        $whole = array();
        $db = Yii::app()->db_eel;
        $results = array();
        $sql = "select b.work, b.business ,a.id, b.item point , b.url, first, second, third
        from developer_menu a left join(
select b.`cname` business , b.`business` work ,a.id id,a.url url, a.item
from developer_function a, developer_business b where a.business = b.business) b
on a.function = b.id" . $where;
        $items = $db->createCommand($sql)->queryAll();
        foreach ($items as $key=>$val) {
        	$items[$key]['itemid'] = ItemManager::getItemByName($val['point']);
        }

        $whole = self::formateMenuTree($business, $uid, $items);
        return $whole;
	}

	/**
	 * data平台的菜单获取
	 */
	public function getDataMenuTree($uid) {

		$business = "data平台";

		try {
			$fun = new FunctionManager();
			$menu = DataapiManager::getDataMenu();
			$items = array();
			foreach ($menu as $val) {
				$tmp['point'] = $fun->getFunctionItem($val['buiness'], $val['funname']);
				if (empty($tmp['point'])) {
					continue;
				}
				$tmp['first'] = $val['first_menu'];
				$tmp['second'] = $val['second_menu'];
				$tmp['url'] = $val['url'];
				$tmp["third"] = isset($val['funname']) ? $val['funname'] : '';
				$tmp["work"] = $val['buiness'];
				$tmp['itemid'] = ItemManager::getItemByName($tmp['point']);
				$items[] = $tmp;
			}
			return self::formateMenuTree($business, $uid, $items);
		} catch(Exception $e) {
			return array();
		}
	}

	/**
	 * 格式话菜单展现
	 */
	private function formateMenuTree($business, $uid, $items) {

		if($uid) {
			$haveItem = $this->auth->getAuthFunction($uid);
			$developerFlag = $this->function->isDeveloperByid($business,$uid);
			$superFlag = $this->auth->isSupper($uid);
		}

		$status = '';
		$results = $whole = array();
		foreach ($items as $key=>$item) {
			if ($uid) {
				$status = in_array($item["point"], $haveItem)||$developerFlag||$superFlag;
			}
			if ($item["second"]) {
				$s_key = $item["first"] .'/'. $item["second"];
			} else {
				$s_key = $item["first"];
			}

			if(!isset($results[$s_key])) {

				$results[$s_key] = array();
				$results[$s_key]['itemid'] = $item['itemid'];
				$results[$s_key]["name"] = $item["second"];
				$results[$s_key]["url"] = $item["url"];
				$results[$s_key]["point"] = $item["point"];
				$results[$s_key]["status"] = $status;
				$results[$s_key]["child"] = array();
				$results[$s_key]["business"] = $item["work"];
			}

			if (trim($item["third"])) {
				$results[$s_key]["child"][] = array('itemid'=>$item['itemid'], "name"=>$item["third"],"status"=>$status, "url"=>$item["url"],"point"=>$item['point'], "child"=>array(),"business"=>$item["work"]);
				if (count($results[$s_key]["child"]) > 1) {
					$results[$s_key]["url"] = '#';
					unset($items[$key]);
				}
			}
		}
		foreach ($items as $key => $value) {

			if ($item["second"]) {
				$m_key = $value["first"] .'/'. $value["second"];
			} else {
				$m_key = $value["first"];
			}

			if(!isset($whole[$value["first"]])) {
				if ($uid) {
					$status = in_array($value["point"], $haveItem)||$developerFlag||$superFlag;
				}
				$m_key = $value["first"] .'/'. $value["second"];
				$whole[$value["first"]] = array();
				$whole[$value["first"]]['itemid'] = $value["itemid"];
				$whole[$value["first"]]["name"] = $value["first"];
				$whole[$value["first"]]["url"] = $value["url"];
				$results[$m_key]["status"] = $status;
				$whole[$value["first"]]["point"] = $value["point"];
				$whole[$value["first"]]["child"] = array();
				$whole[$value["first"]]["business"] = $value["work"];
			}
			if (trim($value["second"])) {
				$whole[$value["first"]]["child"][] = $results[$m_key];
			}
		}//var_dump($whole);exit;
		return $whole;
	}

	/**
	 * 获取角色对应的权限树
	 */
	public function getRoleTree($business, $role) {

		//先获取业务下的所有的功能；再获取roles对应的的有权限的items，然后求交集；然后格式话返回
		$auth = new AuthManager();
		$has_auth = array();
		$auth->getItemChild(array($role=>1), $has_auth);

		$bitems = array();
		if ($business == "data平台") {
			$fun = new FunctionManager();
			$menu = DataapiManager::getDataMenu();
			foreach ($menu as $val) {
				$tmp['item'] = $fun->getFunctionItem($val['buiness'], $val['funname']);
				if (empty($tmp['item'])) {
					continue;
				}
				$tmp['first'] = $val['first_menu'];
				$tmp['second'] = $val['second_menu'];
				$tmp['url'] = $val['url'];
				$tmp["third"] = isset($val['funname']) ? $val['funname'] : '';
				$bitems[] = $tmp;
			}

		} else {
			$db = yii::app()->db_eel;
			$sql = "select a.business, a.first, a.second, a.third, b.item from developer_menu a, developer_function b where a.function=b.id and a.business='$business'";
			$bitems = $db->createCommand($sql)->queryAll();
		}

		$menus = array();
		$item = new ItemManager();
		foreach($bitems as $key=>$bval) {
			$t_item = $bval['item'];
			if (in_array($t_item, $has_auth) || $auth->whitelistRule($t_item)) {
				$menus[$key] = $bval;
				$menus[$key]['itemid'] = $item->getItemByName($t_item);
				$menus[$key]["status"] = 1;
			}
		}

		return self::formateRoleMenuTree($menus);
	}

	/**
	 * 格式话针对角色的菜单展现
	 */
	private function formateRoleMenuTree($items) {

		$status = '';
		foreach ($items as $key=>$item) {
			if(!isset($results[$item["second"]])) {

				$results[$item["second"]] = array();
				$results[$item["second"]]['itemid'] = $item['itemid'];
				$results[$item["second"]]["name"] = $item["second"];
				$results[$item["second"]]["point"] = $item["item"];
				$results[$item["second"]]["status"] = $item['status'];
				$results[$item["second"]]["child"] = array();
				$results[$item["second"]]["business"] = $item["business"];
			}

			if (trim($item["third"])) {
				$results[$item["second"]]["child"][] = array('itemid'=>$item['itemid'], "name"=>$item["third"],"status"=>$item['status'],"point"=>$item['item'], "child"=>array(),"business"=>$item["work"]);
			}
		}
		foreach ($items as $key => $value) {
				$whole[$value["first"]] = array();
				$whole[$value["first"]]['itemid'] = $value["itemid"];
				$whole[$value["first"]]["name"] = $value["first"];
				$whole[$value["first"]]["url"] = $value["url"];
				$results[$item["second"]]["status"] = $item['status'];
				$whole[$value["first"]]["point"] = $value["item"];
				$whole[$value["first"]]["child"] = array();
				$whole[$value["first"]]["business"] = $value["work"];
			if (trim($value["second"])) {
				$whole[$value["first"]]["child"][] = $results[$value["second"]];
			}
		}
		return $whole;
	}
}