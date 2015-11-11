<?php

class AuthController extends Controller {

    public $defaultAction = 'Role';
    public function actionRole($departid='') {

    	$auth = new AuthManager();
    	$speedUser = yii::app()->user;
    	$uid = $speedUser->id;
    	$departname = '';
    	$depart = array();
    	if ($auth->isSupper($speedUser->id)) {
    		if ($departid) {
    			$depart = NewCommonManager::getDepartRole();
    			$departname = NewCommonManager::getDepartRoleByDid($departid);
    		} else {
    			$departname = NewCommonManager::getUserDepart($uid);
    			$departid = NewCommonManager::getDepartidByDepartRole($departname);
    			$depart = NewCommonManager::getDepartRole();
    		}
    	}else {
    		$depart = NewCommonManager::getDepartRole();
    		$roles = $auth->userRoles($uid);
    		$formate_rols = array();
    		foreach($roles as $rkey=>$rval) {
                if ($index = strpos($rval, '/')) {
                    $formate_rols[substr($rval, 0, $index)] = substr($rval, 0, $index);
                } else {
                    $formate_rols[$rval] = $rval;
                }
            }
    		foreach($depart as $key=>$val) {
    			if (!isset($formate_rols[$val])) {
    				unset($depart[$key]);
    			}
    		}
    		$user_departid = NewCommonManager::getUserDepartId($uid);
    		$user_departname = NewCommonManager::getUserDepart($uid);
    		if (!$departid) {
    			if (!$user_departid) {
    				$this->render('error/404.tpl', array('message'=>"没有该部门的权限！"));
    				Yii::app()->end();
    			} else {
    				$departname = $user_departname;
    			}
    		} else {
    			$departname = $depart[$departid];
    		}

    		if (!isset($departall[$user_departid])) {
    			$depart[$user_departid] = $user_departname;
    		}
    	}

        $db = Yii::app()->sdb_eel;

        $sql = "select * from developer_AuthItem where developer_AuthItem.type =2
            and developer_AuthItem.name  in (select developer_AuthItemChild.child
            from developer_AuthItemChild where developer_AuthItemChild.parent='".$departname."')";
        $results = $db->createCommand($sql)->queryAll();
        foreach ($results as $key => $value) {
            $results[$key]['name'] = substr($value['name'],strpos($value['name'],"/")+1);
        }
		$choice_depart = $depart;
		unset($choice_depart[$departid]);
        $this->render("authority/roles.tpl",array("data"=>$results,"depart"=>$depart,"departname"=>$departname, "departid"=>$departid, 'choice_depart'=>$choice_depart));
    }

    /**
     * 获取权限对应的人员
     */
    public function actionRoleUser($depart, $role) {

    	$userid = Yii::app()->user->id;
    	$auth = new AuthManager();
    	$all = array();
    	!$depart && $depart = NewCommonManager::getUserDepartId($userid);
    	$departArray = NewCommonManager::getDepartRole();
    	if ($auth->isSupper($userid)) {
    		$comm = new CommonManager();
    		$all = $comm->getAllusers();
    	} else {

    		$all = $this->common->getDepartUsers($depart);
    	}

        $departname = $departArray[$depart];
        $rolename = $departname."/".$role;
        $selected = $this->auth->getRoleUsers($rolename);
        $this->render("authority/roleuser.tpl",array("role"=>$role, "depart"=>$depart, "selected"=>$selected, "whole"=>$all));
    }

