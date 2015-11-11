<?php
/**
* 运营人员操作相关
* @author mingyang@meilishuo.com
* @since 2015-11-5
*/

class OperationManager extends Manager {

	public function getRolesInDepart($depart){
		$sql = " select t1.parent,t1.child from developer_AuthItemChild t1 , developer_AuthItem t2 where t2.name=t1.child and t2.type=2 and t1.parent ='{$depart}' ";
		$db = yii::app()->sdb_eel;
		$results = $db -> createCommand($sql)->queryAll();
		return $results;
	}

	public function getExistDepart(){
		$sql = " select t1.name from developer_AuthItem t1,developer_AuthItemChild t2 where t1.type=2 and t2.parent = t1.name and t1.name not in (select child from developer_AuthItemChild group by child) group by t1.name";
		$db = yii::app()->sdb_eel;
		$results = $db -> createCommand($sql)->queryColumn();
		$super = array('super');
		$results = array_diff($results,$super);
		return $results;
	}

	public function exportDepartExcel($list, $title){

        $titles = array('部门','角色');
        $columns = array('parent','child');
        $title = $title."_".date("Y-m-d H:i:s").".xls";
        CommonManager::exportHtml($titles, $columns, $list, $title);
    }

}