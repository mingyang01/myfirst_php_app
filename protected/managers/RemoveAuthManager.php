<?php
class RemoveAuthManager extends Manager {

    public function removeAuth($business,$item,$users)
    {
        $op_user = Yii::app()->user->username;
        $db = yii::app()->db_eel;
        $time = time();
        $userArr = array();
        $msg = "";
        $fid = '';
        $op_item = '';
        $op_actions = '';
        if($item){
            $functions = self::getFunctionList($business,$item);
        }else{
            $functions = '';
            $msg = "这个功能貌似不存在啊，请再检查";
            return $msg;
        }
        if($functions){
            $fid = $functions['rows'][0]['id'];
            $op_item = $functions['rows'][0]['item'];
            $op_actions = self::getActionOfFunction($fid);
        }
        $userIds = self::getUserId($users);
        $userIds = $userIds->id;
        $status = self::getRemoveAuth($userIds,$op_item,false);
        if($status){
            $msg = "这个用户的 {$item} 已经移除了";
            return $msg;
        }

        //foreach ($userArr as $key => $value) {
            $sql = "insert into developer_remove_auth (user,op_time,op_user,op_item,op_action) values(?,?,?,?,?)";
            $db->createCommand($sql)->execute(array($userIds,$time,$op_user,$op_item,$op_actions));
        //}
            $msg = "这个用户的 {$item} 移除成功";
            return $msg;
    }
    //获取功能的action
    public function getActionOfFunction($fid)
    {
        $db = yii::app()->sdb_eel;
        $sql = "select action from developer_action where functionid = {$fid} ";
        $results = $db->createCommand($sql)->queryAll();
        $re = array();
        foreach ($results as $key => $value) {
            $re [] = $value['action'];
        }
        $re = implode(',',$re);
        return $re;
    }

    public function getUserId($uid) {
        $output = yii::app()->curl->get("http://api.speed.meilishuo.com/user/show?token=e98cfc1a4f23ae1699919c505ae2ba04&mail=".$uid);
        $results = (array)json_decode($output['body']);
        if($results){
            $results = $results['data'];
            return $results;
        }else{
            return array();
        }
    }

    //获取remove的权限
    public function getRemoveAuth($uid,$option,$flag=true)
    {

        $redis = new Redisdb();
        $rediskey = "removeAuth";
        
        $removeArr = array();
        $itemArr = array();

        if ($redis->exists($rediskey)&&$flag) {
            $re = json_decode($redis->get($rediskey,array()),true);
            return isset($re[$uid])&&in_array($option, $re[$uid]);
        }

        $db = yii::app()->sdb_eel;
        $sql = "select * from developer_remove_auth";
        $results = $db->createCommand($sql)->queryAll();

        foreach ($results as $key => $value) {
            $itemArr = $value['op_item'];
            $action  = explode(',', $value['op_action']);
            $removeArr[$value['user']] [] = $itemArr;
            foreach ($action as $k => $val) {
                $removeArr[$value['user']] [] = $val;
            }
            
        }
        $redis->set($rediskey,array(),json_encode($removeArr));
        return isset($removeArr[$uid])&&in_array($option, $removeArr[$uid]);
    }

    public function getFunctionList($business,$funname='') {

        $db = yii::app()->db_eel;
        $sql = "select * from developer_function where business = '$business' ";
        if($funname) {
            $sql = "select * from developer_function where business = '$business' and  funname = '$funname' ";
        }
        $total = $db->createCommand($sql)->queryAll();
        $total = count($total);

        $where = "";
        if ($business) {
            $where = " where business = '$business'";
            if($funname) {
                $where = " where business = '$business' and  funname = '$funname' ";
            }
        }
        $sql = "select * from developer_function". $where;
        $results = $db->createCommand($sql)->queryAll();
        foreach ($results as $key => $value) {
            $results[$key]['option']=$value['status'];
        }
        $results = array("rows"=>$results,"total"=>$total);
        return $results;
    }

    public function getAllRemove()
    {
        $db = yii::app()->sdb_eel;
        $sql = "select * from developer_remove_auth order by op_time desc";
        $results = $db->createCommand($sql)->queryAll();
        return $results;
    }

    public function getUserAuth($uid,$username,$op_user,$remove){
        $db = yii::app()->sdb_eel;
        $sql = "select t2.name,t2.type from developer_AuthAssignment t1, developer_AuthItem t2 where userid = {$uid} and t1.itemname=t2.name order by type desc";
        $results = $db->createCommand($sql)->queryAll();
        $con = '';
        if($remove){
            foreach ($results as $key => $value) {
                $con = "[".date("Y-m-d H:i:s")."]"."--"."itemname- {$value['name']} type - {$value['type']} op_user - {$op_user}";
            }
            file_put_contents("/home/work/websites/developer/protected/runtime/removeAuthAll.txt",$con,FILE_APPEND);
            $sql = "delete from developer_AuthAssignment where userid = {$uid} ";
            $db->createCommand($sql)->execute();
        }
        return $results;
    }
}