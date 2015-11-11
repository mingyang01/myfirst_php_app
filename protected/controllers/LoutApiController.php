<?php

class LoutApiController extends Controller
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
                'actions'=>array("FunctionList","Updatefunction","CheckGroup","CheckStatus","AutoAuth","AutoItem","AutoVerify","DivideAuth","RoleUserAdd","ChangeName","IsAnalyst",'GetItemName'),
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
    		$value = strtolower($value);
    		if(isset($reArray[$value]))
    		{
    			$point = $reArray[$value];
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
    public function actionAutoItem()
    {
        $desc = "";
        $msg = "";
        $results = "";
        if(empty($_POST['business'])) {
           $msg .= "项目不能为空";
        } else {
            $business = $_POST['business'];
        }

        if(empty($_POST['funname'][0]))
        {
           $msg .= "   功能名称不能为空";
        }
        if(!empty($_POST['description']))
        {
          $desc = $_POST['description'];
        }

        if(!empty($_POST['business'])&&!empty($_POST['funname'])&&!empty($_POST['url'])&&!empty($_POST['sign']))
        {
            $db = yii::app()->db_eel;
            $point = $business."/".$_POST['funname'];
            $sql = "select id from developer_function where item = '$point' ";
            $flag = $db->createCommand($sql)->queryColumn();
            if(!empty($flag))
            {
                $msg = "请不要重复添加功能名称";
                $results = array('code'=>'0',"msg"=>$msg,"functionid"=>$flag[0]);
            }
            else{
                $db = yii::app()->db_eel;
                $sql = "insert into  developer_function (business, funname, description,unix, url, sign, item) values (?,?,?,?,?,?,?)";
                $results = $db->createCommand($sql)->execute(array($business,$_POST['funname'],$desc,date('Y-m-d H:m:s'), $_POST['url'],
                    $_POST['sign'], $point));
                $msg = "添加成功";
                $sql = "select id from developer_function where item = '$point' ";
                $functionid = $db->createCommand($sql)->queryColumn();
                // 创建权限Task
                $auth = Yii::app()->authManager;
                if(!$auth->getAuthItem($point))
                {
                    $task = $auth->createTask($point,$desc);
                }
                $action = $business."/action/".$_POST['sign'];
                if(!$auth->getAuthItem($action))
                {
                    $auth->createOperation($action);
                }
                if(!$auth->hasItemChild($point,$action))
                {
                    $auth->addItemChild($point,$action);
                }
                $sql = "insert into developer_action(functionid,action,unix,username) values(?,?,?,?)";
                $db->createCommand($sql)->execute(array($functionid[0],$action,date('Y-m-d H:i:s'),"接口添加"));
                $results = array('code'=>'1',"msg"=>$msg,"functionid"=>$functionid[0]);
            }

        }
        else
        {
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

    public function actionIsAnalyst($username)
    {
        $sid = $this->auth->getId($username);
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
		$desc =  $msg = $url = $sign ="";
		if (empty($_POST['business'])) {
			echo json_encode(array('code'=>0, 'msg'=>'业务名不能为空'));
			exit;
		}
		if (empty($_POST['funname'])) {
			echo json_encode(array('code'=>0, 'msg'=>'功能名不能为空'));
			exit;
		}
		if(!empty($_POST['url'])) {
			$url = $_POST['url'];
		}
		if(!empty($_POST['sign'])) {
			$sign = $_POST['sign'];
		}

		if(!empty($_POST['description'])) {
			$desc = $_POST['description'];
		}
		if(!empty($_POST['business'])&&!empty($_POST['funname'])) {
			$fun = new FunctionManager();
			try {
				$ret = $fun->updateFunction($_POST['id'], $_POST['business'], $_POST['funname'], $url, $sign, $desc);
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
	public function actionFunctionList($business="", $function="") {
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
		$results = $fun->getFunctionList($business, $function);
		echo json_encode($results);
	}

}
