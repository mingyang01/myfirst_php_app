<?php
/**
 * data平台的接口调用
 * @author linglingqi
 * @version 2015-06-09
 */
class DataapiManager extends Manager {

	public static function getDataMenu() {
		
		$curl = Yii::app()->curl;
		$re = $curl->get("http://data.meiliworks.com/service/getmenu");
		$menu = json_decode($re['body'], true);
 		if($menu['status'] == 1) {
			$menu = $menu['data'];
 		} else {
 			throw new Exception('api fail,please check!');
 		}
		return $menu;
	}

}