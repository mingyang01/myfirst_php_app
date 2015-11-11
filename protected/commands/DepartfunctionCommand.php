<?php
/**
 * linglingqi的导数据脚本文件
 * @author linglingqi
 *
 */ 
class DepartfunctionCommand extends Command {
	///usr/local/bin/php /home/work/websites/developer/protected/yiic.php Departfunction
	public function main($args) {
		self::updateUrl();
	}
	
	
	//修改data平台的路径，已完成修改
	public function updateUrl() {
		$business = "data平台";
		$url = "/data/data";
		$time = time();
		$db = yii::app()->db_eel;
		$sql = "select * from developer_function where business='$business' and url='$url'";
		$results = $db->createCommand($sql)->queryAll();
		
		$i = $j = 0;
		foreach ($results as $val) {
			$ret = array();
			if (empty($val['funname'])) {
				continue;
			}
			$funname = trim($val['funname']);
			$arr = explode('_', trim($funname));
			$url = '/visual/index/'. $arr[0];
			$sign = 'visual/index/'. $arr[0];
			$id = $val['id'];
				
			$usql = "update developer_function set url='$url', sign='$sign' where id=$id";
			$ret = $db->createCommand($usql)->execute();
			if($ret) {
				$i ++;
				file_put_contents("/tmp/functionsucc.txt", $id ."\t". $funname ."\t". $url. "\r\n", FILE_APPEND);
			} else {
				$j ++;
				file_put_contents("/tmp/functionerr.txt", $id ."\t". $funname ."\t". $url. "\r\n", FILE_APPEND);
			}
				
		}
		var_dump('用时：'.time()-$time);
		var_dump($i, $j);exit;
	}
	
	public function shellaa($args) {
		$file1 = "commandfile/xianshang_new_auth.cvs";
		$data1 = file_get_contents($file1,"rw");
		$arr1 = explode("\n", $data1);
		$in = $out = array();
		$db = Yii::app()->db_eel;
		$i = $j = 0;
		foreach ($arr1 as $val) {
			$tmp = explode(",", $val);
			if (!isset($tmp[0]) || !isset($tmp[1]) || !isset($tmp[2])) {
				$eall[] = $tmp;
				continue;
			}
			$depart = trim($tmp[0]);
			$business = trim($tmp[1]);
			$function = trim($tmp[2]);
			$functons = self::getRelate($depart, $business);
			
			if (in_array($function,$functons)) {
				$in[] = $tmp;
				$i ++;
			} else {
				$new_parent = self::getChild($depart);
				$flag = 0;
				foreach ($new_parent as $pval) {
					$second_function = self::getRelate($pval, $business);
					if (in_array($function, $second_function)) {
						$in[] = $tmp;
						$i ++;
						$flag = 1;
						continue;
					}
					
				}
				if ($flag != 1) {
					$out[] = $tmp;
					$j ++;
				}
			}
			var_dump($i, $j);
		}
		$fp2=fopen("/tmp/auth_in.txt","w");
		fputcsv($fp2,array('部门','项目','功能'));
		foreach ($in as $rval) {
		      fputcsv($fp2,$rval);   //fputcsv可以用数组循环的方式进行实现
		}
		fclose($fp2);
		
		$fp3=fopen("/tmp/auth_out.txt","w");
		fputcsv($fp3,array('部门','项目','功能'));
		foreach ($out as $oval) {
			fputcsv($fp3,$oval);   //fputcsv可以用数组循环的方式进行实现
		}
		fclose($fp3);
		
	}
	
	public function getRelate($depart, $business) {
		$db = Yii::app()->db_eel;
		$sql = "select funname from developer_function a, `developer_AuthItemChild` b where  a.`item`=b.`child` and b.parent='" .$depart ."' and a.business='". $business ."'";
		$functons = $db->createCommand($sql)->queryColumn();
		return $functons;
	}
	
	public function getChild($depart) {
		$db = Yii::app()->db_eel;
		$psql = "select child from developer_AuthItemChild where parent='". $depart ."'";
		$parents = $db->createCommand($psql)->queryColumn();
		return $parents;
	}
	
	
//     public function main($args) {
//     	setlocale(LC_ALL,array('zh_CN.gbk','zh_CN.gb2312','en_US.utf8'));
//        $file = "commandfile/auth_depart.txt";
//        $data = file_get_contents($file,"rw");
       
//        $arr = explode("\n", $data);
//        $db = Yii::app()->db_eel;
//        $result = array();
//        foreach ($arr as $val) {
//        		$depa_childs = array();
//        		$departs = trim($val);
//        		$sql = "select * from developer_function a, `developer_AuthItemChild` b where  a.`item`=b.`child` and b.parent='" .$departs ."'";
//        		$depa_childs = $db->createCommand($sql)->queryAll();
//        		if (!$depa_childs) {
//        			continue;
//        		}
//        		foreach ($depa_childs as $dkey=>$dval) {
//        			$tmp['depart'] = $dval['parent'];
//        			$tmp['business'] = $dval['business'];
//        			$tmp['funname"'] = $dval['funname'];
//        			$result[] = $tmp;
//        		}
//        }
//        $fp2=fopen("/tmp/new_auth.csv","w");
//        fputcsv($fp2,array('部门','项目','功能'));
//        foreach ($result as $rval) {
//        		fputcsv($fp2,$rval);   //fputcsv可以用数组循环的方式进行实现
//        }
       
//        fclose($fp2);
//     }

}
