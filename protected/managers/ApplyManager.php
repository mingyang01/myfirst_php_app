<?php
class ApplyManager extends Manager {


	//功能相关
	const FUNCTION_UNCHECK = 1;    //已提交审核
	const FUNCTION_CHECK = 2;		//审核通过
	const FUNCTION_NOCHECK = 0;		//添加功能，还没有送审

	const FUNCTION_SHARE = 1;
	const FUNCTION_NOMAL = 0;
	const FUNCTION_SPECIAL = 2;

	//用户权限相关
	const USER_ALL = 5;		//全部
	const USER_FIRST_CHECK = 0;   //待审核
	const USER_SECOND_NOMAL = 1;	//管理员对普通功能审核通过
	const USER_SECOND_SPE = 2;		//管理员对特殊功能审核通过
	const USER_PASS = 3;		//审核完整通过
	const USER_REFUSE = 6; 		//拒绝申请
	const USER_NO_ACESS = 4;   	//没有权限

	/**
	 * 批量审核通过
	 */
	public function dealApply($applyid) {

		$db = yii::app()->db_eel;
		$auth = yii::app()->authManager;
		$user = yii::app()->user;
		$sid = $user->id;

		// 处理人相关信息
		$current_usermail = $user->username;
		$current_username  = $user->name;
		//$depart = $user->depart;

		$sql ="select item,applyid,applicant from developer_Apply where id='".$applyid."'";
		$results = $db->createCommand($sql)->queryAll();
		// 申请人id
		$nameid = $results[0]['applicant'];
		$item = $results[0]['item'];
		$applicantName = NewCommonManager::getUserRealnameByuid($nameid);
		$applicantEmail = NewCommonManager::getUsernameByuid($nameid);
		$depart = NewCommonManager::getUserDepartByName($applicantEmail);

		$apply = new ApplyManager();
		if($auth->checkAccess('super',$sid)) {
			// 如果是管理员直接 进度->3
			$sql = "update developer_Apply set status=?, riskid=? where id=?";
			$db->createCommand($sql)->execute(array(3, $sid, $applyid));
			if(!$auth->checkAccess($item,$nameid)) {
				$auth->assign($item, $nameid);
			}
			SyslogManager::Write($current_usermail, SyslogManager::LOG_SUPER_ALLOW, $applicantEmail,$item);
			$this->mail->sendMail($applicantEmail,$item,'success');
		} elseif($auth->checkAccess($depart,$sid)) {

			$sql = "update developer_Apply set status=?, adminid=? where id=?";
			$db->createCommand($sql)->execute(array(3, $sid, $applyid));
			if(!$auth->checkAccess($item,$nameid)) {
				$auth->assign($item, $nameid);
			}
			SyslogManager::Write($current_usermail, SyslogManager::LOG_ADMIN_ALLOW, $applicantEmail, $item);
			$this->mail->sendMail($applicantEmail,$item,'success');
		} else {
			// 直属上级
			$lauth = new AuthManager();

			// 获取部门管理员
			$admins = $lauth->getDepartManage($depart);
			$admin = array();
			foreach ($admins as $val) {
				$tmp = NewCommonManager::getUsernameByuid($val);
				$tmp && $admin[] = $tmp;
			}
			$to = $lauth->delLeader($admin);

			$sql = "update developer_Apply set status=?, leader=? where id=?";
			$db->createCommand($sql)->execute(array(1, $sid, $applyid));
			//log
			SyslogManager::Write($current_usermail, SyslogManager::LOG_LEADER_ALLOW, $applicantEmail, $item);
			$this->mail->sendMail($to,$applicantName."，领导{$current_username}已通过审核",'group');
		}

		// 发送邮件给审批人
		$manage_content = "申请人：".$applicantName .",功能：". $item;
		$this->mail->sendMail($current_usermail, $manage_content, 'manager');
		return true;
	}

	/**
	 * 修改申请表的状态
	 */
	public function updateApply($id, $status, $adminid='') {

		if (!$id) {
			return false;
		}
		$db = yii::app()->db_eel;
		$sql = "update developer_Apply set status=?, adminid=? where id=?";
		return $db->createCommand($sql)->execute(array($status, $adminid, $id));
	}
}