    public function actionRoleUserAdd() {

    	try {
    		$user = Yii::app()->user;
    		$username = $user->username;
    		$userid = $user->id;
	        $selected = $_POST['selected'];
	        if(!is_array($selected)) {
	            $selected = array();
	        }
	        $role = $_POST['role'];
	        $depart =$_POST['departid'];

	        $auth = new AuthManager();
	        if ($auth->isSupper($userid)) {
	        	$comm = new CommonManager();
	        	$all = $comm->getAllusers();
	        } else {

	        	$all = $this->common->getDepartUsers($depart);
	        }

	        $departArray = NewCommonManager::getDepartRole();
	        $depart = $departArray[$depart];
	        $role = $depart."/".$role;
	        $auth = Yii::app()->authManager;
	        $count = 0;
	        $deletecount = 0;
	        $roleUser = $this->auth->getRoleUsers($role);
	        $allStaffId = array();
	        foreach ($all as $key => $value) {
	            $allStaffId [] = $value['id'];
	        }

	        $roleUser = array_intersect($roleUser, $allStaffId);
	        $selected = array_intersect($selected, $allStaffId);

	        $add = array_diff($selected, $roleUser);
	        $delete = array_diff($roleUser, $selected);

	        if (!$add && !$delete) {
	        	echo json_encode(array("code"=>0, "msg"=>"没有修改角色下的用户"));
	        	exit;
	        }

	        if(!empty($add)){
	            foreach ($add as $key => $value) {
	                if(!empty($value) && !$auth->checkAccess($role, $value)) {
	                    $auth->assign($role, $value);
	                    SyslogManager::Write($username, SyslogManager::LOG_ADD_ROLE_USER, NewCommonManager::getUsernameByuid($value), $role);
	                    $count += 1;
	                }
	            }
	        }
	        $lauth = new AuthManager();
	        if(!empty($delete)){
	            foreach ($delete as $key => $value) {
	            	if ($lauth->isAssign($role, $value)){
		                $auth->revoke($role, $value);
		                $deletecount += 1;
		                SyslogManager::Write($username, SyslogManager::LOG_DEL_ROLE_USER, NewCommonManager::getUsernameByuid($value), $role);
	            	}
	            }
	        }
	        $results = array("code"=>0, "msg"=>"操作成功", "data"=>array('count' => $count));
	        echo json_encode($results);
    	}catch(Exception $e) {
    		$mail = new MailManager();
    		$mail->sendCommMail('linglingqi', '报警', implode(',', $e));
    		echo json_encode(array("code"=>0, "msg"=>"添加失败"));
    	}
    }

    public function actionRoleAdd()
    {
    	try {
	        $msg = "";
	        $rolename = "";
	        $redata = array();
	        $username = Yii::app()->user->username;
	        if(isset($_POST['departid']))
	        {
	            $depart = NewCommonManager::getDepartRole();
	            $depart = $depart [$_POST['departid']];
	        }
	        else
	        {
	            $depart = $_POST['item'];
	        }
	        $auth = Yii::app()->authManager;
	        if(!empty($_POST['name'])) {
	            $db = Yii::app()->sdb_eel;
	            $rolename = $depart."/".$_POST['name'];
	            if(!$auth->getAuthItem($rolename))
	            {
	                $role = $auth->createRole($rolename,$_POST['disc'],$_POST['rule'],$_POST['data']);
	                SyslogManager::Write($username, SyslogManager::LOG_ROLE_ADD, '', $rolename);
	                if(!$auth->getAuthItem($depart))
	                {
	                    $auth->createRole($depart);
	                }
	                if(!$auth->hasItemChild($depart,$rolename))
	                {
	                    $auth->addItemChild($depart,$rolename);
	                }
	                $redata=array("msg"=>"添加成功","depart"=>$depart,"rolename"=>$rolename);
	            } else {
	                $redata=array("msg"=>"添加失败，可能重名","depart"=>$depart,"rolename"=>$rolename);
	            }

	        } else {
	            $redata=array("msg"=>"添加失败，名字不能为空","depart"=>$depart,"rolename"=>$rolename);
	        }
	        echo json_encode($redata);
        }catch(Exception $e) {
        	$mail = new MailManager();
        	$mail->sendCommMail('linglingqi', '报警', $e->getMessage());
        	echo json_encode(array("code"=>0, "msg"=>"添加失败"));
        }
    }

