<?php

class ApplyController extends Controller {

	function checkStatus()
	{
		$findstatus = array();
		$db      = yii::app()->sdb_eel;
		$sid     = $this->auth->getId(yii::app()->user->username);
		$sql     = "select applyid,status from developer_Apply where applicant =".$sid;
		$funlist = $db->createCommand($sql)->queryAll();
		foreach ($funlist as $key => $value) {
			$findstatus[$value['applyid']]= $value['status'];
		}
		return $findstatus;
	}


	public function actionAddApply() {

		$rows = $_POST['rows'];
		$item     = "";
		$type     = "";
		$user = yii::app()->user;
		$username = $user->username;
		$applicantName     = $user->name;
		$leader   = NewCommonManager::getUserLeader($user->id);
		$sid      = $user->id;
		$departid = NewCommonManager::getUserDepartId($sid);
		$db       = yii::app()->db_eel;
		$leadername = NewCommonManager::getUsernameByuid($leader);

		$sucess = $fail = array();
		foreach ($rows as $key => $value) {
			$sql      = "select status from developer_Apply where applyid=".$value['id']." and applicant=".$sid;
			$items    = $db->createCommand($sql)->queryAll();
			if(!empty($items)) {
				$sql = "update developer_Apply set status= ? where applyid =? and applicant=?";
				$db->createCommand($sql)->execute(array(0,$value['id'],$sid));
				SyslogManager::Write($username, SyslogManager::LOG_APPLY, $username, $value['item']);
				$sucess[] = $value['funname'];
				continue;
			}

			if($value['id']) {
				$sql = "select item,type from developer_function where id=".$value['id'];
				$item = $db->createCommand($sql)->queryAll();
				$type = $item[0]['type'];
				$item = $item[0]['item'];
			}
			try {
				$db = yii::app()->db_eel;
				$sql = "insert into developer_Apply (type,item,status,applicant,leader,applyid,unix,departid,adminid,riskid,message) values(?,?,?,?,?,?,?,?,?,?,?)";
				$db->createCommand($sql)->execute(array($type,$item,0,$sid,$leader,$value['id'],date('Y-m-d H:m:s'),$departid,0,0,""));
				SyslogManager::Write($username, SyslogManager::LOG_APPLY, $username, $value['item']);
			} catch (Exception $e) {
				$fail[] = $value['funname'];
			}
			$sucess[] = $value['funname'];
		}
		$this->mail->sendMail($leadername,$applicantName,'apply');
		if ($fail) {
			$str = implode(',', $fail)." 申请失败，申请成功的有:". implode(',', $sucess);
		} else{
			$str = implode(',', $sucess)." 申请成功,请到'我的申请'中查看申请状态";
		}

		echo json_encode($str);
	}

