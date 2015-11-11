<?php
/**
 * 角色相关的操作
 * @author linglingqi
 * @version 2015-05-19
 */
class RoleManager extends Manager {
    public function departBusiness($depart) {

    	$db = Yii::app()->sdb_eel;
    	$sql = "select a.business, b.cname, a.funname,a.item from developer_function a, developer_business b where a.business=b.business and a.item  in (select developer_AuthItemChild.child
            from developer_AuthItemChild where developer_AuthItemChild.parent='".$depart."')";

    	$results = $db->createCommand($sql)->queryAll();
    	return $results;
    }
}
