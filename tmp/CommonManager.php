<?php
class CommonManager extends Manager {
    public function userMap() {
        $speed = new Speed();
        $redis = new Redis();
        //$redis->connect('172.16.12.117');
        $redis->connect('127.0.0.1');
        $redis->select(9);

        $key = "userMap";
        if ($redis->exists($key)) {
            return json_decode($redis->get($key), true);
        }

        $sql = "select * from t_eel_admin_user";
        $db = Yii::app()->sdb_eel;
        $results = $db->createCommand($sql)->queryAll();

        $map = array();
        foreach ($results as $k => $value) {
            $user = $speed->getUser($value['username']);
            $map[$value['user_id']] = array(
                'username'=>$value['username'],
                'realname'=>$value['realname'],
                //'depart'=>$user['departname'],
                //'id'=>$user['sid']
            );
        }

        $redis->set($key, json_encode($map));
        return $map;
    }

    // works
    public function getUid($username) {
        $sql = "select user_id from t_eel_admin_user where username='$username'";
        $db = Yii::app()->sdb_eel;
        $results = $db->createCommand($sql)->queryColumn();

        return $results[0];
    }

    public function getDepartUsers($depart) {
        $params = Yii::app()->params;
        $token = $params['TOKEN'];
        $api = $params['SPEED'];
        $url = $api."/user/show_depart_user?token=$token&depart_id=$depart";

        $output = Yii::app()->curl->get($url);
        $results = json_decode($output['body'], true);
        $results = $results['data'];

        return $results;
    }

    public function getUser($username) {
        $output = Yii::app()->curl->get("http://api.speed.meilishuo.com/user/show?token=e98cfc1a4f23ae1699919c505ae2ba04&mail=".$username);
        $results = (array)json_decode($output['body']);
        $results = (array)$results['data'];
        return $results;
    }

    public function getUserById($uid) {

    	$output = Yii::app()->curl->get("http://api.speed.meilishuo.com/user/show?token=e98cfc1a4f23ae1699919c505ae2ba04&id=".$uid);
    	$results = (array)json_decode($output['body']);
    	$results = (array)$results['data'];
    	return $results;
    }


    function array_column($array, $column) {
        if(function_exists("array_column")) {
            return array_column($array, $column);
        } else {
            if(!is_array($array) || count($array) === 0) {
                return array();
            }
            $ret = array();
            foreach($array as $row) {
                $ret[] = $row[$column];
            }
            return $ret;
        }
    }

    public function getAllStaff($status=true)
    {

        $departall = $this->auth->getDepartByName($status);
        $staffList = array();
        $redis = new Redis();
        //$redis->connect('172.16.12.117');
        $redis->connect('127.0.0.1');
        $redis->select(9);
        $rekey = "AllStaff";
        if($status&&$status!=='false')
        {
            if ($redis->exists($rekey)) {
                return json_decode($redis->get($rekey),true);
            }
        }

        $flag = 0;
        foreach ($departall as $key => $value) {

            $all = CommonManager::getDepartUsers($key);
            if($all)
            {
                foreach ($all as $k => $val) {
                    $ename = explode('@',$val['mail']);
                    $ename = $ename[0];
                    $staffList [$flag] ['username']= $ename;
                    $staffList [$flag] ['name']= $val['name'];
                    $staffList [$flag] ['id']= $val['id'];
                    $staffList [$flag] ['departid']= $key;
                    $staffList [$flag] ['depart']= $value;
                    $flag ++;
                }
            }


        }
        $redis->set($rekey, json_encode($staffList));
        return array_values($staffList);
    }

    public function getSpeedId($status =true)
    {
        $variable = $this->getAllStaff($status);
        $nameArray = array();
        foreach ($variable as $key => $value) {
            $nameArray[$value['username']] = $value['id'];
        }
        return $nameArray;
    }
    public function getUserName()
    {
        $variable = $this->getAllStaff();
        $nameArray = array();
        foreach ($variable as $key => $value) {
            $nameArray[$value['id']] = $value['username'];
        }
        return $nameArray;
    }

    /**
     * 根据用户ID获取用户名
     * @param unknown $uid
     * @return unknown
     */
    public function getUserNameByUid($uid) {

    	$redis = new Redis();
    	//$redis->connect('172.16.12.117');
        $redis->connect('127.0.0.1');
    	$redis->select(9);
    	$rekey = sprintf("user_name_%s",$uid);
    	if ($redis->exists($rekey)) {
    		return json_decode($redis->get($rekey),true);
    	}
    	$auth = new AuthManager();
    	$username = $auth->getUserName($uid);
    	$redis->set($rekey, json_encode($username));
    	return $username;
    }

    public function getUsersInDepart()
    {
        $redis = new Redis();
        //$redis->connect('172.16.12.117');
        $redis->connect('127.0.0.1');
        $redis->select(9);
        $rekey = "Depart-users";
        if ($redis->exists($rekey)) {
            return json_decode($redis->get($rekey),true);
        }
        $departall = $this->auth->getDepartByName();
        $reArray = array();
        foreach ($departall as $key => $value) {
            $reArray [$key]= $this->common->getDepartUsers($key);
        }
        $redis->set($rekey, json_encode($reArray));
        return $reArray;
    }