	public function actionMyApply() {

		$db = yii::app()->db_eel;
		$data = array();
		$user = yii::app()->user;
		$sid = $user->id;
		$depart = NewCommonManager::getUserDepart($sid);

		$sql = "select a.message, b.item, a.applicant,b.funname,c.cname,a.type,a.status,b.business,a.unix, a.adminid, a.riskid, a.leader
		from developer_Apply a, developer_function b, developer_business c
		where a.applyid=b.id and c.business=b.business and a.applicant=".$sid;
		$results = $db->createCommand($sql)->queryAll();

		$apply = new ApplyManager();
		$auth = new AuthManager();
		$admins = $auth->getDepartManage($depart);
		$admin = array();
		foreach ($admins as $val) {
			$tmp = NewCommonManager::getUserRealnameByuid($val);
			$tmp && $admin[] = $tmp;
		}

		foreach ($results as $key => $value) {
			if ($value['status'] == $apply::USER_PASS || $this->auth->checkAccess($value['item'],$sid)) {
				$results[$key]['status'] = 100;
				if ($value['riskid']){
					$opt = NewCommonManager::getUserRealnameByuid($value['riskid']);
					$results[$key]['text'] = "通过，操作人为风控：".$opt;
				} elseif ($value['adminid'] == $value['leader']) {
					$opt = NewCommonManager::getUserRealnameByuid($value['adminid']);
					$results[$key]['text'] = "通过，操作人管理员：".$opt;
				} else {

					$leader = NewCommonManager::getUserRealnameByuid($value['leader']);
					$results[$key]['text'] = "通过，操作领导为：". $leader;
					if ($value['adminid']) {
						$opt = NewCommonManager::getUserRealnameByuid($value['adminid']);
						$results[$key]['text'] .= "操作人管理员：".$opt;
					}
				}
			} elseif ($value['status'] ==  $apply::USER_REFUSE) {
				$results[$key]['status'] = 101;
				if ($value['adminid']){
					$opt = NewCommonManager::getUserRealnameByuid($value['adminid']);
					$results[$key]['text'] = "操作用户：".$opt;
				}
			}elseif ($value['status'] ==$apply::USER_SECOND_NOMAL  || $value['status'] == $apply::USER_SECOND_SPE) {
				$results[$key]['status'] = 50;
				$opt = NewCommonManager::getUserRealnameByuid($value['leader']);
				$results[$key]['text'] = $opt ." 已通过,等待通过管理员：".implode(',', $admin). " 审批";
			} else{
				$results[$key]['status'] = 0;
				if ($value['leader']) {
					$opt = NewCommonManager::getUserRealnameByuid($value['leader']);
					$results[$key]['text'] = "等待领导审批，领导：".$opt;
				}
			}
		}
		$this->render("apply/myapply.tpl",array("data"=>$results));
	}

	public function actionApplyCheck() {

		$condition = isset($_GET['condition'])?$_GET['condition']: '';
		$applyUser = isset($_GET['applyUser'])?$_GET['applyUser']: '';
		$applyItem = isset($_GET['applyItem'])?$_GET['applyItem']: '';
		if(!empty($condition)){
			$conditionItem = explode('_', $condition);
			$orderCondition = " order by {$conditionItem[0]} {$conditionItem[1]}";
		}else{
			$orderCondition = " order by applicant asc";
		}
		$where = "";
		if(!empty($applyUser)){
			$applicant = $this->auth->getId($applyUser);
			if($applicant){
				$where .= " and applicant=$applicant ";
			}
		}
		if(!empty($applyItem)){
			$where .= " and item like '%{$applyItem}%' ";
		}

		$page = isset($_GET['nowPage']) ? intval($_GET['nowPage']) : 1;
		$pagesize = 10;
		$offset = ($page-1)*$pagesize;

		$user = yii::app()->user;
		$sid  =  $user->id;
		$db     = yii::app()->db_eel;
		$sql    = "";
		$auth   = new AuthManager();
		$data   = array();

		$roles = $auth->userRoles($sid);
		if($auth->isSupper($sid)) {

			$sql = "select * from developer_Apply where status < 3".$where;
			$sql .=$orderCondition;
			$results = $db->createCommand($sql)->queryAll();
		} elseif ($roles) {
			$results = array();
			foreach($roles as $dkey=>$dval) {
				$tmp = array();
				$sql = "select * from developer_Apply where status <= 2 and status != 0 and departid='$dkey' ".$where;
				$sql .=$orderCondition;
				$tmp = $db->createCommand($sql)->queryAll();
				if (!$tmp) {
					continue;
				}
				$results = array_merge($results, $tmp);
				unset($tmp);
			}
			// 直属上级
			$ltmp = array();
			$sql = "select * from developer_Apply where status = 0 and leader =$sid ".$where;
			$sql .=$orderCondition;
			$ltmp = $db->createCommand($sql)->queryAll();
			$results = array_merge($results, $ltmp);
		}else {
			// 直属上级
			$sql = "select * from developer_Apply where status = 0 and leader = $sid ".$where;
			$sql .=$orderCondition;
			$results = $db->createCommand($sql)->queryAll();
		}

		$total = count($results);
		if ($total > $pagesize) {
			$results = array_slice($results, $offset, $pagesize);
		}

// 		$apply = new ApplyManager();
		foreach ($results as $key => $value) {
// 			if(!$auth->checkAccess($value['item'],$value['applicant'])) {
				$data[$key] =  $results[$key];
// 			}else {
// 				$apply->updateApply($value['id'], 3, '3195');
// 			}
		}

		$users = array();
		foreach ($data as $key => $value) {
			$username = NewCommonManager::getUsernameByuid($value['applicant']);
			$data[$key]['appuid'] = $value['applicant'];
			//判断，如果存在申请用户，用户的数加1
			if(isset($users[$value['applicant']])) {
				$users[$value['applicant']] ++;
			} else {
				$users[$value['applicant']] = 1;
			}
			$data[$key]['applicant']=NewCommonManager::getUsernameByuid($value['applicant']);
			$data[$key]['depart'] = NewCommonManager::getUserDepartByName($username);
			$admins = $auth->getDepartManage($data[$key]['depart']);
			$admin = array();
			foreach ($admins as $val) {
				$tmp = NewCommonManager::getUserRealnameByuid($val);
				$tmp && $admin[] = $tmp;
			}
			if ($value['status'] == 0) {
				$data[$key]['operate'] = NewCommonManager::getUserRealnameByuid($value['leader']);
			} elseif($value['status'] == 1 or $value['status'] == 1) {
				if ($admin ) {
					$data[$key]['operate'] = implode(',', $admin);
				} else {
					$data[$key]['operate'] = '';
					$data[$key]['text'] = "发邮件到".MailManager::FENGKONG_MAIL;
				}
			}
		}

		$pageInfo = PageManager::getPageInfo($total, $page, $pagesize);
		$params = array("data"=>$data,
			            'totalNum'=>$total,
						'pageInfo'=>$pageInfo,
						'users'=>$users ,
						'condition'=>$condition,
						'applyUser'=>$applyUser,
						'applyItem'=>$applyItem
						);
		$this->render("apply/applycheck.tpl",$params);
	}

