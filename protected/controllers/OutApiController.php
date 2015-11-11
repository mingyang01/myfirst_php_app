<?php

class OutApiController extends Controller
{

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array("Delfunction","CheckGroupNew","UpdateFunctionItem","GetPoint","FunctionList","Updatefunction","CheckGroup","CheckStatus","AutoAuth","AutoItem","AutoVerify","DivideAuth","RoleUserAdd","ChangeName","IsAnalyst",'GetItemName'),
                'users'=>array('?'),
            ),

            array('deny',
                'users'=>array('?'),
            ),
        );
    }
    //批量检测权限
    public function actionCheckGroup()
    {
    	$where = '';
    	$params = '';
    	$business = $_POST['business']?$_POST['business']:'';
    	$uid = $_POST['uid']?$_POST['uid']:NULL;
    	$points = $_POST['points']?$_POST['points']:array();
    	if($params)
    		@$params = json_decode($params, true);
    	if($business)
    	{
    		$where =  " where business = '$business' ";
    	}
    	$db = yii::app()->db_eel;
    	$sql = "select item, business, funname from developer_function".$where;
    	$results = $db->createCommand($sql)->queryAll();
    	$reArray = array();
    	$authResult = array();
    	$reList = array();
    	$auth = yii::app()->authManager;
    	foreach ($results as $key => $value) {
    		$reArray[strtolower($value['funname'])] = $value['item'];
    	}
    	foreach ($points as $key => $value) {
    		$point = '';
    		$val = strtolower($value);
    		if(isset($reArray[$val]))
    		{
    			$point = $reArray[$val];
    			$authResult = array("code"=>1, "message"=>"", "data"=>array());
    			$authResult['data']['checked'] = $this->auth->checkAccess($point, $uid,'true');
    			$reList []= array("function"=>$value,"status"=>$authResult);
    		}
    		else
    		{
    			$authResult = array("code"=>0, "message"=>"have no this function", "data"=>array());
    			$reList [] = array("function"=>$value,"status"=>$authResult);
    		}
    	}

    	echo json_encode($reList);

    }


    //批量检测权限
    public function actionCheckGroupNew() {

    	$business = $_POST['business'];
    	$uid = $_POST['uid'];
    	$points = $_POST['points'];

    	if(empty($uid)) {
    		echo json_encode(array("code"=>0, "message"=>"args error:uid is empty", "data"=>array()));
    		exit;
    	}
    	if (empty($business)) {
    		echo json_encode(array("code"=>0, "message"=>"args error:business is empty", "data"=>array()));
    		exit;
    	}

    	if (empty($points)) {
    		echo json_encode(array("code"=>0, "message"=>"args error:ponits is empty", "data"=>array()));
    		exit;
    	}

    	$auth = new AuthManager();
    	$fun = new FunctionManager();
    	if ($auth->isSupper($uid) || $fun->isDeveloper($business, NewCommonManager::getUsernameByuid($uid))) {
			$haveItem = $fun->getFunctionList($business);
			foreach ($haveItem['rows'] as $key => $hvalue) {
				if ($hvalue['funname'] && $hvalue['item']) {
					$reArray[strtolower($hvalue['funname'])] = $hvalue['item'];
				}
			}
			foreach ($points as $key => $value) {
				$point = '';
				$val = strtolower($value);
				if(isset($reArray[$val])) {
					$point = $reArray[$val];
					$authResult = array("code"=>1, "message"=>"", "data"=>array());
					$authResult['data']['checked'] = true;
					$reList []= array("function"=>$value,"status"=>$authResult);
				} else {
					$authResult = array("code"=>0, "message"=>"have no acess", "data"=>array());
					$authResult['data']['checked'] = false;
					$reList [] = array("function"=>$value,"status"=>$authResult);
				}
			}
    	} else {
    		//普通用户；获取用户的权限点；获取
    		$haveItem = $auth->getNewAuthFunction($uid);
    		foreach ($haveItem as $hkey => $hvalue) {
				if (strpos($hvalue, $business) === false) {
					unset($haveItem[$hkey]);
				}
    		}
    		$function = $fun->getfunctionByItems($haveItem);
    		foreach ($function as $fvalue) {
    			$reArray[strtolower($fvalue['funname'])] = $fvalue['item'];
    		}

    		foreach ($points as $key => $value) {
    			$point = '';
    			$val = strtolower($value);
    			if(isset($reArray[$val])) {
    				$point = $reArray[$val];
    				$authResult = array("code"=>1, "message"=>"", "data"=>array());
    				$authResult['data']['checked'] = true;
    				$reList []= array("function"=>$value,"status"=>$authResult);
    			} else {
    				$authResult = array("code"=>0, "message"=>"have no acess", "data"=>array());
    				$authResult['data']['checked'] = false;
    				$reList [] = array("function"=>$value,"status"=>$authResult);
    			}
    		}

    	}
    	echo json_encode($reList);
    }



    //自动分配权限
    public function actionAutoAuth()
    {

        $Array = $_POST['id'];
        $auth = yii::app()->authManager;
        $db = yii::app()->db_eel;
        $returnList = array();
        foreach ($Array as $key => $value) {
            $sql = "select item ,business,id from developer_function where funname='$value' ";
            $results = $db->createCommand($sql)->queryAll();
            $task = '';
            $data = array();
            if(!empty($results))
            {
                $item = $results[0]['item'];
                if(!$auth->getAuthItem($item))
                {
                    $task = $auth->createTask($item);
                    $message = 'create function item  success!';
                    $returnList [] =array('code'=>'1',"id"=>$value,"message"=>$message);
                }
                else
                {
                    $message = 'there is this function item already';
                    $returnList [] =array('code'=>'0',"id"=>$value,"message"=>$message);
                }
            }
            else
            {
                $message = "have no this function ";
                $returnList [] =array('code'=>'0',"id"=>$value,"message"=>$message);
            }
        }

        echo json_encode($returnList);

    }
    //自动创建权限点
    public function actionAutoItem() {

        $desc = "";
        $msg = "";
        $results = "";
        if(empty($_POST['business'])) {
           $msg .= "项目不能为空";
        } else {
            $business = $_POST['business'];
        }

        if(empty($_POST['funname'][0])) {
           $msg .= "   功能名称不能为空";
        }
        if(!empty($_POST['description'])) {
          $desc = $_POST['description'];
        } else {
        	$desc = 'data平台创建功能';
        }

        if(!empty($_POST['business'])&&!empty($_POST['funname'])&&!empty($_POST['url'])&&!empty($_POST['sign'])) {

            $db = yii::app()->db_eel;
            $point = $business."/".$_POST['funname'];

            try {
            	$fun = new FunctionManager();
            	$functionid = $fun->Insert($business, $_POST['funname'], $point, $_POST['url'], $desc);

            	// 创建权限Task
            	$auth = Yii::app()->authManager;
            	if(!$auth->getAuthItem($point)) {
            		$task = $auth->createTask($point,$desc);
            	}
            	if ($business == "works") {
            		$action = $business."/action/work_space".$_POST['url'];
            	} else {
            		$action = $business."/action".$_POST['url'];
            	}
            	if(!$auth->getAuthItem($action)) {
            		$auth->createOperation($action);
            	}
            	if(!$auth->hasItemChild($point,$action)) {
            		$auth->addItemChild($point,$action);
            	}

            	$action_manage = new ActionManager();
            	$action_manage->addAction($functionid[0], $action, "接口添加");
            	$results = array('code'=>'1',"msg"=>'操作成功',"functionid"=>$functionid[0]);
            } catch (Exception $e) {
            	$msg = $e->getMessage();
            	$results = array('code'=>'0',"msg"=>$msg,"functionid"=>$functionid[0]);
            }


        }else {
            $results = array("code"=>'0',"msg"=>"项目，功能，url,sign都不能为空！");
        }
        echo json_encode($results);
    }
    //自动提交审核
    public function actionAutoVerify()
    {
        $msg = '';
        $business = isset($_POST['business'])?$_POST['business']:'';
        $funname = isset($_POST['funname'])?$_POST['funname']:'';
        $db = yii::app()->db_eel;
        $data = array();
        if(!empty($business)&&!empty($funname))
        {
            $sql = "select * from developer_function where business = '$business' and funname = '$funname' ";
            $results = $db->createCommand($sql)->queryAll();
            if(!empty($results))
            {
                $id = $results[0]['id'];
                $sql = "update  developer_function set status=? where id =? ";
                $db->createCommand($sql)->execute(array(1,$id));
                $msg = 'submit item success!';
                $data = array('code'=>'1','message'=>$msg);
            }
            else
            {
                $msg = " hava no this function in this business";
                $data = array('code'=>'0','message'=>$msg);
            }
        }
        else
        {
            $msg = 'business and function must be exist!';
            $data = array('code'=>'0','message'=>$msg);
        }

        echo json_encode($data);
    }
    //分配权限
    public function actionDivideAuth()
    {
        $business = isset($_POST['business'])?$_POST['business']:'';
        $funname = isset($_POST['funname'])?$_POST['funname']:'';
        $userArray = isset($_POST['userArray'])?$_POST['userArray']:'';
        $auth = yii::app()->authManager;
        $successArray = array();
        $failArray = array();
        $point = '';
        $item = $this->function->getFunctionItem($business,$funname);
        $nameArray = $this->common->getSpeedId();
        if($auth->getAuthItem($item))
        {
            foreach ($userArray as $key => $value) {
                $flag = $this->auth->getId($value);
                if(!empty($flag))
                {

                    $uid = $flag;
                    if(!$this->auth->checkAccess($item,$uid,'true'))
                    {
                        $auth->assign($item,$uid);
                        $message = array("code"=>'1','message'=>"Divide Auth success!");
                        array_push($successArray, $message);
                    }
                    else
                    {

                        $message = array("code"=>'0','message'=>"this peaple have the auth already!","name"=>$value,"funname"=>$funname);
                        array_push($failArray, $message);

                    }
                }
                else
                {
                    $message = array("code"=>'0','message'=>"can't find this person's speedid!","name"=>$value);
                    array_push($failArray, $message);
                }
            }
        }
        else
        {
            $message = array("code"=>'0','message'=>"have no this '$funname' in '$business' !","name"=>$funname);
            array_push($failArray, $message);
        }

        $redata = array('success'=>$successArray,"fail"=>$failArray);
        echo json_encode($redata);
    }
    //检测功能状态
    public function actionCheckStatus()
    {
        $business = isset($_POST['business'])?$_POST['business']:'';
        $funname = isset($_POST['funname'])?$_POST['funname']:array();
        if($business&&count($funname)>0)
        {
            foreach ($funname as $key => $value) {
                $status = $this->function->checkStatus($business,$value);
                if(isset($status)&&$status!='')
                {
                    $result []= array("code"=>1,"function"=>$value,"message"=>"0--未提交审核，1--待审核，2--已审核","status"=>$status);
                }
                else
                {
                    $result []= array("code"=>0,"message"=>"Have no $business or $value");
                }
            }
        }
        else
        {
            $result = array("code"=>0,"message"=>'Business or funname can not be empty');
        }

        echo json_encode($result);
    }
    //更改功能名称
    public function actionChangeName($business = '',$funname = '',$newName = '')
    {
        $varReturn = array();
        if($business&&$funname&&$newName)
        {
            $db = yii::app()->db_eel;
            $sql = "select * from developer_function where business = '$business' and funname = '$funname' ";
            $results = $db->createCommand($sql)->queryAll();
            if($results)
            {
                $sql = "select * from developer_function where business = '$business' and funname = '$newName' ";
                $flag = $db->createCommand($sql)->queryAll();
                if(empty($flag))
                {
                    $sql = "update developer_function set funname= ? where business=? and funname=? ";
                    $db->createCommand($sql)->execute(array($newName,$business,$funname));
                    $varReturn = array("code"=>1,"message"=>"Update success!");
                }
                else
                {
                    $varReturn = array("code"=>0,"message"=>" There is '$newName' already! ");
                }

            }
            else
            {
                $varReturn = array("code"=>0,"message"=>"Hove no this '$funname' in business '$business' ");
            }

        }
        else
        {
            $varReturn = array("code"=>0,"message"=>"Business and funname and newName must be given!");
        }
        echo json_encode($varReturn);
    }
    //角色组添加成员
    public function actionRoleUserAdd()
    {
        $selected = $_POST['selected'];
        //if(!is_array($selected)) {
          //  $selected = array();
        //}
        $role = $_POST['role'];
        $depart =$_POST['departid'];
        $departArray = NewCommonManager::getDepartRole();
        $depart = $departArray[$depart];
        $role = $depart."/".$role;
        $auth = Yii::app()->authManager;
        $count = 0;
        $fail = array();
        $success = array();
        $redata = array();
        $results = array();
        foreach ($selected as $key => $value) {
            $redata[$value] = $this->auth->getId($value);
        }

        foreach ($redata as $key => $value) {
            if(!empty($value))
            {
                $flag = $auth->getAuthAssignment($role,$value);
                if($flag==NULL)
                {
                    $auth->assign($role,$value);
                    $count += 1;
                    $success [] = array('code'=>1,'message'=>"success '$value' insert '$role' ");
                }
                else
                {
                    $fail[] = array('code'=>0,'message'=>" There is '$key' in '$role' already! ");
                }

            }
            else
            {
                $fail[] = array('code'=>0,'message'=>" '$key' is not found in speed ");
            }
        }
        $results = array('success'=>$success,'fail'=>$fail);
        echo json_encode($results);
    }

    /**
     * 兼容speed接口异常问题
     */
    public function actionIsAnalyst($username, $uid='') {

    	if (!$uid) {
    		$sid = $this->auth->getId($username);
    	} else {
    		$sid = $uid;
    	}

        $auth = yii::app()->authManager;

        if(!empty($sid))
        {
            $roles = $auth->getAuthItems('2',$sid);
            $reDate = array();
            foreach ($roles as $key => $value) {
                $reDate[] = $key;
            }
            $flag = in_array('技术部-数据智能/data平台-分析师', $reDate);
            $results = array('code'=>1,'checkStatus'=>$flag);
        }
        else
        {
            $results = array('code'=>0,'message'=>"there is not this '$username' in speed ");
        }

        echo json_encode($results);
    }

    /**
     * url不唯一，不能获取唯一可用的权限名，提供需谨慎
     */
    public function actionGetItemName($url)
    {
        $urlArray = $this->function->urlToFunctionName();
        $results = array();
        if(isset($urlArray[$url]))
        {
            $results = array('code'=>1,'functionName'=>$urlArray[$url]);
        }
        else
        {
            $results = array('code'=>0,'message'=>" $url may be not exist! ");
        }
        echo json_encode($results);
    }

    /**
     * 修改功能信息；提供给data平台使用
     */
    public function actionUpdatefunction() {
    	$desc =  $msg = $url = $sign = $funname = "";
    	if (empty($_POST['business'])) {
    		echo json_encode(array('code'=>0, 'msg'=>'业务名不能为空'));
    		exit;
    	}
    	if (!empty($_POST['funname'])) {
    		$funname = $_POST['funname'];
    	}
    	if(!empty($_POST['url'])) {
    		$url = $_POST['url'];
    	}
    	if(!empty($_POST['sign'])) {
    		$sign = $_POST['sign'];
    	}

    	if(!empty($_POST['description'])) {
    		$desc = $_POST['description'];
    	}else {
    		$desc = '接口修改';
    	}
    	if(!empty($_POST['business'])) {
    		$fun = new FunctionManager();
    		try {
    			$ret = $fun->updateFunction($_POST['id'], $_POST['business'], $funname, $url, $sign, $desc);
    			SyslogManager::Write('data', SyslogManager::FUNCTION_UPDATA, $url, $funname);
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
    /**
     * 获取业务下的功能列表； 提供给data平台使用
     */
    public function actionFunctionList($business="", $funname="") {
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
    	$fun = new FunctionManager();
    	$results = $fun->getFunctionList($business, $funname);
    	echo json_encode($results);
    }

    /**
     * 根据业务号和功能名获取权限点；权限点唯一
     */
    public function actionGetPoint() {

    	$business = $_POST['business'];
    	$function = $_POST['function'];
    	if (!$business) {
    		echo json_encode(array('code'=>0,"msg"=>"没有输入业务号，请检查"));
    		exit;
    	}
    	if (!$function) {
    		echo json_encode(array('code'=>0,"msg"=>"没有输入功能名，请检查"));
    		exit;
    	}
    	$fun = new FunctionManager();
    	$functions = $fun->getFunctions($business);
    	if (empty($functions)) {
    		echo json_encode(array('code'=>0,"msg"=>"请检查business对应的业务是否存在！"));
    		exit;
    	}
    	$item = '';
    	foreach ($functions as $val) {
    		if (strtolower(trim($val['name'])) == strtolower(trim($function))) {

    			$item = $val['item'];
    		}
    	}
    	if (!$item) {
    		echo json_encode(array('code'=>0,"msg"=>"功能不存在，请检查！"));
    		exit;
    	}
    	echo json_encode(array('code'=>1,"msg"=>"成功", "item"=>$item));
    	exit;
    }

    /**
     * 修改功能的url和权限点
     */
    public function actionUpdateFunctionItem($business='',$funname='',$url='',$sign='') {

    	$desc = "";
    	$msg = "";
    	$results = "";
    	if(empty($business)) {
    		$msg .= "项目不能为空";
    	}

    	if(empty($funname)) {
    		$msg .= "   功能名称不能为空";
    	}
    	if (empty($url)) {
    		$msg .= "URL不能为空";
    	}
    	if (empty($sign)) {
    		$msg .= "msg不能为空";
    	}

    	if(!empty($business)&&!empty($funname)&&!empty($url)&&!empty($sign)) {
    		$db = yii::app()->db_eel;
    		$sql = "select id, item from developer_function where funname = '$funname' and business='$business'";
    		$flag = $db->createCommand($sql)->queryRow();
    		if(empty($flag)) {
    			$msg = "功能不存在，请检查！";
    			$results = array('code'=>'0',"msg"=>$msg,"functionid"=>$flag['id']);
    		} else{
    			try{
    				$db = yii::app()->db_eel;
    				$sql = "update  developer_function set url=?, sign=? where id=?";
    				$results = $db->createCommand($sql)->execute(array($url, $sign, $flag['id'] ));
    				SyslogManager::Write('data', SyslogManager::FUNCTION_UPDATA,$url, $funname);
    				$point = $flag['item'];
    				// 创建权限Task
    				$auth = Yii::app()->authManager;
    				if(!$auth->getAuthItem($point)) {
    					$task = $auth->createTask($point,$desc);
    				}
    				$action = $business."/action".$url;
    				if(!$auth->getAuthItem($action)) {
    					$auth->createOperation($action);
    				}
    				if(!$auth->hasItemChild($point,$action)){
    					$auth->addItemChild($point,$action);
    				}
    				$sql = "insert into developer_action(functionid,action,unix,username) values(?,?,?,?)";
    				$db->createCommand($sql)->execute(array($flag['id'],$action,date('Y-m-d H:i:s'),"接口添加"));
    			} catch (Exception $e) {
    				$results = array("code"=>'0',"msg"=>$e->getMessage());
    			}

    			$results = array('code'=>'1',"msg"=>$msg,"functionid"=>$flag['id']);
    		}
    	} else {
    		$results = array("code"=>'0',"msg"=>"项目，功能，url,sign都不能为空！");
    	}
    	echo json_encode($results);
    }

    /**
     * 根据业务号和功能名称删除功能
     */
    public function actionDelfunction($business='', $funname='', $user='') {

    	if (!$business || !$funname || !$user) {
    		Json::fail('请求参数有误');
    	}

    	$fun = new FunctionManager();
    	try {

    		$function = $fun->getFunctionInfo($business, $funname);
    		$id = $function['id'];
    		$fun->DelFunctionByID($id);

    		//发邮件通知相关人员功能下线
    		$notice = $this->auth->getAccessUserByFunction($id);
    		$ext['to'] = "quanxian@meilishuo.com";
    		if ($notice) {
    			$mail = new MailManager();
    			$time = date('Ymd H:i:s');
    			$content = "功能{$funname} 在{$time}被用户{$user}下线，请知晓～";
    			$mail->sendCommMail($notice, '功能下线通知', $content, $ext);
    		}
    	} catch (Exception $e) {

    		Json::fail('功能删除失败，请重试'.$e->getMessage());
    	}
    	Json::succ('功能删除成功');
    }

}
