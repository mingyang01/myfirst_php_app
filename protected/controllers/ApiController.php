<?php

class ApiController extends Controller
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
                'actions'=>array("menu","checkAccess", "VisterLog"),
                'users'=>array('?'),
            ),

            array('deny',
                'users'=>array('?'),
            ),
        );
    }

    /**
     * 现在很少使用，不建议提供出去
     */
    public function actionMenu($uid, $business=null, $domain=true)
    {
        $result = array("code"=>1, "message"=>"", "data"=>array());
        if (!$uid) {
            $result['code'] = 0;
            $result['message'] = "param uid is required!";
            echo json_encode($result);
            yii::app()->end();
        }
        // $uid = $this->speed['id'];
        echo json_encode(MenuManager::getMenu($business, $uid, $domain));
    }

    /**
     * 判断用户的功能权限
     */
    public function actionCheckAccess($point, $uid, $params=""){

        if($params) {
            @$params = json_decode($params, true);
        }
	    $db = yii::app()->db_eel;
	    $sql = "select controller,action from developer_whitelist";
	    $results = $db->createCommand($sql)->queryAll();
	    $options = array();
	    foreach ($results as $key => $value) {
	        $options []= strtolower($value['controller'])."/".strtolower($value['action']);
	    }
	    $auth = yii::app()->authManager;
	    $whiteListFlag = in_array(strtolower($point), $options);
	    if(!$auth->getAuthItem($point)&&!$whiteListFlag) {

	    	$message ='你没有该功能的权限，<a href="developer.meiliworks.com/auth/distribution" target="_blank">请点击跳转到申请页</a>申请权限,如还有问题请联系运营人员：<a href="mailto:quanxian@meilishuo.com">quanxian@meilishuo.com</a>；或者开发人员：<a href="mailto:linglingqi@meilishuo.com">linglingqi@meilishuo.com</a>';
	        $result = array("code"=>0, "message"=>$message, "data"=>array());
	        echo json_encode($result);

	    } else {
	        $sign = explode("action/", $point);
	        if(count($sign)>0) {
	                $sign = $sign[1];
	            } else {
	               $sign =  $point;
	            }
	            //$funname = $this->function->getFunname($sign);
	            $funname = " ";
	            $check = $this->auth->checkAccess($point, $uid, $params);
	            if (!$check) {
	            	$message ='你没有该功能的权限，<a href="developer.meiliworks.com/auth/distribution" target="_blank">请点击跳转到申请页</a>申请权限,如还有问题请联系运营人员：<a href="mailto:quanxian@meilishuo.com">quanxian@meilishuo.com</a>；或者开发人员：<a href="mailto:linglingqi@meilishuo.com">linglingqi@meilishuo.com</a>';
	            }
	            $result = array("code"=>1,"function"=>$funname, "message"=>$message, "data"=>array('checked'=>$check));
	            echo json_encode($result);
	        }
	 }

	 /**
	  * 纪录员工对各平台下的功能访问情况
	  */
	 public function actionVisterLog() {

	 	$request = Yii::app()->getRequest();
	 	$uid = $request->getQuery('uid');
	 	$url = $request->getQuery('url');
	 	$ip = $request->getQuery('ip');

	 	if (!$uid || !$url || !$ip) {
	 		Json::fail('访问信息不全');
	 	}
	 	try {
	 		$redis = new Redisdb();
	 		$key='mq_Visitelog';
	 		$data = array(
	 				'url'		=>	$url,
	 				'uid'		=>	$uid,
	 				'ip'		=>	$ip,
	 		);
			if ($redis->llen($key)<10000) {
				$redis->lPush($key, array(), array($data));
			} else {
				$mail = new MailManager();
				$mail->sendWarnning("redis队列阻塞,ip：$ip;url：$url");
			}

	 	} catch (Exception $e) {
	 		$mail = new MailManager();
	 		$mail->sendWarnning('用户访问log写数据失败，请检查队列');
	 		Json::fail('访问日志');
	 	}

	 	Json::succ('记录成功');
	 }

}
