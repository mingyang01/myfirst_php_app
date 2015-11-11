<?php
/**
* 部门领导，以及部门下的功能查询 for风控
* @author mingyang
* @version 2015-8-24
*/
class RiskController extends Controller
{
    public function ActionIndex(){
        $request = Yii::app()->request;
        $depart = $request->getparam('depart','');
        $departLeaders = $this->risk->getDepartLeader();
        $admin = '';
        if(!empty($depart)){
            foreach ($departLeaders as $key => $value) {
                if($value['itemname']==$depart){
                    $admin = $value['admin'];
                    $depart = $value['itemname'];
                }
            }
        }else{
            $admin = $departLeaders[0]['admin'];
            $depart = $departLeaders[0]['itemname'];
        }
        //ajax请求获得部门的管理员
        if($_GET['depart']){
            echo json_encode($admin);
            return;
        }
        $params = array('departLeaders'=>$departLeaders,'admin'=>$admin,'depart'=>$depart);
        $this->render('risk/index.tpl',$params);
    }
    //获得部门对应有权限的功能 并分页
    public function ActionGetDepartFunction(){
        $request = Yii::app()->request;
        $depart = $request->getparam('depart','');
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 20;
        $offsset = ($page -1) * $rows;
        $where = " order by business limit $offsset , $rows";
        $function = $this->auth->getDepartFunction($depart,$where);
        $total = $this->auth->getDepartFunction($depart);
        $total =  count($total);
        //var_dump($function);exit;
        $results = array('total'=>$total,'rows'=>$function);
        echo json_encode($results);
    }
    //部门的功能导出
    public function ActionExportHtml(){
        $request = Yii::app()->request;
        $depart = $request->getparam('depart','');
        $admin = $request->getparam('admin','');
        $title = $depart.'-'.$admin;
        $where = " order by business";
        $functions = $this->auth->getDepartFunction($depart,$where);
        $this->risk->exportExcel($functions,$title);
    }
    //部门人员及功能查看
    public function ActionDepartDetailViews(){
        $auth = new AuthManager();
        $request = Yii::app()->request;
        $departs = NewCommonManager::getDepartRole();
        $depart = $request->getparam('depart','');
        if(empty($depart)){
            $depart = 2;
        }
        $this->render('risk/depart-staff-detail.tpl',array('departs'=>$departs,'depart'=>$depart));
    }
    //ajax 获取部门人员
    public function ActionGetDepartStaff(){

        $auth = new AuthManager();
        $request = Yii::app()->request;
        $depart = $request->getparam('depart','');
        $common = new CommonManager();
        $results = $common->getDepartUsers($depart);
        $functionItem = FunctionManager::FunctionItemToFunction();
        foreach ($results as $key => $value) {
            $item = $auth->getNewAuthFunction($value['id']);
            foreach ($item as $k => $val) {
                $item[$k] = $functionItem[$val];
            }
            $results[$key]['function'] = implode(',',$item);
        }
        echo json_encode($results);
    }

    public function ActionExportDepartStaffAuth(){
        $auth = new AuthManager();
        $request = Yii::app()->request;
        $depart = $request->getparam('depart','');
        $common = new CommonManager();
        $results = $common->getDepartUsers($depart);
        //$functionItem = FunctionManager::FunctionItemToFunction();
        $departs = NewCommonManager::getDepartRole();
        $function = $this->auth->getDepartFunction($departs[$depart]);
        $departFunction = array();
        foreach ($function as $key => $value) {
            $departFunction [] = $value['item'];
        }
        $all = array();
        $i = 0;
        foreach ($results as $key => $value) {
            $item = $auth->getNewAuthFunction($value['id']);
            foreach ($item as $k => $val) {
                $all[$i]['name'] = $value['name'];
                $all[$i]['mail'] = $value['mail'];
                $items = explode('/', $val);
                $business = $items[0];
                $all[$i]['business'] = $business;
                $all[$i]['function'] = $items[1];
                $all[$i]['item'] = $val;
                $all[$i]['flag'] = in_array($val,$departFunction)?'是':'否';
                $i ++;
            }
        }
        $title = $departs[$depart];
        $title.= '--权限情况';
        $this->risk->exportDepartExcel($all,$title);
    }
}