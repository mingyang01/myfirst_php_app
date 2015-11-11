<?php
/**
 * 公共处理方法
 * @author linglingqi
 * @version 2015-05-29
 */
class Json{
    
	public static function succ($msg="成功！", $errno="1", $data=array()) {
		$re = array('errno' => $errno, 'msg' => $msg, 'result'=>$data);
		echo json_encode($re);
		exit;
	}
	
	public static function fail($msg="失败", $errno="0", $data=array()) {
		$re = array('errno' => $errno, 'msg' => $msg, 'result'=>$data);
		echo json_encode($re);
		exit;
	}
	
	public static function rederText($text) {
		echo $text;
		exit;
	}
}