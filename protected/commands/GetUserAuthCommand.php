<?php
/**
 * 获取部门下员工有权限的功能信息
 * @author linglingqi@
 */

class GetUserAuthCommand extends Command {

    public function main($args) {

    $depart_id = $args['depart'];
    self::getfileUserAuth($depart_id);


    }

    public function getfileUserAuth($departid) {


    	$output = Yii::app()->curl->get("http://api.speed.meilishuo.com/user/show_depart_user?token=e98cfc1a4f23ae1699919c505ae2ba04&depart_id=$departid");

    	$results = (array)json_decode($output['body']);
        $results = (array)$results['data'];


    	$file = dirname(__FILE__) ."/commandfile/users.txt";

    	$lines = file_get_contents($file);
    	$users = explode("\n", $lines);
    	foreach ($users as $val) {
    		$user[] = explode("\t", $val);
    	}


    	$new = array();
    	foreach ($results as $val) {
    		$val = (array)$val;
    		if (!$val) {
    			continue;
    		}
    		$new[trim($val['name'])] = $val;
    	}
    	$users = ArrFomate::hashmap($user, '0');

    	$db = yii::app()->db_eel;
    	$comm = new CommonManager();
    	$un_results = array();
    	foreach ($users as $key=>$uval) {
    		$tmp = array();
    		if (!isset($new[trim($key)])) {
    			$sql = "select username from t_eel_admin_user where realname='$key'";
    			$mail = $db->createCommand($sql)->queryRow();
    			if (!$mail) {
    				var_dump($key);
    			}
    			$use = $comm->getUser($mail['username']);

    			$un_results[$key] = $use;
    		}
    	}

    	$results = array_merge($new, $un_results);

    	$auth = new AuthManager();
    	$fun = new FunctionManager();
    	$menu = new MenuManager();
    	$bus = new BusinessManager();

    	$datas = DataapiManager::getDataMenu();

    	$return = array();
    	$i=$j=0;
    	foreach ($results as $val) {
    		$val = (array)$val;

    		if (!$val) {
    			continue;
    		}

    		if (isset($users[$val['name']])) {
    			$i ++;
    			//获取用户的有权限功能；然后获取功能的菜单，如果菜单不存在给出链接
    			$function = $auth->getAuthFunction($val['id']);
    			$h=0;
    			foreach ($function as $fval) {

    				$tmp = array();
    				$tmp['name'] = $val['name'];
    				$tmp['depart_1'] = $users[$val['name']][1];
    				$tmp['depart_2'] = $users[$val['name']][2];
    				$tmp['mail'] = $val['mail'];

    				//获取功能的详细信息
    				$f_info = $fun->getFunctionByItem($fval);
    				if ($f_info) {
    					$j ++;
    					$h ++;
    					$tmp['funname'] = $f_info['funname'];
    					$funname = $f_info['funname'];

    					if ($f_info['business']=='data平台') {
    						$m_list = array();
							foreach($datas as $dval) {
								if (isset($dval['funname']) && $funname) {
									if ($dval['funname'] == $funname) {
										$m_list['first'] = isset($dval['first_menu']) ? $dval['first_menu'] : ' ';
										$m_list['second'] = isset($dval['second_menu']) ? $dval['second_menu'] : ' ';
										$m_list['third'] = isset($dval['funname']) ? $dval['funname'] : ' ';
										$m_list && $tmp['menu'][] = $m_list;
									}
								}
							}

    					}else {
    						$m_info = $menu->getMenuByfunctionid($f_info['id']);

    						if ($m_info) {
    							$m_list = array();
    							foreach ($m_info as $mval) {
    								if ($mval['business'] == 'focus' || $mval['business'] == 'works') {
    									continue;
    								}
    								$m_list['first'] = $mval['first'] ?  $mval['first'] : ' ';
    								$m_list['second'] = $mval['second'] ? $mval['second']: ' ';
    								$m_list['third'] = $mval['third'] ? $mval['third']: ' ';
    								$m_list && $tmp['menu'][] = $m_list;
    							}

    						}
    					}
    					$b_info = $bus->getInfoByBusiness($f_info['business']);
    					$url = 'http://'. $b_info['description'] . trim($f_info['url'], '/');
    					$tmp['url'] = $url ? $url : ' ';
    					$tmp['business'] = $b_info['cname'];

    					$return_key = $val['id'] .'-'. $funname;
    					$return[$return_key] = $tmp;
    				}

    			}
    			var_dump('用户:'. $val['name'] . '有功能'. $h);
    		}

    	}
    	$filename = "部门权限查询".date("Ymd").'.csv';
    	$fp = fopen('/tmp/'.$filename, 'a');
    	$titles = array('人员名单', '邮箱', '一级部门', '二级部门', '平台', '一级菜单', '二级菜单', '三级菜单', '功能名称','url');
    	foreach ($titles as $i => $tv) {
    		// CSV的Excel支持GBK编码，一定要转换，否则乱码
    		$head[$i] = mb_convert_encoding($tv, 'gbk', 'utf-8');
    	}
    	fputcsv($fp, $head , ';');
    	$columns = array('name', 'mail', 'depart_1', 'depart_2', 'business', 'first', 'second', 'third', 'funname', 'url');
    	$out = array();
    	foreach ($return as $key=>$val){

    		//将所有的数据都转化成单条的数组
    			if (isset($val['menu'])) {
    				!is_array($val['menu']) && $val['menu'] = array($val['menu']);
    				foreach($val['menu'] as $mm_val) {
    					$tmp = array();
    					//菜单多余一条
    					$tmp['name'] = $val['name'];
    					$tmp['mail'] = $val['mail'];
    					$tmp['depart_1'] = $val['depart_1'];
    					$tmp['depart_2'] = $val['depart_2'];
    					$tmp['business'] = isset($val['business']) ? $val['business'] : '';
    					$tmp['first'] = isset($mm_val['first']) ? $mm_val['first'] : '';
    					$tmp['second'] = isset($mm_val['second']) ? $mm_val['second'] : '';
    					$tmp['third'] = isset($mm_val['third']) ? $mm_val['third'] : '';
    					$tmp['funname'] = isset($val['funname']) ?  $val['funname'] : '';
    					$tmp['url'] = isset($val['url']) ? $val['url'] : '';
    					$out[] = $tmp;
    				}
    			} else {
    				$tmp = array();
    				//菜单多余一条
    				$tmp['name'] = $val['name'];
    				$tmp['mail'] = $val['mail'];
    				$tmp['depart_1'] = $val['depart_1'];
    				$tmp['depart_2'] = $val['depart_2'];
    				$tmp['business'] = isset($val['business']) ? $val['business'] : '';
    				$tmp['first'] = '';
    				$tmp['second'] = '';
    				$tmp['third'] = '';
    				$tmp['funname'] = isset($val['funname']) ?  $val['funname'] : '';
    				$tmp['url'] = isset($val['url']) ? $val['url'] : '';
    				$out[] = $tmp;
    			}
    	}
		self::export_csv($filename, $out, $columns);
    	fclose($fp);
    	var_dump('用户有:'. $i ."个，用户". $val['name'] ."个，共有有权限功能". $j);
    }


    function export_csv($filename,$data, $colum) {

    	$fp = fopen('/tmp/'.$filename, 'a');
    	$result = array();
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