    public function actionRoleDelete()
    {
    	try {
	        $id = $_POST['id'];
	        $username = Yii::app()->user->username;
	        $db = Yii::app()->sdb_eel;
	        $sql = "select name from developer_AuthItem where id='$id' ";
	        $name = $db->createCommand($sql)->queryAll();
	        $name = $name[0]['name'];
	        $auth = Yii::app()->authManager;
	        $rolename = substr($name,0,strpos($name,"/"));
	        $results = $auth->removeAuthItem($name);
	        $auth->removeItemChild($rolename,$name);

	        SyslogManager::Write($username, SyslogManager::LOG_ROLE_DEL, '', $name);

	        echo json_encode("删除成功".$results);
        }catch(Exception $e) {
        	$mail = new MailManager();
        	$mail->sendCommMail('linglingqi', '报警', $e->getMessage());
        	echo json_encode(array("code"=>0, "msg"=>"删除成功"));
        }
    }
//有bug，暂时不提供使用
//     public function actionRoleUpdata() {

//         $msg = "";$disc = "";$rule = "";$data = "";
//         $username = Yii::app()->user->username;
//         if(empty($_POST['name']) || empty($_POST["id"])) {
//             $msg = "名字不能为空";
//             echo json_encode($msg);
//             return;
//         }
//         $name = trim($_POST['name']);
//         $db = Yii::app()->db_eel;
//         $sql = "update developer_AuthItem set name = ?, type = ?, description = ?, bizrule = ?,data = ? where id = ? ";
//         if(!empty($_POST["disc"]))
//         {
//             $disc = trim($_POST["disc"]);
//         }
//         if(!empty($_POST["rule"]))
//         {
//             $rule = $_POST["rule"];
//         }
//         if(!empty($_POST["data"]))
//         {
//             $data = $_POST["data"];
//         }
//         $db->createCommand($sql)->execute(array($name, 2, $disc,$rule,$data,$_POST["id"]));
//         SyslogManager::Write($username, SyslogManager::LOG_ROLE_UPD, $_POST["id"], $name);
//         $msg="更新成功";

//         echo json_encode(array("code"=>0, "msg"=>$msg));
//     }

    public function actionDistribution()
    {
        $showdepart = NewCommonManager::getUserDepartByName(yii::app()->user->username);
        $departall = NewCommonManager::getDepartRole();
        $sid = $this->user->id;
        $auth = yii::app()->authManager;
        $db = yii::app()->sdb_eel;
        $data = "";
        $depart = array();
        foreach ($departall as $key => $value) {
            preg_match_all("/[\x{4e00}-\x{9fa5}|A-Z]+/iu",$value, $m);
            if(isset($m[0][1])) {
                $depart[$m[0][0]][] = array('id'=>$key,"name"=>$m[0][1]);
            } else {
                $depart[$m[0][0]][] = array('id'=>$key,"name"=>$m[0][0]);
            }
        }

        if($auth->checkAccess('super',$sid)) {
        	$sql = "select name from developer_AuthItem where type = 2 ";
        	$results = $db->createCommand($sql)->queryColumn();
            foreach ($results as $key => $value) {
                if(!in_array($value,$depart)) {
                    $depart['其他'] [0] = array('id'=>"other","name"=>"其他");
                }
            }
        } else {
        	$sql = "select b.name from developer_AuthAssignment a, developer_AuthItem b where a.itemname=b.name and b.type =2 and a.userid=".yii::app()->user->id;
        	$results = $db->createCommand($sql)->queryColumn();
        	foreach ($results as $key => $value) {

        		if ($index =strpos($value ,'/')) {
        			$value = substr($value, 0, $index);
        		}
        		preg_match_all("/[\x{4e00}-\x{9fa5}|A-Z]+/iu",$value, $m);
        		$value = $m[0][0];
        		if(!isset($depart[$value])) {
        			$tmp['其他'] [0] = array('id'=>"other","name"=>"其他");
        		}else {
        			$tmp[$value] = $depart[$value];
        		}
        	}
        	$depart = array();
            $depart[$showdepart]= array("部门"=>array('id'=>yii::app()->user->departid,"name"=>$showdepart));
            $depart = array_merge($depart, $tmp);
        }

        $this->render("authority/distribution.tpl",array("depart"=>$depart,"data"=>$data));
    }