    /**
     * 获取部门信息，带有领导信息，不提供外部调用
     */
    private function getDepart() {
    	$depart = array();
    	$output = yii::app()->curl->get("http://api.speed.meilishuo.com/user/show_depart?token=e98cfc1a4f23ae1699919c505ae2ba04");
    	$results = (array)json_decode($output['body'], true);
    	$results = $results['data'];
    	$all_depart = array();
    	foreach($results as $key=>$val) {
    		if (!$val['sub_depart']) {
    			$all_depart[$val['depart_id']] = $val;
    		} else {
    			foreach ($val['sub_depart'] as $sval) {
    				$all_depart[$sval['depart_id']] = $sval;
    			}
    		}
    	}
    	return $all_depart;
    }

    /**
     * 获取所有的用户信息
     */
    public function getAllUsers() {

    	$redis = new Redis();
    	//$redis->connect('172.16.12.117');
        $redis->connect('127.0.0.1');
    	$redis->select(9);

    	$redis_key = "all_users_info";
    	$departs = self::getDepart();
    	$users = array();
    	foreach ($departs as $key=>$val) {
    		$tmp = array();
    		$tmp = self::getDepartUsers($key);
    		if (!$tmp) {
    			continue;
    		}
    		$users = array_merge($tmp, $users);
    	}
    	$users = ArrFomate::hashmap($users, 'id');
		$ceo = self::getUser('yirongxu');
		$users[1] =  (array)$ceo;
		$redis->set($redis_key, json_encode($users));
		return $users;
    }

    /**
     * 获取二级部门id
     */
    public static function getSecondDepart() {
    	$departs = self::getDepart();
    	$second_id = array();
    	foreach($departs as $key=>$val) {
    		if ($val['sub_depart']) {
    			$second_id[$key] = $key;
    		}
    	}
    	return $second_id;
    }

    /**
     * 根据二级分类ID获取三级分类
     */
    public static function getDepartBySecondId($id) {

    	$second_id = self::getSecondDepart();
    	if (!in_array($id, $second_id)) {
    		return false;
    	}
    	$departs = self::getDepart();
    	$info = $departs[$id];
        $third = array();
        foreach($info['sub_depart'] as $val) {
            $third[$val['depart_id']] = $val['depart_name'];
        }
        return $third;
    }
    function exportHtml($titles, $columns, $rows, $filename=''){
        if(empty($filename)) $filename = date('Ymd') . '.xls';
        Header ( "Content-type:   application/octet-stream " );
        Header ( "Accept-Ranges:   bytes " );
        Header ( "Content-type:application/vnd.ms-excel;charset=Big5" );
        Header ( "Content-Disposition:attachment;filename=" . $filename );
        header ( 'content-Type:application/vnd.ms-excel;charset=utf-8' );

        $html  = "";
        $html .='<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        $html .='<table border=1><thead><tr>';

        foreach ($titles as $key=>$val){
            $html .= '<td style="background:cornsilk">'.$val.'</td>';
        }
        $html .='</tr></thead>';

        foreach ( $rows as $key=>$val){
            $html .='<tbody><tr>';
            //数据字段
            foreach ($columns as $k=>$v){
                if(isset($val[$v])){
                    $html .='<td>'.$val[$v].'</td>';
                } else {
                    $html .='<td></td>';
                }
            }
            $html .='</tr>';
        }

        $html .='</tbody></table>';

        echo $html;
    }

    /**
     * 判断leader
     *
     * @param type $leader
     * @param type $employee
     * @return type
     */
    public function isDirectLeader($leader, $employee)
    {
        return ( (isset($leader['id'])&&!empty($leader['id'])) && (isset($employee['direct_leader'])&&!empty($employee['direct_leader'])) ) && ($leader['id'] == $employee['direct_leader'])?TRUE:FALSE;
    }


    /**
     *
     * @param type $titles
     * @param type $columns
     * @param type $rows
     * @param string $filename
     */
    public function bulidHtml($titles, $columns, $rows)
    {
        $html  = "";
        $html .='<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        $html .='<table border=1><thead><tr>';

        foreach ($titles as $key=>$val){
//            var_dump(mb_detect_encoding($va))
            $html .= '<td style="background:cornsilk">'.$val.'</td>';
        }
        $html .='</tr></thead>';
        $html .='<tbody>';
        foreach ( $rows as $key=>$val){
            $html .='<tr>';
            //数据字段

            foreach ($columns as $k=>$v){
                if(isset($val[$v])){
                    $html .='<td>'.htmlspecialchars($val[$v]).'</td>';
                } else {
                    $html .='<td></td>';
                }
            }
            $html .='</tr>';
        }
        $html .='</tbody></table>';
        return $html;
    }

    /**
     * 脚本情况下写文件
     * @param  $filename 文件名
     * @param  $titles	标题
     * @param  $data	数据
     * @param  $colum	数组对应的列名
     */
    public static function export_csv($filename, $titles, $data, $colum) {

    	$fp = fopen('/tmp/'.$filename .'.cvs', 'a');

    	foreach ($titles as $i => $tv) {
    		// CSV的Excel支持GBK编码，一定要转换，否则乱码
    		$head[$i] = mb_convert_encoding($tv, 'gbk', 'utf-8');
    	}
    	fputcsv($fp, $head , ';');

    	foreach ($data as $row) {
    		$tmp = array();
    		foreach ($colum as $col) {
    			if (isset($row[$col])) {
    				$tmp[$col] = mb_convert_encoding(trim($row[$col]), 'gbk', 'utf-8');
    			}else {
    				$tmp[$col] = ' ';
    			}
    		}
    		fputcsv($fp, $tmp, ';');
    	}
    	fclose($fp);
    }
}
