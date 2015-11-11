<?php

Class FunctionController extends Controller {

    function UpdateAction($id)
    {
        $db = yii::app()->db_eel;
        $sql = "select id,action from developer_action where functionid=".$id;
        $results = $db->createCommand($sql)->queryAll();
        rsort($results);
        echo json_encode($results);
    }

    public function actionIndex($business)
    {
        $where = "";
        if (isset($_GET["business"])) {
            // $name = $_GET['name'];
            $where = " where business = '$business'";
        }

        $db = yii::app()->db_eel;
        $sql = "select * from developer_function". $where;
        $results = $db->createCommand($sql)->queryAll();
        //var_dump($results);
        $this->render("function/index.tpl", array("business"=>$business));
    }

    public function actionGetFunctions($business='',$funname='') {

    	if (!$business) {
    		echo json_encode(array('code'=>0,"msg"=>"请输入业务号"));
    		exit;
    	}
    	$bus = new BusinessManager();
    	$allbusness = $bus->getAllBusinessId();
    	if (!in_array($business, $allbusness)) {
    		echo json_encode(array('code'=>0,"msg"=>"业务号不正确"));
    		exit;
    	}

    	$db = yii::app()->db_eel;
	    $sql = "select * from developer_function where business = '$business' and `status` !=". FunctionManager::FUN_STATUS_DEL;
	    if($funname) {
	    	$where = " and funname like '%$funname%' ";
	    }
		$where && $sql .= $where;
    	$total = $db->createCommand($sql)->queryAll();
    	$total = count($total);

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : $total;
        $offset = ($page-1)*$rows;
        $where = "";
        if ($business) {
            $where = " where `status` !=". FunctionManager::FUN_STATUS_DEL ." and business = '$business' limit $offset,$rows ";
            if($funname) {
                $where = " where `status` !=". FunctionManager::FUN_STATUS_DEL ." and business = '$business' and funname like '%".$funname."%' limit $offset,$rows ";
            }
        }

        $sql = "select * from developer_function". $where;
        $results = $db->createCommand($sql)->queryAll();
        foreach ($results as $key => $value) {
            $results[$key]['option']=$value['status'];
        }
        $results = array("rows"=>$results,"total"=>$total);
        echo json_encode($results);

    }

    public function actionAdd() {
        $desc = $msg = $results = "";

        $user = Yii::app()->user;
        $username = $user->name;
        $uid = $user->id;
        $mailname = $user->username;

        if(empty($_POST['business'])) {
           $msg .= "项目不能为空";
           echo json_encode(array("code"=>0,"msg"=>$msg));
           exit;
        } else {
            $business = trim($_POST['business']);
        }

        if(empty($_POST['funname'][0])) {
           $msg .= "功能名称不能为空";
           echo json_encode(array("code"=>0,"msg"=>$msg));
           exit;
        } else {
        	$funname = trim($_POST['funname']);
        }
        if(empty($_POST['description']))  {
        	$msg .= "请填入功能描述";
        	echo json_encode(array("code"=>0,"msg"=>$msg));
        	exit;
        }else {
        	$desc = trim($_POST['description']);
        	$desc .= ";操作人：". $username;
        }
		$url = isset($_POST['url']) ? trim($_POST['url']) : '';
		$sign = isset($_POST['sign']) ? trim($_POST['sign']) : '';
        if(!empty($business)&&!empty($funname)&&!empty($_POST['url'])&&!empty($_POST['sign'])) {
            $db = yii::app()->db_eel;
            $point = $business."/".$funname;

            $fun = new FunctionManager();
            try {
            	$functionid = $fun->Insert($business, $funname, $point, $url, $desc);
            }catch (Exception $e) {
				Json::fail($e->getMessage());
            }

            $msg = "添加成功";
            // 创建权限Task
            $auth = Yii::app()->authManager;
            $lauth = new AuthManager();
            if(!$auth->getAuthItem($point)){
                $task = $auth->createTask($point,$desc);
            }
            if ($business == 'works') {
                $action = $business."/action/work_space/".$sign;
            } else {
    			$action = $business."/action/".$sign;
    		}
    		if(!$auth->getAuthItem($action)) {
    			$auth->createOperation($action);
    		}
    		if(!$auth->hasItemChild($point,$action)){
    			$auth->addItemChild($point,$action);
    		}
    		try{
    			$auth->assign($point,$uid);
    			SyslogManager::Write($mailname, SyslogManager::FUNCTION_ASSIGN_USER, 'system', $point);
    		}catch (Exception $e) {

    		}

    		$action_manage = new ActionManager();
    		$action_manage->addAction($functionid[0],$action, $username);
            $results = array("code"=>1, "msg"=>$msg,"functionid"=>$functionid[0]);

        } else {
            $results = array("code"=>0,"msg"=>"项目，功能，url,sign都不能为空！");
        }
        echo json_encode($results);
    }


    public function actionUpdata() {

        $desc = $msg = "";
        $user = Yii::app()->user;
        $username = $user->name;
        if(!empty($_POST['description'])) {
          $desc = trim($_POST['description']);
        }
        $business = trim($_POST['business']);
        $funname = trim($_POST['funname']);
        if(!empty($business)&&!empty($funname)) {
        	$fun = new FunctionManager();
            try {

            	$funtions = $fun->getFunction($_POST['id']);
            	$point = $funtions[0]['item'];
            	$old_url = $funtions[0]['url'];

            	$ret = $fun->updateFunction($_POST['id'], $business, $funname, trim($_POST['url']), trim($_POST['sign']), $desc);
            	SyslogManager::Write($username, SyslogManager::FUNCTION_UPDATA,$funname, $funname);

            	//获取权限点；2:更改权限点下的自权限点url
            	if ($business == "works") {
            		$action = $business."/action/work_space".$_POST['url'];
            	} else {
            		$action = $business."/action".$_POST['url'];
            	}
            	$auth = Yii::app()->authManager;
            	if(!$auth->getAuthItem($point)) {
            		$auth->createOperation($point);
            	}
            	if(!$auth->getAuthItem($action)) {
            		$auth->createOperation($action);
            	}
            	if(!$auth->hasItemChild($point,$action)){
            		$auth->addItemChild($point,$action);
            	}
            	$act = new ActionManager();
            	$act->addAction($_POST['id'], $action, $username);

            }catch(Exception $e){
				$msg = $e->getmessage();
				echo json_encode(array('code'=>0, 'msg'=>$msg));
				exit;
			}
			$msg = "更新成功";
			$result = array('code'=>1, 'msg'=>$msg);
        } else {
            $msg = "更新失败";
            $result = array('code'=>0, 'msg'=>$msg);
        }
        echo json_encode($result);
    }

    public function actionDelete() {

        $db = yii::app()->db_eel;
        $user = yii::app()->user;
        $sid = $user->id;
        $username = $user->username;
        $auth = Yii::app()->authManager;

        $sql = "select item, funname, business from developer_function where id = ".$_POST["id"];
        $item_info = $db->createCommand($sql)->queryAll();
        $item = $item_info[0]['item'];
        $funname = $item_info[0]['funname'];
        $business = $item_info[0]['business'];
        $sql = "select itemname, userid from developer_AuthAssignment where itemname='$item'";
        $results = $db->createCommand($sql)->queryAll();
        if(!empty($results)) {
        	foreach($results as $rval) {
        		$auth->revoke($item,$rval['userid']);
        	}
        }

        $auth->removeAuthItem($item);

        //取消收藏
        $collect = new CollectManager();
        try {
        	$collect->deleteCollect($business, $funname);
        } catch(Exception $e) {

        }

        //取消菜单
        $menu = new MenuManager();
        $menu->deleteMenu($_POST["id"]);

        $sql = "delete from developer_function where id = ".$_POST["id"];
        $db->createCommand($sql)->execute();
        //发邮件通知相关人员功能下线
        $notice = $this->auth->getAccessUserByFunction($_POST["id"]);
        $ext['to'] = "quanxian@meilishuo.com";
        if ($notice) {
        	$mail = new MailManager();
        	$time = date('Ymd H:i:s');
        	$content = "功能{$funname} 在{$time}被用户{$username}下线，请知晓～";
        	$mail->sendCommMail($notice, '功能下线通知', $content, $ext);
        }

        Json::succ('删除成功');

    }

    public function actionAddAction()
    {

        $auth = yii::app()->authManager;
        $action = "";
        $db = yii::app()->db_eel;
        $transaction=$db->beginTransaction();
        try {
        	if(!empty($_POST['action'])&&!empty($_POST['controller']))
        	{
        		$action = $_POST['business']."/action/".strtolower($_POST['controller'])."/".strtolower($_POST['action']);
        		$sql = "select * from developer_action where functionid=".$_POST['id']." and action='".$action."'";
        		$results = $db->createCommand($sql)->queryAll();
        		if(empty($results))
        		{
        			$sql = "select business,item from developer_function where id=".$_POST['id'];
        			$item = $db->createCommand($sql)->queryAll();
        			$item = $item[0];
        			$item = $item['item'];
        			$sql = "select * from developer_AuthItem where name ='".$item."'";
        			$flag = $db->createCommand($sql)->queryAll();
        			if(empty($flag))
        			{
        				$auth->createTask($item);
        			}
        			$sql = "select * from developer_AuthItem where name = '".$action."'";
        			$flag = $db->createCommand($sql)->queryAll();
        			if(empty($flag))
        			{
        				$auth->createOperation($action);
        			}
        			$sql = "select * from developer_AuthItemChild where parent ='$item' and child = '$action' ";
        			$flag = $db->createCommand($sql)->queryAll();
        			if(empty($flag))
        			{
        				$auth->addItemChild($item,$action);
        			}
        			$sql = "insert into developer_action(functionid,action,unix,username) values(?,?,?,?)";
        			$db->createCommand($sql)->execute(array($_POST['id'],$action,date('Y-m-d H:i:s'),yii::app()->user->name));
        			$this->UpdateAction($_POST['id']);
        		}
        		else
        		{
        			$this->UpdateAction($_POST['id']);
        		}


        	}
        	else
        	{
        		$this->UpdateAction($_POST['id']);
        	}
        	$transaction->commit();
        }catch (Exception $e) {
			$mail = new MailManager();
    		$mail->sendCommMail('linglingqi', '报警', '添加action异常'.implode(',', $e));
			$transaction->rollBack();
			exit;
        }

    }



    public function actionUpdateAction() {

    	$this->UpdateAction($_POST['row']['id']);
    }

    public function actionDeleteAction() {

        $db = yii::app()->db_eel;
        $auth = yii::app()->authManager;
        $sql = "select action,item from developer_function,developer_action where developer_action.id=".$_POST['id']."
        and developer_action.functionid=developer_function.id";
        $results = $db->createCommand($sql)->queryAll();
        $parent = $results[0]['item'];
        $child = $results[0]['action'];
        $auth->removeItemChild($parent,$child);
        //判断子权限还有父权限不，没有则删除
		$has_sql = "select * from `developer_AuthItemChild` where child='$child'";
		$has_parent = $db->createCommand($has_sql)->queryAll();

		if (!$has_parent) {
			$auth->removeAuthItem($child);
		}
        $sql = "delete from developer_action  where id=".$_POST['id'];
        $db->createCommand($sql)->execute();
        echo json_encode("删除成功");
    }

    public function actionCheckSubmit($id) {
        $db = Yii::app()->db_eel;
        $sql = "update  developer_function set status=? where id =? ";
        $db->createCommand($sql)->execute(array(1,$id));
        //发邮件给风控人员提示进行审核
		$fun = new FunctionManager();
        $detail = $fun->getFunctionInfoById($id);
        $mail = new MailManager();
        $name = Yii::app()->user->name;
        $content = "您有 '{$name}' 的功能审核申请,功能名称为' ".$detail['funname'] ." '，请尽快处理～";
        $subject = "功能审核申请";
        $mail->sendCommMail($mail::FENGKONG_MAIL, $subject, $content);
        echo json_encode("提交成功");
    }

    /**
     * 根据url获取功能信息；返回信息包括名称、url、描述和创建时间、是否有添加菜单
     */
    public function actiongetFunnameByUrl($url='') {

    	$functions = array();
    	if ($url) {
    		$fun = new FunctionManager();
			$functions = $fun->getFunnameByUrl($url);
    	}

		$this->render("function/showFunctionByUrl.tpl", array("function"=>$functions, "url"=>$url));
    }

    /**
     * 获取功能的有权限的用户
     */
    public function actiongetFunctionsRight($business='',$funname='') {

    	$users = array();
    	$msg ='';
    	$total = 0;
    	if ($funname) {
    		$funname = trim($funname);
    		//获取功能的权限点；根据权限点判断是否是白名单功能；获取该功能的有权限用户；获取功能的开发者 *** 检查开发者用户和超级管理员
    		$fun = new FunctionManager();
    		$functions = $fun->getFunctionItem($business, $funname);

    		$all_items = $infos = $supers = $developers = array();
    		if ($functions) {
    			$item = new ItemManager();
				$parent = $child = array();
				$item->getAllParents($functions, $parent);

				$auth = new AuthManager();
				$comm = new CommonManager();
				$auth->getItemChild($item, $child);

				$all_items = array_merge($parent, $child, array($functions=>$functions));
				$infos = $auth->getItemAssigns($all_items);

				$supers = $auth->getSuper();
				//获取超级管理员；获取开发者
				$dev = $fun->getDevelopers($business);
				foreach($dev as $dkey=>$dval) {
					$developers[$dkey]['username'] = $dval;
					$developers[$dkey]['itemname'] = '开发者';
					$developers[$dkey]['userid'] = $auth->getSid($dval);
				}
				$is_strick = $auth->strickFunction($functions);
				if (!$is_strick) {
					$infos = array_merge($infos, $supers, $developers);
				}
				if ($infos) {
					foreach($infos as $ival) {
						$tmp_name = '';
						$tmp_name = $comm->getUserById($ival['userid']);
						if (!isset($ival['username'])) {

							if ($tmp_name && $ival['userid']) {
								 $users[$ival['userid']] = $ival;
								 $users[$ival['userid']]['name'] = $tmp_name['name'];
								 $users[$ival['userid']]['depart'] = $tmp_name['depart'];
								 $users[$ival['userid']]['funname'] = $funname;
							}
						} else {
							if (isset($ival['userid']) && isset($ival['username'])) {
								$users[$ival['userid']] = $ival;
								$users[$ival['userid']]['name'] = $tmp_name['name'];
								$users[$ival['userid']]['depart'] = $tmp_name['depart'];
								$users[$ival['userid']]['funname'] = $funname;
							}
						}
					}
					$total = count($users);
					if ($_GET['excel']) {
						$fun->export('功能有权限用户', $users);
						exit;
					}
				} else {
					$msg = '功能没有给用户分配权限';
				}
    		} else {
				$msg = '功能不存在';
    		}
    	} else {
    		$users = array();
    		$msg = '没有数据';
    	}

    	$this->render("tools/showFunctionUser.tpl", array("total"=>$total,"users"=>$users, "funname"=>$funname, 'business'=>$business, 'msg'=>$msg));
    }

}