    public function actionSearchUser() {

    	$comm = new CommonManager();
    	$results = $comm->getUser($_POST['username']);

    	if(!empty($results)) {

    		$ename = explode('@',$results['mail']);
    		$username = $ename[0];
    		echo json_encode(array('code'=>1, 'msg'=>'成功！', 'data'=>$results));
    		exit;
    	}
    	echo json_encode(array('code'=>0, 'msg'=>'没有搜到用户信息,请检查用户是否存在！'));
    }

    public function actionRoleView()
    {
        $uid = $this->auth->getSid($_GET['umail']);
        $auth = yii::app()->authManager;
        $sid = $this->auth->getSid(yii::app()->user->username);
        $depart = $this->auth->getDepart(yii::app()->user->username);
        $db = Yii::app()->sdb_eel;
        $superflag = false;
        if($auth->checkAccess('super',$sid))
        {
            $superflag = true;
            $sql = "select name from developer_AuthItem where developer_AuthItem.type=2";
        }
        else
        {
            $sql = "select developer_AuthItemChild.child from developer_AuthItemChild,developer_AuthItem where developer_AuthItem.name=developer_AuthItemChild.child and developer_AuthItem.type=2 and developer_AuthItemChild.parent = '$depart' ";
        }
        $all = $db->createCommand($sql)->queryColumn();
        $sql = "select developer_AuthAssignment.itemname from developer_AuthItem,developer_AuthAssignment where developer_AuthItem.type=2 and developer_AuthAssignment.itemname=developer_AuthItem.name and developer_AuthAssignment.userid= '$uid' ";
        $results = $db->createCommand($sql)->queryColumn();
        if($auth->checkAccess('super',$sid))
        {
            $selected = $results;
        }
        else{
            $selected = array_intersect($results, $all);
        }
        $select = array_diff($all,$results);
        $data = array();
        $data['selected'] = $selected;
        $data['select'] = $select;
        $data['superflag'] = $superflag;
        echo json_encode($data);
    }

