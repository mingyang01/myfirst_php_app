<?php
/**
 * 测试文件
 * @author linglingqi
 */

class LtestController extends Controller {

	//check redis
	public function actionCheck() {

		$redis = new Redisdb();
		$redis_key = "depart";

		var_Dump($redis->get($redis_key, array()));
		if($status&&$status!=='false') {
			if ($redis->exists($redis_key, array())) {
				return json_decode($redis->get($redis_key, array()), true);
			}
		}
		$depart = array();
		$output = yii::app()->curl->get("http://api.speed.meilishuo.com/user/show_depart?token=e98cfc1a4f23ae1699919c505ae2ba04");
		$results = (array)json_decode($output['body'], true);
		$results = $results['data'];
		$data = array();

		$data = $this->getAllName($results, $data);
		$redis->set($redis_key, array(), json_encode($data));
		return $data;
	}
}