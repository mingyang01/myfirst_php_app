<?php
class WorkManager extends Manager {
    public function getCollect($id,$business)
    {
    	$db = yii::app()->db_eel;
    	$sql = "select * from developer_collect_menu where userid='$id' and business = '$business' ";
    	$variable = $db->createCommand($sql)->queryAll();
    	$results = array();
    	foreach ($variable as $key => $value) {
    		$results[$value['business']] []= $value['itemName'];
    	}
    	return $results;
    }
}