	public function actionIndex() {

		$speedUser = Yii::app()->user;
		$uid    = $speedUser->id;
        $depart = NewCommonManager::getUserDepart($uid);

		$auth = new AuthManager();
		$isSuper = $auth->isSupper($uid);
		$business = array();
		if ($isSuper) {
			$bus = new BusinessManager();
			$business = $bus->getALLBusiness();
		} else {
			$role = new RoleManager();
			$results = $role->departBusiness($depart);
			if ($results)  {
				foreach ($results as $key => $value) {
					$business[$value['business']]['cname'] = $value['cname'];
					$business[$value['business']]['business'] = $value['business'];
				}
			}
		}
		$pub = new PublishManager();
		$business = $pub->getUserBusiness();

		foreach ($business as $key=>$val) {
			if ($val['business'] == "risk") {
				unset($business[$key]);
				continue;
			}
		}
		$this->render("apply/index.tpl",array("business"=>$business,"status"=>4, "depart"=>$depart));
	}

	public function actionCondition($business = NULL,$status = NULL,$funname = NULL) {

		$sid       = yii::app()->user->id;
		$statushas = $this->checkStatus();
		$depart = NewCommonManager::getUserDepart($sid);
		$data  =  $funlist  = array();
		$haveItem  = $this->auth->getAuthFunction($sid);
        $developerFlag = $this->function->isDeveloperByid($business,$sid);
        $superFlag = $this->auth->isSupper($sid);
		$where     = "";
		$like      = "";

		if($funname) {
			$like = " and b.funname like  '%$funname%' ";
		}

		if(!empty($business)) {
			$where .= " and b.business='$business'".$like;
		}

		if($superFlag || $developerFlag) {

			$db = yii::app()->sdb_eel;
			$sql = "select b.business cname, b.id, b.type, b.business,b.funname,b.description,b.item from
					developer_function b,developer_business a where a.business=b.business and b.status!=". FunctionManager::FUN_STATUS_DEL .$where;
			$funlist = $db->createCommand($sql)->queryAll();
		} else {
			$auth = new AuthManager();
			$funlist = $auth->getDepartFunction($depart, $where);
		}

		if (!$funlist) {
			$results['rows'] = array();
			$results['total'] = 0;
			echo json_encode($results);exit;
		}

	 	if(!empty($status)&&$status==3) {
	 		foreach ($funlist as $key => $value) {
	 			$authFlag = in_array($value['item'], $haveItem);
	 			if($superFlag||$developerFlag||$this->auth->whiteListRule($value['item'])||$authFlag) {
					$funlist[$key]['status'] = 3;
					$data []= $funlist[$key];
				}
			}
	 	}
	 	if(!empty($status)&&$status==5) {
	 		if($status==0) {
	 		}
	 		foreach ($funlist as $key => $value) {
	 			$authFlag = in_array($value['item'], $haveItem);
		 		if($superFlag||$developerFlag||$this->auth->whiteListRule($value['item'])||$authFlag) {

					$funlist[$key]['status'] = 3;
					$data []= $funlist[$key];
				} else {
					if(isset($statushas[$value['id']])) {
						$funlist[$key]['status'] = $statushas[$value['id']];
						$data []= $funlist[$key];
					} else {
						$funlist[$key]['status'] = 4;
						$data []= $funlist[$key];
					}
				}
		 	}
	 	}
	 	if(!empty($status)&&$status==4) {
	 		foreach ($funlist as $key => $value) {
	 			$authFlag = in_array($value['item'], $haveItem);
		 		if(!$superFlag&&!$developerFlag&&!$this->auth->whiteListRule($value['item'])&&!$authFlag){
					if(isset($statushas[$value['id']])) {
						if($statushas[$value['id']]!=0&&$statushas[$value['id']]!=1&&$statushas[$value['id']]!=2) {
							$funlist[$key]['status'] = 4;
							$data []= $funlist[$key];
						}
					} else {
						$funlist[$key]['status'] = 4;
						$data []= $funlist[$key];
					}
				}
			}
	 	}

	 	if(empty($status)&&$status==0) {
	 		foreach ($funlist as $key => $value) {
	 			$authFlag = in_array($value['item'], $haveItem);
		 		if(!$superFlag&&!$developerFlag&&!$this->auth->whiteListRule($value['item'])&&!$authFlag) {
					if(isset($statushas[$value['id']])) {
						if($statushas[$value['id']]<3) {
							$funlist[$key]['status'] = $statushas[$value['id']];
							$data []= $funlist[$key];
						}
					}
				}
			}
	 	}
	 	//过滤掉特殊方法
	 	foreach($data as $dkey=>$dval) {
	 		if ($dval['type'] == FunctionManager::$function_special) {
	 			unset($data[$dkey]);
	 		}
	 	}
	 	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$results = array();
	 	for ($i=$offset; $i <$offset+$rows; $i++) {
	 		if(isset($data[$i])) {
	 			array_push($results, $data[$i]);
	 		}
	 	}
	 	$results['rows'] = $results;
	 	$results['total'] = count($data);
		echo json_encode($results);
	}