    public function actionUserForm() {
    	try {
    		$username = Yii::app()->user->username;
    		$uid = $this->auth->getSid($_POST['umail']);

    		$name_arr = explode("@",$_POST['umail']);
    		$optname = $name_arr[0];

    		$auth = Yii::app()->authManager;
    		$depart = NewCommonManager::getDepartRole();
    		$selected = isset($_POST['userrole'])?$_POST['userrole']:array();
    		$hasAuth = $auth->getAuthItems('2',$uid);
    		$allRole = $auth->getAuthItems('2',null);
    		$userAuthItem = array();
    		$allRoleItem = array();
    		$db = Yii::app()->db_eel;
    		foreach ($hasAuth as $key => $value) {
    			$userAuthItem [] = $key;
    		}
    		foreach ($hasAuth as $key => $value) {
    			$allRoleItem [] = $key;
    		}
    		$addArray = $deleteArray = array();
    		if ($userAuthItem) {
    			foreach ($userAuthItem as $val) {
    				if (!in_array($val, $selected)) {
    					$deleteArray[] = $val;
    				}
    			}
    		}
    		if ($selected) {
    			foreach ($selected as $sval) {
    				if (!in_array($sval, $userAuthItem)){
    					$addArray[] = $sval;
    				}
    			}
    		}

    		foreach ($addArray as $key => $value) {
    			if(!$this->auth->checkAccess($value,$uid,'true')) {
    				$auth->assign($value,$uid);
    				SyslogManager::Write($username, SyslogManager::ROLE_ASSIGN, $optname, $value);
    			}
    		}
    		foreach ($deleteArray as $key => $value) {
    			if($this->auth->checkAccess($value,$uid,'true')) {
    				$auth->revoke($value,$uid);
    				SyslogManager::Write($username, SyslogManager::ROLE_REVOKE, $optname, $value);
    			}
    		}

    		$superflag = false;
    		$sid = $this->auth->getSid(yii::app()->user->username);
    		$depart = $this->auth->getDepart(yii::app()->user->username);
    		if($auth->checkAccess('super',$sid)) {
    			$superflag = true;
    			$sql = "select name from developer_AuthItem where developer_AuthItem.type=2";
    		} else {
    			$sql = "select developer_AuthItemChild.child from developer_AuthItemChild,developer_AuthItem where developer_AuthItem.name=developer_AuthItemChild.child and developer_AuthItem.type=2 and developer_AuthItemChild.parent = '$depart' ";
    		}
    		$all = $db->createCommand($sql)->queryColumn();
    		$sql = "select developer_AuthAssignment.itemname from developer_AuthItem,developer_AuthAssignment where developer_AuthItem.type=2 and developer_AuthAssignment.itemname=developer_AuthItem.name and developer_AuthAssignment.userid= '$uid' ";
    		$results = $db->createCommand($sql)->queryColumn();
    		if($superflag) {
    			$selected = $results;
    		} else{
    			$selected = array_intersect($results, $all);
    		}
    		$select = array_diff($all,$results);
    		$data = array();
    		$data['selected'] = $selected;
    		$data['select'] = $select;
    		$data['superflag'] = $superflag;
    		echo json_encode($data);
    	}catch(Exception $e) {
    		$mail = new MailManager();
    		$mail->sendCommMail('linglingqi', '报警', $e->getMessage());
    		echo json_encode(array("code"=>0, "msg"=>"分配失败"));
    	}

    }

    public function actionTaskDist()
    {
    	try {
    		$db=yii::app()->db_eel;

    		$current_name = Yii::app()->user->username;

    		$auth = Yii::app()->authManager;
    		$departname = NewCommonManager::getDepartRole();
    		$depart = $departname[$_POST['departid']];
    		$rolename = $_POST['rolename'];
    		if(!empty($depart))
    		{
    			$rolename = $depart."/".$_POST['rolename'];
    		}
    		$sql = "select child from developer_AuthItemChild where parent ="."'".$rolename."'";
    		$selected = $db->createCommand($sql)->queryColumn();
    		$sql = "select name from developer_AuthItem";
    		$all = $db->createCommand($sql)->queryColumn();
    		$tasklist = $_POST['tasklist']?$_POST['tasklist']:array();
    		$select_num = count($selected);
    		$submit_num = count($tasklist);
    		if($select_num<$submit_num && $submit_num>900) {
    			echo json_encode(array("code"=>0,"msg"=>"操作失败:添加的数量太多"));
    			exit;
    		} elseif ($submit_num>=998) {
    			echo json_encode(array("code"=>0,"msg"=>"操作失败:添加的数量太多"));
    			exit;
    		}
    		$add = array_diff($tasklist, array_values($selected));
    		$delete = array_diff(array_values($selected),$tasklist);
    		$success = $result = array();
    		$fail = array();
    		if(!empty($delete)) {
    			foreach ($delete as $key => $value) {
    				$auth->removeItemChild($rolename,$value);
    				SyslogManager::Write($current_name, SyslogManager::ROLE_FUNCTION_DEL, $value, $rolename);
    			}
    		}
    		if(!empty($add)) {
    			foreach ($add as $key => $value) {

    				if(!$auth->getAuthItem($rolename)||!$auth->getAuthItem($value)) {
    					$list[$rolename] = $value;
    					$listArray [] = $list;
    					array_push($fail, array("msg"=>"没有该角色或功能，添加失败","Array"=>$listArray));
    				} else {
    					$auth->addItemChild($rolename,$value);
    					$list[$rolename] = $value;
    					$listArray [] = $list;
    					SyslogManager::Write($current_name, SyslogManager::ROLE_FUNCTION_ADD, $value, $rolename);
    					array_push($success, array("msg"=>"添加成功的功能","Array"=>$listArray));
    				}
    			}
    		}
    		if(empty($success)&&empty($fail)) {
    			$redata = "没有新增功能分配给该角色";
    			$result = array('code'=>0, 'msg'=>$redata);
    		} else {
    			$redata = array("succesArray"=>$success,"failArray"=>$fail);
    			$result = array('code'=>1, 'msg'=>'操作成功','data'=>$redata);
    		}
    		echo json_encode($result);
    	}catch (Exception $e) {
    		$mail = new MailManager();
    		$mail->sendCommMail('linglingqi', '报警', $e->getMessage());
    		echo json_encode(array("code"=>0, "msg"=>"分配失败"));
    	}
    }

