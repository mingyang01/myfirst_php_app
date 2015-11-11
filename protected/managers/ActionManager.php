<?php
/**
 * action管理
 * @author linglingqi
 * @version 2015-06-09
 */
class ActionManager extends Manager {

	/**
	 * 根据功能名和action获取action详情
	 */
	public function getByfidAndAction($fid, $action) {

		$db = yii::app()->db_eel;
		$sql = "select * from developer_action where functionid=$fid and action='$action'";
		return $db->createCommand($sql)->queryAll();
	}

	/**
	 * 添加action
	 */
	public function addAction($fid, $action, $username) {

		$is_exist = self::getByfidAndAction($fid, $action);
		if (!$is_exist) {

			$time = date('Y-m-d H:i:s');

			$db = yii::app()->db_eel;
			$sql = "insert into developer_action(functionid,action,unix,username) values(?,?,?,?)";
			return $db->createCommand($sql)->execute(array($fid,$action,$time,$username));
		}
		return true;
	}
}