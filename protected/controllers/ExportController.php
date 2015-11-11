<?php

class ExportController extends Controller {

	public function actionIndex($business= '',$username= '',$funname = '') {

		$bus = new BusinessManager();
		$project = $bus->getALLBusiness();

		$data = array('business'=>$business,'username'=>$username,'funname'=>$funname, 'project'=>$project);

		$this->render('export/index.tpl', $data);
	}



    public function actionDownLoad($business = '',$username = '')
    {
        set_time_limit(0);
        $excel = new CPHPExcel();
        $sheet = $excel->getActiveSheet();
        $excel->getProperties()->setCreator("developer");
        $db = yii::app()->db_eel;
        $uid = $this->auth->getSid($username);
        $cname = NewCommonManager::getUserRealnameByuid($uid);
        if(!empty($business)&&!empty($uid))
        {
            $i=0;
            $businesses = $this->function->getFunctions($business, '', 'nomal');
            foreach ($businesses as $key => &$value) {
                $i++;
                $name = $business."/".$value['name'];
                if($this->auth->checkAccess($name,$uid))
                {
                    $value['status'] = '有权限';
                    // $excel->setActiveSheetIndex(0)->setCellValue('A'.$i,$business);
                    // $excel->setActiveSheetIndex(0)->setCellValue('B'.$i,$name);
                    // $excel->setActiveSheetIndex(0)->setCellValue('C'.$i,$cname);
                    // $excel->setActiveSheetIndex(0)->setCellValue('D'.$i,"有权限");
                }
                else
                {
                    $value['status'] = '';
                    // $excel->setActiveSheetIndex(0)->setCellValue('A'.$i,$business);
                    // $excel->setActiveSheetIndex(0)->setCellValue('B'.$i,$name);
                    // $excel->setActiveSheetIndex(0)->setCellValue('C'.$i,$cname);
                    // $excel->setActiveSheetIndex(0)->setCellValue('D'.$i," ");
                }
                $value['idx'] = $i;
            }
        }
        $filename = "权限查询".date("Ymd");
        // $excel->dumpToClient($filename);
        $titles = array('idx', '功能名', '用户', '状态');
        $columns = array('idx', 'name', 'cname', 'status');
        $this->common->exportHtml($titles, $columns, $businesses, $filename);
    }

    // filter = 1 有权限
    public function actionViews($business = '',$username = '',$funname='', $filter=0) {
      $return = array();
      if(!empty($business)&&!empty($username)) {
            $results = array();
            $data =array();
            $uid = $this->auth->getSid($username);
            if(empty($uid)) {
                return;
            }
            $cname = NewCommonManager::getUserRealnameByuid($uid);
            $auth = yii::app()->authManager;
            $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
            $rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 10;

            $fun = new FunctionManager();
            $businesses = $fun->getFunctionByPage($business,$funname, 'nomal', $page, $rows);
            $i=0;
            foreach ($businesses as $key => $value) {
                $name = $value['item'];
                if($this->auth->checkAccess($name,$uid,'true')) {
                    $results[$i]['id'] = $uid;
                    $results[$i]['business'] = $business;
                    $results[$i]['name']= $value['name'];
                    $results[$i]['item']=$value['item'];
                    $results[$i]['status'] = "有权限";
                    $results[$i]['username']= $cname;
                    $results[$i]['funname']= $value['name'];

                } else {
                    if ($filter == 1 ) {
                        unset($businesses[$key]);
                        continue;
                    }
                    $results[$i]['id'] = $uid;
                	$results[$i]['business'] = $business;
                    $results[$i]['name']= $value['name'];
                    $results[$i]['item']=$value['item'];
                    $results[$i]['status'] = " ";
                    $results[$i]['username'] = $cname;
                    $results[$i]['funname']= $value['name'];
                }
                $i++;
            }
            $return['rows'] = $results;
            $return['total'] = $fun->getFunctionCount($business,$funname);
            header('HTTP/1.1 200 OK');
            echo json_encode($return);exit;
        }
    }

    public function actiongetAuth(array $item,$uid) {

        $current_name = Yii::app()->user->username;
        $name = NewCommonManager::getUsernameByuid($uid);
        $time = date('Y:m:d H:m:s');
        $auth = yii::app()->authManager;
        foreach ($item as $key => $value) {
           if($auth->getAuthItem($value['item'])!=NULL) {
                if(!$auth->isAssigned($value['item'],$uid)) {

                    $auth->assign($value['item'],$uid);
                    SyslogManager::Write($current_name, SyslogManager::FUNCTION_ASSIGN_USER, $name, $value['item']);
                    //$this->log->printLog($operateUser,$name,'distribution',$value['item'],$time);
                }
            }
        }
        echo json_encode("分配成功");

    }
    public function actionRevokeAuth(array $item,$uid)
    {
        $operateUser = yii::app()->user->username;
        $name = NewCommonManager::getUsernameByuid($uid);
        $time = date('Y:m:d H:m:s');
        $auth = yii::app()->authManager;
        foreach ($item as $key => $value) {
           if($auth->getAuthItem($value['item'])) {
                $auth->revoke($value['item'],$uid);
                SyslogManager::Write($operateUser, SyslogManager::FUNCTION_REVOKE_USER, $name, $value['item']);
                //$this->log->printLog($operateUser,$name,'revoke',$value['item'],$time);
            }
        }
        echo json_encode("解除权限成功");

    }

    // public function actionSearch($business,$username,$funname)
    // {
    //     $results = $this->function->getFunctions($business,$funname);
    //     echo json_encode($results);
    // }

    /**
     * 发送某人的权限
     *
     * @param type $business
     * @param type $username
     * @param type $mailto
     */
    public function actionSendEmail($business = '',$username = '', $mailto='')
    {
        set_time_limit(0);
        $db = yii::app()->db_eel;
        $uid = $this->auth->getSid($username);
        $cname = NewCommonManager::getUserRealnameByuid($uid);
        if(!empty($business)&&!empty($uid))
        {
            $i=0;
            $businesses = $this->function->getFunctions($business, '', 'nomal');
            $start = microtime(true);
            foreach ($businesses as $key => &$value) {

                $i++;
                $name = $business."/".$value['name'];
                if($this->auth->checkAccess($name,$uid))
                {
                    $value['status'] = '有权限';
                }
                else
                {
                    $value['status'] = '无权限';
                }
                $value['idx'] = $i;
                $value['cname'] = $cname;
            }
            $end = microtime(true);
        }
        $titles = array('idx', '功能名', '用户', '状态');
        $columns = array('idx', 'name', 'cname', 'status');
        $strContent = $this->common->bulidHtml($titles, $columns, $businesses);
        $strSubject = $cname."在".$business."的权限情况";
        $bolRet = $this->mail->sendCommMail($mailto, $strSubject, $strContent, array(), true);
        header('Content-type: application/json');
        if($bolRet)
        {
            echo json_encode(array('data'=>'发送成功'));
        }
        else
        {
            echo json_encode(array('data'=>'发送失败'));
        }
        exit;
    }
}
