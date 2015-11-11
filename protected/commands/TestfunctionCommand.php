<?php
/**
 * linglingqi的导数据脚本文件
 * @author linglingqi
 *
 */
class TestfunctionCommand extends Command {
	///usr/local/bin/php /home/work/websites/developer/protected/yiic.php Testfunction
	public function main($args) {
		//获取部门下面的功能
// 		self::updateUrl();
// 		//修改 “交易产品组”下的子角色
// 		self::updateItem();

		self::getDepartUserAuth();

	}

	public function getDepartUserAuth() {

		$depart = '商家运营 -华东';//商家运营 -华南
		$auth = new AuthManager();
		$comm = new CommonManager();
		$fun = new FunctionManager();
		$departs = $auth->getDepartByName();
		$d_id = '';
		foreach ($departs as $key =>$val) {
			if ($depart == $val) {
				$d_id = $key;
			}
		}
		if (!$d_id) {
			echo "部门不存在";exit;
		}
		$depart_users = $comm->getDepartUsers($d_id);
		$users = array();
		$i = $j = 0;
		foreach($depart_users as $user) {
			$i++;
			$name = $user['name'];
			$functions = $auth->getNewAuthFunction($user['id']);
			if ($functions) {
				foreach($functions as $val) {
					$function = $fun->getFunctionByItem($val);
					if ($function) {
						$j++;
						$str = $depart ."\t". $function['business'] ."\t". $function['funname'] ."\t". $name;
						file_put_contents('/tmp/ll_0730_auth.txt', $str ."\r\n", FILE_APPEND);
					}
				}
			}

		}
var_Dump('共有用户：'.$i .";有记录：".$j);exit;
	}

	public function updateItem() {
		$file1 = "/home/work/websites/developer/protected/commands/commandfile/auth_function.txt";
		$data1 = file_get_contents($file1,"rw");
		$arr1 = explode("\n", $data1);
		$tasklist = array();
		foreach ($arr1 as $val) {
			$tasklist[] = 'data平台/'.trim($val);
		}

		$departid = 46;
		$db=yii::app()->db_eel;
		$auth = Yii::app()->authManager;
		$departname = $this->auth->getDepartByName();
		$depart = $departname[$departid];
		$rolename = '交易产品组';
		if(!empty($depart)) {
			$rolename = $depart."/".$rolename;
		}
		$sql = "select child from developer_AuthItemChild where parent ="."'".$rolename."'";
		$selected = $db->createCommand($sql)->queryColumn();
		$sql = "select name from developer_AuthItem";
		$all = $db->createCommand($sql)->queryColumn();
		$add = array_diff($tasklist, array_values($selected));
		$i = $j = 0;
		if(!empty($add)) {
			foreach ($add as $key => $value) {

				if(!$auth->getAuthItem($rolename)||!$auth->getAuthItem($value)) {
					$list[$rolename] = $value;
					$listArray [] = $list;
					$i ++;
					file_put_contents('/tmp/ll_fail.txt', $value ."\t没有该功能权限，操作失败 \r\n", FILE_APPEND);
				} else {
					$auth->addItemChild($rolename,$value);
					$list[$rolename] = $value;
					$listArray [] = $list;
					$j++;
					file_put_contents('/tmp/ll_succ.txt', $value ."\t操作成功 \r\n", FILE_APPEND);
				}
			}
		}
		var_dump(count($add), '成功'.$i, '失败'.$j);exit;
	}

	public function updateUrl() {
		$time =  time();
		$file1 = "/home/work/websites/developer/protected/commands/commandfile/auth_depart.txt";
		$data1 = file_get_contents($file1,"rw");
		$arr1 = explode("\n", $data1);
		$auth = new AuthManager();
		$departs = array();
		foreach ($arr1 as $val) {
			if (!$val) {
				continue;
			}
			$depart = trim($val);
			$haveItem = array();
			$auth->getItemChild(array($depart=>1),$haveItem);
			$departs[$depart] = $haveItem;
		}

		$db = yii::app()->db_eel;
		$function = array();
		foreach ($departs as $key=>$val) {
			$result = array();
			foreach($val as $v) {
				$sql = "select funname, business from developer_function where item = '$v' ";
				$result = $db->createCommand($sql)->queryAll();
				foreach($result as $rk) {
					file_put_contents('/tmp/ll_522_function.cvs', $key .",". $rk['business']. ",". $rk['funname'] ."\r\n", FILE_APPEND);
				}
			}

		}
		var_dump((time()-$time)/3600);
	}
}