    public function actionRoleList($depart)
    {
        $db = Yii::app()->sdb_eel;
        $username = yii::app()->user->username;
        $sid = $this->auth->getSid(yii::app()->user->username);
        $departname = NewCommonManager::getDepartRole();
        $speedUser = $this->common->getUser($username);
        if($depart=='other')
        {
            $depart = 'other';
        }
        else
        {
            $depart = $departname[$depart];
        }

        $auth = yii::app()->authManager;
        $data = array();
        if($depart=='other')
        {
            $sql = "select name from developer_AuthItemChild, developer_AuthItem where type = 2 ";
            $results = $db->createCommand($sql)->queryAll();
        }
        else
        {
            $sql = "select da.name from developer_AuthItem da,developer_AuthItemChild dc where dc.child=da.name and da.type = 2 and dc.parent='$depart' " ;
            $results = $db->createCommand($sql)->queryAll();
        }

        foreach ($results as $key => $value) {

            $results[$key]['name'] = substr($value['name'],strpos($value['name'],"/")+1);

        }
        echo json_encode($results);


    }

    public function actionshowtasklist() {

        $db = Yii::app()->sdb_eel;
        $departname = NewCommonManager::getDepartRole();
        $depart = $departname[$_POST['departid']];
        $rolename = $_POST['rolename'];
        $sid = $this->auth->getSid(yii::app()->user->username);
        $auth = yii::app()->authManager;
        if(!empty($depart)) {

            $rolename = $depart."/".$_POST['rolename'];
        }
        if($auth->checkAccess('super',$sid)) {

            $sql = "select a.name from developer_AuthItem a, developer_function b  where a.type=1 and b.item=a.name and b.status!= ".FunctionManager::FUN_STATUS_DEL;
            $all = $db->createCommand($sql)->queryColumn();
            $sql = "select child from developer_AuthItemChild a, developer_function b where a.parent ='$rolename' and b.item=a.child and b.status!= ".FunctionManager::FUN_STATUS_DEL;
            $selected = $db->createCommand($sql)->queryColumn();
            $redata['select'] = array_values(array_diff($all,$selected));
            $redata['selected'] = $selected;
            echo json_encode($redata);

        }else {

            $sql = "select child from developer_AuthItemChild,developer_AuthItem,developer_function where developer_AuthItemChild.child=developer_AuthItem.name
            and developer_AuthItem.type=1 and developer_function.item=developer_AuthItemChild.child and developer_function.type=0 and developer_function.status!= ".FunctionManager::FUN_STATUS_DEL
            ." and developer_AuthItemChild.parent= '$depart' ";
            $all = $db->createCommand($sql)->queryColumn();
            $selected = array();
            foreach ($all as $key => $value) {
                if($auth->hasItemChild($rolename,$value)) {
                    $selected [] = $value;
                }
            }
            $redata['select'] = array_values(array_diff($all,array_values($selected)));
            $redata['selected'] = $selected;
            echo json_encode($redata);

        }
    }

    public function actionTest($point,$user){
        $access=Yii::app()->getAuthManager()->checkAccess($point,$user,$params);
    }
}
