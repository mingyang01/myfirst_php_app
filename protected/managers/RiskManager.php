<?php
/**
 * 部门权限信息for风控
 * @author mingyang
 * @version 2015-08-24
 */
class RiskManager extends Manager {

    //获得部门管理员及部门下的功能
    public function getDepartLeader(){
        $auth = new AuthManager();
        $depart = NewCommonManager::getDepartRole();
        foreach ($depart as $key => $value) {
            $departs[] = $value;
        }
        $leaders = $this->getItemAssigns($departs);
        return $leaders;
    }
    //获得某一角色有权限的功能
    public function getItemAssigns($item) {

        if (!is_array($item)) {
            return false;
        }
        $auth = yii::app()->authManager;
        $db = yii::app()->sdb_eel;
        $sql = "select itemname,userid from developer_AuthAssignment where itemname in ('". implode("','", $item) ."')";
        $result = $db->createCommand($sql)->queryAll();
        $hasAdmin = array();
        foreach ($result as $key => $value) {
            if($auth->checkAccess('super',$value['userid'])){
                unset($result[$key]);
            }
        }
        foreach ($result as $key => $value) {
            $result[$key]['admin'] = NewCommonManager::getUserRealnameByuid($value['userid']);
        }

        return $result;
    }
    //导出权限的方法
    public function exportExcel($list, $title){

        $titles = array('项目','功能名称','功能点');
        $columns = array('business','funname','item');
        $title = $title."_".date("Y-m-d H:i:s").".xls";
        CommonManager::exportHtml($titles, $columns, $list, $title);
    }
    //部门人员权限查看
    public function exportDepartExcel($list, $title){

        $titles = array('姓名','邮箱','项目','功能名称','权限点','是否为本部门权限');
        $columns = array('name','mail','business','function','item','flag');
        $title = $title."_".date("Y-m-d H:i:s").".xls";
        CommonManager::exportHtml($titles, $columns, $list, $title);
    }
}