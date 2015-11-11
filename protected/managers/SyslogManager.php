<?php
/**
 * 系统操作日志
 * @author linglingqi@meilishuo.com
 * @version 2015-06-11
 */
class SyslogManager extends Manager {

	const LOG_ALL_TYPE = 0;  //全部日志
	const LOG_APPLY = 1;
	const LOG_LEADER_ALLOW = 2;
	const LOG_ADMIN_ALLOW = 3;
	const LOG_SUPER_ALLOW = 4;
	const LOG_REJECT = 5;
	const LOG_ADD_ROLE_USER = 6;
	const LOG_DEL_ROLE_USER = 7;
	const LOG_ROLE_ADD = 8;
	const LOG_ROLE_DEL = 9;
	const LOG_ROLE_UPD = 10;
	const ROLE_ASSIGN = 11;
	const ROLE_REVOKE = 12;
	const ROLE_FUNCTION_ADD = 13;
	const ROLE_FUNCTION_DEL = 14;
	const FUNCTION_ASSIGN_USER = 15;
	const FUNCTION_REVOKE_USER = 16;
	const FUNCTION_CHECK = 17;
	const FUNCTION_UPDATA = 18;
	const FUNCTION_DEL = 19;
	const MENU_DEL = 20;

	/**
	 * 获取日志类型
	 */
	public static function getType() {
		return array(
			self::ROLE_FUNCTION_ADD	=>	'角色分配功能权限',
			self::ROLE_FUNCTION_DEL	=>	'角色收回功能权限',
			self::ROLE_ASSIGN		=>	'角色分配',
			self::ROLE_REVOKE		=>	'角色收回',
			self::LOG_ALL_TYPE		=>	'全部',
			self::LOG_APPLY			=>	'权限申请',
			self::LOG_ADMIN_ALLOW	=>	'权限管理员审批',
			self::LOG_LEADER_ALLOW	=>	'权限直接领导审理',
			self::LOG_SUPER_ALLOW	=>	'权限超级管理审批',
			self::LOG_REJECT		=>	'拒绝申请',
			self::LOG_ADD_ROLE_USER	=> 	'添加角色成员',
			self::LOG_DEL_ROLE_USER	=>	'删除角色成员',
			self::LOG_ROLE_ADD		=>	'添加角色',
			self::LOG_ROLE_DEL		=>	'删除角色',
			self::LOG_ROLE_UPD		=>	'修改角色',
			self::FUNCTION_ASSIGN_USER=>'给用户分配功能权限',
			self::FUNCTION_REVOKE_USER=>'收回用户的功能权限',
			self::FUNCTION_CHECK		=> '审核',
			self::FUNCTION_UPDATA	=> '功能修改',
			self::FUNCTION_DEL		=>	'功能删除',
			self::MENU_DEL			=>	'菜单删除',
		);
	}

	/**
	 * 写日志
	 * @param current_user 操作人
	 * @param type  操作类别
	 * @param operate 被进行操作的对象，有人、功能和角色
	 * @param optfunction  操作的功能
	 */
	public static function Write($current_user, $type, $operate='', $optfunction='') {

		$db = Yii::app()->db_eel;
		$sql = "insert into developer_log (user,type,optobject,optfunname, time) values(?,?,?,?,?)";
		$ret = $db->createCommand($sql)->execute(array($current_user,$type,$operate,$optfunction,time()));
		return $ret;
	}

	/**
	 * 根据条件查询日志
	 */
	public static function getlog($user='', $type=self::LOG_ALL_TYPE, $optuser='', $optfunction='', $start='', $end='', $offset=0, $limit=1000) {

		if ($user || $type != self::LOG_ALL_TYPE || $optuser || $optfunction || $start || $end) {
			$where = " where 1=1 ";
			if ($user) {
				$where .= " and user='$user'";
			}
			if ($type != self::LOG_ALL_TYPE) {
				$where .= " and type=$type";
			}
			if ($optuser) {
				$where .= " and optobject='$optuser'";
			}
			if ($optfunction) {
				$where .= " and optfunname LIKE '%$optfunction%'";
			}
			if ($start) {
				$start = strtotime($start);
				$where .= " and time>='$start'";
			}
			if ($end) {
				$end = strtotime($end);
				$where .= " and time<='$end'";
			}
		}
		$must = " order by time desc limit $offset, $limit";
		$db = Yii::app()->sdb_eel;
		if ($where) {
			$sql = "select * from developer_log". $where. $must;
		} else {
			$sql = "select * from developer_log $must";
		}

		return $db->createCommand($sql)->queryAll();
	}

	public static function getLogCount($user='', $type=self::LOG_ALL_TYPE, $optuser='', $optfunction='', $start='', $end='') {
		if ($user || $type != self::LOG_ALL_TYPE || $optuser || $optfunction || $start || $end) {
			$where = " where 1=1 ";
			if ($user) {
				$where .= " and user='$user'";
			}
			if ($type != self::LOG_ALL_TYPE) {
				$where .= " and type=$type";
			}
			if ($optuser) {
				$where .= " and optobject='$optuser'";
			}
			if ($optfunction) {
				$where .= " and optfunname LIKE '%$optfunction%'";
			}
			if ($start) {
				$start = strtotime($start);
				$where .= " and time>='$start'";
			}
			if ($end) {
				$end = strtotime($end);
				$where .= " and time<='$end'";
			}
		}

		$db = Yii::app()->sdb_eel;
		$sql = "select count(*) from developer_log ";
		$where && $sql .= $where;
		$total = $db->createCommand($sql)->queryColumn();
		return $total[0];
	}

}