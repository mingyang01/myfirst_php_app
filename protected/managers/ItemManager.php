<?php
/**
 * item相关的操作
 * @author linglingqi
 * @version 2015-05-19
 */
class ItemManager extends Manager {
    /**
     *
     * @param unknown $parent
     * @return unknown
     */
    public function getChileItem($parent) {
    	$db = yii::app()->sdb_eel;
    	$sql = "select * from developer_AuthItemChild where parent='". $parent ."'";
    	$itemname = $db->createCommand($sql)->queryAll();
    	return  $itemname;
    }

    /**
     * 根据name获取权限点
     */
    public static function getItemByName($name) {

    	$db = yii::app()->sdb_eel;
    	$sql = "select id, name from developer_AuthItem where name='". $name ."'";
    	$itemname = $db->createCommand($sql)->queryRow();
    	return  $itemname['id'];
    }

    /**
     * 根据ID获取权限点
     */
    public static function getItemById($id) {

    	$db = yii::app()->sdb_eel;
    	$sql = "select id, name from developer_AuthItem where id='". $id ."'";
    	$itemname = $db->createCommand($sql)->queryRow();
    	return  $itemname['name'];
    }

    /**
     * 获取角色有权限的功能
     */
    public static function getRolesProject($role) {

    	$auth = new AuthManager();
    	$bus = new BusinessManager();
		$functions = $auth->getDepartFunctions($role);
		foreach($functions as $val) {
			$arr = explode('/', $val);
			$bid = $arr[0];
			$business = $bus->getInfoByBusiness($bid);
			if (!$business) {
				continue;
			}
			$project[$bid] = $business;
		}
		return $project;
    }

    /**
     * 获取权限的所有父权限
     */
    private function _getParents($item) {

    	$db = yii::app()->sdb_eel;
    	$sql = "select parent from developer_AuthItemChild where child='". $item ."'";
    	$itemname = $db->createCommand($sql)->queryColumn();
		return $itemname;
    }

    public static function getAllParents($item, &$allParent) {

		$parents = self::_getParents($item);
		if ($parents) {
			foreach ($parents as $val) {
				$allParent[$val] = $val;
				self::getAllParents($val, $allParent);
			}
		}
    }
}
