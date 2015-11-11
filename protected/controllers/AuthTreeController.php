<?php

class AuthTreeController extends Controller {

	public function actionIndex($role = '',$depart = '', $business = '', $pre='') {
        $username = yii::app()->user->username;
        $speedUser = yii::app()->user;
        $db = Yii::app()->sdb_eel;

        $new_role = $pre .'/'. $role;
        //项目取部门下面的项目
        $depart_name = NewCommonManager::getDepartRoleByDid($depart);

        $m_role = new RoleManager();
        $results = $m_role->departBusiness($depart_name);
        foreach ($results as $key => $value) {
        	$project[$value['business']]['cname'] = $value['cname'];
        	$project[$value['business']]['business'] = $value['business'];
        }
		$menu = new MenuTreeManager();
		if (!$business) {
			$b_tmp = current($project);
			$business = $b_tmp['business'];
		}
		$menuTree = $menu->getRoleTree($business, $new_role);

		$data = array('menuTree'=>$menuTree,'departid'=>$depart,'role'=>$role, "business"=>$business,"project"=>$project, "pre"=>$pre);

        $this->render("auth/role-auth.tpl", $data);
	}

    public function actionUserAuthShow($user = '',$business = '') {

        $username = yii::app()->user->username;
        $speedUser = yii::app()->user;
        $db = Yii::app()->sdb_eel;
        $userid = $this->auth->getId($user);


        $menu = new MenuTreeManager();
        if ($business == "data平台") {
        	$menuTree = $menu->getDataMenuTree($userid);;
        } else {
        	$menuTree = $menu->getMenuTree($business,$userid);
        }
        $project = $this->publish->getProjectWithUserFilter($username);
        $this->render("auth/user-auth.tpl",array('menuTree'=>$menuTree,"user"=>$user,"business"=>$business,"project"=>$project));
    }

    public function actionAddUserAuth() {

    	$items = $_POST['items'];
    	$user = $_POST['user'];
    	$business = $_POST['business'];
        $message = '';
        $current_user = Yii::app()->user->username;
		try {
			$item = new ItemManager();
			if($user) {
				$auth = yii::app()->authManager;
				$lauth = new AuthManager();
				$userid = $this->auth->getId($user);
				$hasItems = $this->auth->getAuthFunction($userid);
				$authItems = array();
				foreach ($hasItems as $key => $value) {
					$authItems [] = strtolower($value);
				}
				$userid = $this->auth->getId($user);
				$items = array_unique($items);
				foreach ($items as $key => $ival) {
					if($ival) {
						$itemname = $item->getItemById($ival);
						if(!in_array(strtolower($itemname),$authItems)) {
							if (!$lauth->checkAccess($itemname, $userid)) {
								$auth->assign($itemname,$userid);
								SyslogManager::Write($current_user, SyslogManager::FUNCTION_ASSIGN_USER, NewCommonManager::getUsernameByuid($userid), $itemname);
							}
						}
					}
				}
				//取消权限的逻辑处理

				$message .= '保存成功';
			} else {
				$message .= '请检查用户名';
			}
			echo json_encode($message);
		} catch(Exception $e) {
			$mail = new MailManager();
			$mail->sendCommMail('linglingqi', '报警', $e->getMessage());
			echo json_encode(array("code"=>0, "msg"=>"添加失败"));
		}
    }

    /**
     * 给角色赋予权限
     */
    public function actionAddRoleAuth() {

    	$items = array_unique($_POST['items']);
    	$role = $_POST['role'];
    	$business = $_POST['business'];

    	$item_names = array();
    	$item = new ItemManager();
    	foreach($items as $ival) {
			$item_names[$ival] = $item->getItemById($ival);
    	}

    	//先获取角色下的用户，然后对用户循环判断授权
    	$auth = new AuthManager();
    	$users = $auth->getRoleUsers($role);

    	foreach($users as $val) {
    		$hasItems = $auth->getAuthFunction($val);
			foreach($item_names as $itkey=>$itname) {

			}
    	}

    }

}