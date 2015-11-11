<?php
/**
 * 日志展现
 * @author linglingqi@meilishuo.com
 * @version 2015-06-11
 */
class AuditController extends Controller {
	
    public function actionIndex($user='', $opttype=0, $optuser='', $optfunction='', $start='', $end='', $nowPage=1, $count=20){

    	$types = SyslogManager::getType();
    	
    	$offset = ($nowPage-1) * $count;
    	$data = SyslogManager::getlog(trim($user), trim($opttype), trim($optuser), trim($optfunction), $start, $end, $offset, $count);
    	$total = SyslogManager::getLogCount(trim($user), trim($opttype), trim($optuser), trim($optfunction), $start, $end);
    	
    	foreach ($data as $key=>$val) {
    		$data[$key]['type'] = $types[$val['type']];
    		$data[$key]['time'] = date('Y-m-d H:i:s', $val['time']);
    	}
    	$pageInfo = PageManager::getPageInfo($total, $nowPage, $count);
    	$tmpArr = array(
    			'types'		=>	$types, 
    			'data'		=>	$data,
    			'start'		=>	$start,
    			'end'		=>	$end,
    			'user'		=>	$user,
    			'type'		=>	$opttype,
    			'optuser'	=>	$optuser,
    			'optfunction'=>	$optfunction,
    			'totalNum'	=> $total,
    			'pageInfo'	=>	$pageInfo,
    			'page'		=>	$nowPage,
    	);
        $this->render('log/index.html', $tmpArr);
    }

    /**
     * 平台的审计
     * @author mingyang@meilishuo.com
     * @version 2015-09-15
     */
    public function actionBusinessAudit()
    {
        $request = Yii::app()->request;
        $business = $request->getparam('business','developer');
        $user = $request->getparam('user','');
        $start_time = $request->getparam('start_time','');
        $end_time = $request->getparam('end_time','');
        $condition = $request->getparam('condition','');
        $project = $this->publish->getUserBusiness();
        $end_unix = '';
        $start_unix = empty($start_time)?strtotime(date('Y-m-d')." 00:00:00"):strtotime($start_time." 00:00:00");
        (!empty($end_time)&&empty($start_time))&&($start_time='')||($end_unix=strtotime($end_time." 23:59:59"));
        $results = $this->audit->getAuditLogByBusiness($business,$user,$start_unix,$end_unix,$condition);
        //分页
        $page = isset($_GET['nowPage']) ? intval($_GET['nowPage']) : 1;
        $pagesize = 10;
        $offset = ($page-1)*$pagesize;
        $total = count($results);
        if ($total > $pagesize) {
            $results = array_slice($results, $offset, $pagesize);
        }
        foreach ($results as $key => $value) {
            $results[$key]['unix'] = date('Y-m-d',$value['unix']);
            $results[$key]['audit_flag'] = $value['audit_flag']==0?'无权限':'有权限';
        }

        $pageInfo = PageManager::getPageInfo($total, $page, $pagesize);
        $data = $this->audit->getBusinessDetail();
        $params = array(
            'data'=>$results,
            'project'=>$project,
            'business'=>$business,
            'pageInfo'=>$pageInfo,
            'totalNum'=>$total,
            'condition'=>$condition,
            'user'=>$user,
            'start_time' =>date('Y-m-d',$start_unix),
            'end_time' =>$end_time,
            'businessMessage'=>$data
            );
        $this->render("audit/business-audit.tpl",$params);
    }
    //总平台的概览
    public function actionBusinessDetail()
    {
        $request = Yii::app()->request;
        $start_time = $request->getparam('start_time','');
        $end_time = $request->getparam('end_time','');
        $end_unix = '';
        $start_unix = empty($start_time)?strtotime(date('Y-m-d')." 00:00:00"):strtotime($start_time." 00:00:00");
        (!empty($end_time)&&empty($start_time))&&($start_time='')||($end_unix=strtotime($end_time." 23:59:59"));
        $data = $this->audit->getBusinessDetail($start_unix,$end_unix);
        $params = array(
            'businessMessage'=>$data,
            'start_time' =>date('Y-m-d',$start_unix),
            'end_time' =>$end_time
            );
        $this->render("audit/business-detail.tpl",$params);
    }
    //某个平台人员访问情况
    public function actionUvOfBusiness()
    {
        $request = Yii::app()->request;
        $start_time = $request->getparam('start_time','');
        $business = $request->getparam('business','developer');
        $end_time = $request->getparam('end_time','');
        $project = $this->publish->getUserBusiness();
        $end_unix = '';
        $start_unix = empty($start_time)?strtotime(date('Y-m-d')." 00:00:00"):strtotime($start_time." 00:00:00");
        (!empty($end_time)&&empty($start_time))&&($start_time='')||($end_unix=strtotime($end_time." 23:59:59"));
        $data = $this->audit->getPvOfBusiness($business,$start_unix,$end_unix);
        $params = array(
            'project'=>$project,
            'data'=>$data,
            'business'=>$business,
            'start_time' =>date('Y-m-d',$start_unix),
            'end_time' =>$end_time
            );
        $this->render("audit/business-uv.tpl",$params);
    }
}