	public function actionApplydeal() {

		$db = yii::app()->db_eel;
		$auth = yii::app()->authManager;
		$user = yii::app()->user;
		$sid = $user->id;
		try{
			// 处理人相关信息
			$current_usermail = $user->username;
			$current_username  = $user->name;
			//$depart = $user->depart;

			$sql ="select item,applyid,applicant from developer_Apply where id='".$_POST['id']."'";
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
				$db->createCommand($sql)->execute(array(3, $sid, $_POST['id']));
				if(!$auth->checkAccess($item,$nameid)) {
					$auth->assign($item, $nameid);
				}
				SyslogManager::Write($current_usermail, SyslogManager::LOG_SUPER_ALLOW, $applicantEmail,$item);
				$this->mail->sendMail($applicantEmail,$item,'success');
			} elseif($auth->checkAccess($depart,$sid)) {

				$sql = "update developer_Apply set status=?, adminid=? where id=?";
				$db->createCommand($sql)->execute(array(3, $sid, $_POST['id']));
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
				$db->createCommand($sql)->execute(array(1, $sid, $_POST['id']));
				//log
				SyslogManager::Write($current_usermail, SyslogManager::LOG_LEADER_ALLOW, $applicantEmail, $item);
				$this->mail->sendMail($to,$applicantName."，领导{$current_username}已通过审核",'group');
			}

			// 发送邮件给审批人
			$manage_content = "申请人：".$applicantName .",功能：". $item;
			$this->mail->sendMail($current_usermail, $manage_content, 'manager');

		} catch (Exception $e) {
			$to = 'linglingqi';
			$this->mail->sendCommMail($to, '报警', $e->getMessage());
			echo json_encode("操作失败，请联系linglingqi@meilishuo确认原因");
		}
		echo json_encode("已通过该申请");

	}

	/**
	 * 批量审核通过
	 */
	public function actionapplydealbat() {

		$ids = $_POST['ids'];
		$uid = $_POST['uid'];

		if (!$ids) {
			echo json_encode("必须选中通过申请");exit;
		}

		$apply = new ApplyManager();
		$succ = $fail = array();

		foreach($ids as $val) {
			$arr = explode('-', $val);
			$app_uid = $arr[1];
			$id = $arr[0];
			$item = $arr[2];
			if ($app_uid == $uid) {
				try {
					$apply->dealApply($id);
					$succ[] = $item;
				} catch (Exception $e){
					$to = 'linglingqi';
					$this->mail->sendCommMail($to, '报警', $e->getMessage());
					$fail[] = $item;
				}
			} else {
				$fail[]=$item;
			}
		}
		$str = "";
		if ($succ) {
			$str = "成功通过:". implode(',', $succ);
		}
		if ($fail) {
			$str .= '操作失败:'. implode(',', $fail);
		}
		if ($str) {
			echo json_encode($str);
		} else {
			echo json_encode("操作失败～");
		}
	}

	public function actionRefuse() {

		$id = $_POST['id'];
		$message = trim($_POST['message']);
		$sid = Yii::app()->user->id;
		$current_usermail = Yii::app()->user->username;

		$db = yii::app()->db_eel;
		$sql ="update developer_Apply set status=?,message=?, adminid=? where id=?";
		$results = $db->createCommand($sql)->execute(array(6,$message, $sid, $id));
		$sql ="select item,applicant from developer_Apply where id='".$id."'";
		$results = $db->createCommand($sql)->queryAll();
		$item = $results[0]['item'];
		$nameid = $results[0]['applicant'];
		$name = NewCommonManager::getUsernameByuid($nameid);
		//log
		SyslogManager::Write($current_usermail, SyslogManager::LOG_REJECT, $name, $item);
		$this->mail->sendMail($name,$item,'refuse');
		$manage_content = "申请人：".$name .",功能：". $item;
		$this->mail->sendMail($current_usermail, $manage_content, 'managerRef');
		echo json_encode("审批成功");
	}
	public function actionAdmin($departid = "") {

		$user = Yii::app()->user;
		$auth = new AuthManager();
		$departall = NewCommonManager::getDepartRole();
		if (!$auth->isSupper($user->id)) {

			$roles = $auth->userRoles($user->id);
			$formate_rols = array();
			foreach($roles as $rkey=>$rval) {
                if ($index = strpos($rval, '/')) {
                    $formate_rols[substr($rval, 0, $index)] = substr($rval, 0, $index);
                } else {
                    $formate_rols[$rval] = $rval;
                }
            }
			foreach($departall as $key=>$val) {
				if (!isset($formate_rols[$val])) {
					unset($departall[$key]);
				}
			}
			$departid = $user->departid;
			if (!isset($departall[$$departid])) {
				$departall[$departid] = $user->depart;
			}
		}
		$depart = array();
		$m = "";
		foreach ($departall as $key => $value) {
			preg_match_all("/[\x{4e00}-\x{9fa5}|A-Z]+/iu",$value, $m);
			if(isset($m[0][1])) {
				$depart[$m[0][0]][] = array('id'=>$key,"name"=>$m[0][1]);
			} else {
				$depart[$m[0][0]][] = array('id'=>$key,"name"=>$m[0][0]);
			}
		}
		$this->render("apply/admin.tpl",array("depart"=>$depart,"departid"=>$departid));
	}

	public function actionGetRoleList($departid)
	{
		$all = $this->common->getAdminDepartUser($departid);
		//$result =array("all"=>$all,"departid"=>$departid);
		echo json_encode($all);
	}

	public function actionGetActionList($roleid) {

		$auth = new AuthManager();
		$current_user = Yii::app()->user;
		$select = $selected = array();
		//$all_item = $auth->getDepartFunctions($current_user->depart);
		$all_item = FunctionManager::getFunctionOfAdmins($current_user->id);
		//var_dump($all_item);exit;
		$has_item = $auth->getAuthFunction($roleid);

		$select = !empty($all_item) ? array_diff($all_item, $has_item) : $all_item;
		$selected = !empty($has_item) ? array_intersect(array_values($has_item), array_values($all_item)) : $has_item;

		$select = array_unique($select);
		$selected = array_unique($selected);

		$data = array("select"=>array_values($select),"selected"=>array_values($selected));
		echo json_encode($data);
	}

	public function actionUpdateaction() {

		try {
			$actionlist = $_POST['actionlist'] ? $_POST['actionlist'] : array();
			$roleid = $_POST['roleid'];

			$auth = new AuthManager();
			$current_user = Yii::app()->user;
			$select = $selected = array();
			$all_item = $auth->getDepartFunctions($current_user->depart);
			$has_item = $auth->getAuthFunction($roleid);

			$select = !empty($all_item) ? array_diff($all_item, $has_item) : $all_item;
			$selected = !empty($has_item) ? array_intersect($has_item, $all_item) : $has_item;

			$delete = $add = array();
			$add = array_diff($actionlist,$selected);
			$delete = array_diff(array_values($selected),array_values($actionlist));

			$add = array_unique($add);
			$delete = array_unique($delete);
			$mauth =yii::app()->authManager;
			if(!empty($add)) {
				foreach ($add as $key => $value) {
					if (!$auth->checkAccess($value,$roleid,'true')) {
						$mauth->assign($value,$roleid);
						SyslogManager::Write($current_user->username, SyslogManager::FUNCTION_ASSIGN_USER, NewCommonManager::getUsernameByuid($roleid), $value);
					}
				}
			}

			if(!empty($delete)) {
				foreach ($delete as $key => $value) {
					if ($auth->isAssign( $value, $roleid)) {
						$mauth->revoke($value,$roleid);
						SyslogManager::Write($current_user->username, SyslogManager::FUNCTION_REVOKE_USER, NewCommonManager::getUsernameByuid($roleid), $value);
					}
				}
			}
			echo json_encode("提交成功！");
		}catch(Exception $e) {
    		$mail = new MailManager();
    		$mail->sendCommMail('linglingqi', '报警', $e->getMessage());
    		echo json_encode(array("code"=>0, "msg"=>"添加失败"));
    	}


	}

	public function actionCheckIndex() {

		$userid = yii::app()->user->id;
		$auth = new AuthManager();
		$admin = $auth->getAdminOfFengkong();

		$hepler = new HelperManager();
		$admin = $hepler->hashmap($admin, 'userid');
		$is_super = $auth->isSupper($userid);
		$flag = 0;
		if (in_array($admin[$userid]) || $is_super) {
			$flag = 1;
		}

		$db = Yii::app()->sdb_eel;
		$sql = "select  developer_function.business,developer_business.cname,developer_business.description from developer_function,developer_business
				where developer_function.business=developer_business.business group by business";
		$results = $db->createCommand($sql)->queryAll();

		$fun = new FunctionManager();
		$type = $fun->getFunctionKinds();

		$this->render("apply/checkup.tpl",array("data"=>$results, 'flag'=>$flag, 'type'=>$type));
	}

	public function actionCheckup($business='',$funname='',$content='',$type='') {
		$db = Yii::app()->db_eel;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
		$where = " where developer_function.business=developer_business.business and developer_function.status !=". FunctionManager::FUN_STATUS_DEL;
		if($business) {
			$where .= " and developer_function.business = '$business' ";
		} else {
			//添加业务号判断，对不存在业务的功能不展现
			$bus = new BusinessManager();
			$businesses = $bus->getALLBusiness();
			$businesses = ArrFomate::hashmap($businesses, 'business');
			$b_key = array_keys($businesses);
			$where .= " and developer_function.business in ('". implode("','", $b_key) ."')";
		}
		if($funname!=9) {
			$where .= " and developer_function.status = '$funname' ";
		}
		if ($type) {
			$fun = new FunctionManager();
			$types = $fun->getFunctionKinds();
			$index = '';
			foreach($types as $key=>$val) {
				if ($type == $val) {
					$index = $key;
				}
			}
			if (in_array($index,array_keys($types))){
				$where .= " and developer_function.type='$index'";
			}
		}
		$contents = '';
		if($content) {
			$contents = " and  developer_function.funname like  '%$content%' ";
		}

		$sql = "select developer_function.id, developer_function.status,developer_function.url,developer_business.developer,developer_function.type,developer_function.item, developer_function.business,developer_business.description,developer_function.funname,developer_function.unix  from developer_business, developer_function".$where.$contents." limit $offset,$rows";
		$results = $db->createCommand($sql)->queryAll();
		$sql = "select developer_function.id, developer_function.status,developer_function.url,developer_business.developer,developer_function.type,developer_function.item, developer_function.business,developer_business.description,developer_function.funname,developer_function.unix  from developer_business, developer_function".$where.$contents;
		$total = $db->createCommand($sql)->queryAll();



		foreach ($results as $key => $value) {
			$url = 'http://'.$value['description'].$value['url'];
			$results[$key]['description'] = '<a href ='.$url.' target="_blank"> 预览</a>';
			$results[$key]['applicant'] = Yii::app()->user->name;
			$sort[$key] = $value['unix'];
		}
		$results = array("rows"=>$results,"total"=>count($total));
		echo json_encode($results);
	}

	public function actionGetDepart($id)
	{
		$depart = NewCommonManager::getDepartRole();
		$db = Yii::app()->db_eel;
		$sql = "select item from developer_function where id='$id' ";
		$item = $db->createCommand($sql)->queryColumn();
		$item = $item[0];
		$sql  = "select parent from developer_AuthItemChild where child = '$item' ";
		$selected = $db->createCommand($sql)->queryColumn();
		$selected = array_intersect($selected,$depart);
		$result = array();
		foreach ($depart as $key=>$val) {
			if (in_array($val, $selected)) {
				$result[$key] = $val;
			}
		}
		$depart = array_diff($depart, $result);

		$data = array("select"=>$depart,"selected"=>$result);
		$auth = Yii::app()->authManager;
		echo json_encode((array)$data);
	}

	public function actionChangeCheck() {

		$result = self::changeDepartPrivilege($_POST['rows']);
		if ($result) {
			echo json_encode("分配成功");
		}else {
			echo json_encode("分配失败");
		}

	}

	/**
	 * 更改功能的部门，来赋予它权限
	 * @param  $rows
	 */
	public function changeDepartPrivilege($rows=array()) {

		try {
			$current_user = Yii::app()->user->username;
			$auth = Yii::app()->authManager;
			$depart = NewCommonManager::getDepartRole();
			$db = Yii::app()->db_eel;
			$local_auth = new AuthManager();
			foreach ($rows as $key => $value) {
				if($value['type']!=0) {
					$sql = "update developer_function set type=? ,status=? where id=? ";
					$db->createCommand($sql)->execute(array($value['type'],2,$value['id']));
					SyslogManager::Write($current_user, SyslogManager::FUNCTION_CHECK, '', $value['funname']);
				}else {
					if(isset($value['depart'])) {
						$departid = $value['depart'];
						$child = $value['item'];

						$departname = array();
						if(!empty($departid)) {
							foreach ($departid as $key => $vid)  {
								if(isset($depart[$vid])) {
									$departname[$vid] = $depart[$vid];
								}
							}
						}
						$sql = "select parent from developer_AuthItemChild where child="."'".$child."'";
						$item = $db->createCommand($sql)->queryColumn();
						$item = array_intersect($item,$depart);
						$add = array_diff($departname,$item);
						$delete = array_diff($item, $departname);
						if($departname) {
							if ($add) {
								foreach ($add as $key => $parent) {
									$sql = "select * from developer_AuthItem where name = '$parent' ";
									$results = $db->createCommand($sql)->queryAll();
									if(empty($results)) {
										$auth->createRole($parent);
										SyslogManager::Write($current_user, SyslogManager::LOG_ROLE_ADD, '', $parent);
									}
									$auth->addItemChild($parent,$child);
									SyslogManager::Write($current_user, SyslogManager::ROLE_FUNCTION_ADD, $parent, $child);
									//判断角色管理员是否有功能权限，没有权限给赋值
									$roles = $local_auth->getDepartManage($parent);
									if ($roles) {
										foreach($roles as $rval) {
											if(!$auth->isAssigned($value['item'],$rval)) {
												$auth->assign($value['item'],$rval);
												SyslogManager::Write($current_user, SyslogManager::FUNCTION_ASSIGN_USER, $rval, $value['item']);
											}
										}
									}
								}
							}
						}
						if($delete){
							$fun = new FunctionManager();
							foreach ($delete as $key => $parent){
								$auth->removeItemChild($parent,$child);
								SyslogManager::Write($current_user, SyslogManager::ROLE_REVOKE, $parent, $child);
								$roles = $local_auth->getDepartManage($parent);
								if ($roles) {
									foreach($roles as $rval) {
										if($local_auth->isSupper($rval) || $fun->isDeveloper($value['business'], $rval)) {
											continue;
										}
										if($auth->isAssigned($value['item'],$rval)) {
											$auth->revoke($value['item'],$rval);
											SyslogManager::Write($current_user, SyslogManager::FUNCTION_REVOKE_USER, $rval, $value['item']);
										}
									}
								}
							}
						}
					}
					$sql = "update developer_function set type=?,status=? where id=? ";
					$db->createCommand($sql)->execute(array($value['type'],2,$value['id']));

				}

			}
			return true;
		} catch(Exception $e) {
    		$mail = new MailManager();
    		$mail->sendCommMail('linglingqi', '报警', $e->getMessage());
    		echo json_encode(array("code"=>0, "msg"=>"添加失败"));
		}

	}

	public function actionMail()
	{
		$speed = new speed();
		var_dump($speed);
	}

	public function actionGetdepartAll()
	{
		$depart = NewCommonManager::getDepartRole();
		echo json_encode($depart);
	}

	public function actionDistAll()
	{
		try {
			$username = Yii::app()->user->username;
			$rows = $_POST['rows'];
			$departList = $_POST['departlist'];
			$auth = yii::app()->authManager;
			$departArray = NewCommonManager::getDepartRole();

			foreach ($rows as $key => $value){
				$item = $value['item'];

				foreach ($departList as $k => $val) {
					$depart = $departArray[$val];

					if(!$auth->getAuthItem($depart)) {
						$auth->createRole($depart);
						SyslogManager::Write($username, SyslogManager::LOG_ROLE_ADD, '', $depart);
					}
					if(!$auth->hasItemChild($depart,$item)) {
						$auth->addItemChild($depart,$item);
						SyslogManager::Write($username, SyslogManager::LOG_ROLE_ADD, $depart, $item);
					}
				}
			}
			self::changeDepartPrivilege($rows);

			echo json_encode("分配成功");
		} catch(Exception $e) {
			$mail = new MailManager();
			$mail->sendCommMail('linglingqi', '报警', $e->getMessage());
			echo json_encode(array("code"=>0, "msg"=>"添加失败"));
		}

	}

	public function actionUpDateMenu()
	{
		$this->render("apply/updateMenu.tpl");
	}

	public function actionAjaxUpdateMenu($uid)
	{
		$this->menu->getTest('work',$uid,'true','true');
		echo json_encode("success");
	}

}