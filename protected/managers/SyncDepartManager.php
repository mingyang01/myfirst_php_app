<?php
/**
 * 部门信息同步
 * @author mingyang@meilishuo.com
 * @since 2015-09-21
 */
class SyncDepartManager extends Manager {

    const ITEM_TYPE  = 2;
    const DEFAULT_ID = 0;
    //更具部门名称获得部门id  同步到 developer_AuthItem departid
    public function updateDepartId()
    {
        $departDetail = self::getDepartSecond();
        $sdb          = Yii::app()->sdb_eel;
        $db           = Yii::app()->db_eel;
        $type         = self::ITEM_TYPE;
        $sql = "select * from developer_AuthItem where type = {$type} ";
        $results = $sdb->createCommand($sql)->queryAll();
        $depart = array();
        foreach ($results as $key => $value) {
            $depart [$value['name']] = $value['id'];
        }
        $nameToId = array_flip($departDetail);
        foreach ($depart as $key => $value) {
            $name = $key;
            if(isset($nameToId[$name])){
                $sql = "update developer_AuthItem set departid = ? where id = ? ";
                $db->createCommand($sql)->execute(array($nameToId[$name],$value));
            }
        }
    }
    //获得最新的部门id-->部门名称
    public function getDepartDetail()
    {
        $departDetail     = self::getDepartSecond();
        $sdb              = Yii::app()->sdb_eel;
        $db               = Yii::app()->db_eel;
        $type             = self::ITEM_TYPE;
        $defaultid        = self::DEFAULT_ID;
        $sql = "select * from developer_AuthItem where type = {$type} and departid <> $defaultid ";
        $results = $sdb->createCommand($sql)->queryAll();
        $depart = array();
        foreach ($results as $key => $value) {
            $depart [$value['departid']] = $value['name'];
        }
        foreach ($depart as $key => $value) {
            if(isset($departDetail[$key])&&$departDetail[$key]!=$value){
                $con = '改变的 id=>'.$key.' 原来的depart=>'.$value.'  -----  现在的depart=>'.$departDetail[$key]."\t\n";
                file_put_contents("/home/work/websites/developer/protected/runtime/departtest.txt",$con,FILE_APPEND);
            
                $this->updateDepartItem($value,$departDetail[$key]);
            }else{
                $con = '没变的 id=>'.$key.' 原来的depart=>'.$value.'  -----  现在的depart=>'.$departDetail[$key]."\t\n";
                file_put_contents("/home/work/websites/developer/protected/runtime/departtest.txt",$con,FILE_APPEND);
            }
        }
    }
    //更新权限三张表中的部门名称
    public function updateDepartItem($oldMsg,$newMsg)
    {
        $type        = self::ITEM_TYPE;
        $connection  = Yii::app()->db_eel;
        $transaction =$connection->beginTransaction();
        //查询这三张表中需要更新的id
        $sql = "select assig.id,assig.itemname from developer_AuthAssignment assig, developer_AuthItem item where assig.itemname = item.name and item.type = {$type} ";
        $results = $connection->createCommand($sql)->queryAll();
        $arr1 = array();
        foreach ($results as $key => $value) {
            $itemname = explode('/', $value['itemname']);
            if($itemname[0] == $oldMsg){
                $arr1 [] = $value['id'];
            }
        }
        $sql = "select itemchild.id,itemchild.parent,itemchild.child from developer_AuthItemChild itemchild, developer_AuthItem item where (itemchild.parent = item.name or itemchild.child = item.name)and item.type={$type} ";
        $results = $connection->createCommand($sql)->queryAll();
        $arr2 = array();
        foreach ($results as $key => $value) {
            $parent = explode('/', $value['parent']);
            $child = explode('/', $value['child']);
            if($parent[0] == $oldMsg||$child[0] == $oldMsg){
                $arr2 [] = $value['id'];
            }
        }

        $sql = "select id,name from developer_AuthItem where type = {$type} ";
        $results = $connection->createCommand($sql)->queryAll();
        $arr3 = array();
        foreach ($results as $key => $value) {
            $itemname = explode('/', $value['name']);
            if($itemname[0] == $oldMsg){
                $arr3 [] = $value['id'];
            }
        }
        //根据上面检索的id  更新对应的部门名称
        $sql1 = "update developer_AuthAssignment set itemname=replace(`itemname`,'$oldMsg','$newMsg') where id in (".implode(',', $arr1).")";
        $sql2 = "update developer_AuthItemChild set parent=replace(`parent`,'$oldMsg','$newMsg'),child=replace(`child`,'$oldMsg','$newMsg')  where id in (".implode(',', $arr2).")";
        $sql3 = "update developer_AuthItem set name=replace(`name`,'$oldMsg','$newMsg') where id in (".implode(',', $arr3).")";

        try
        {   //事务做更新这件事
            $connection->createCommand($sql1)->execute();
            $connection->createCommand($sql2)->execute();
            $connection->createCommand($sql3)->execute();
            //打印一下日志
            $logContent = "\t\n[".date('Y-m-d,H:i:s')." ]原部门名称：{$oldMsg}--新部门名称：{$newMsg}--更新的developer_AuthAssignment--(".implode(',', $arr1).")\t\n更新的developer_AuthItemChild--(".implode(',', $arr2).")\t\n更新的developer_AuthItem--(".implode(',', $arr3).")";
            $date = date('Y-m-d');
            file_put_contents("/home/work/websites/developer/protected/runtime/updateDepartMsg-{$date}.log",$logContent,FILE_APPEND);
            $transaction->commit();
        }
        catch(Exception $e) // 如果有一条查询失败，则会抛出异常
        {
            $con = $oldMsg."---".$newMsg."\t\n";
            file_put_contents("/home/work/websites/developer/protected/runtime/departtest.txt",$con,FILE_APPEND);
            
            $transaction->rollBack();
        }
    }
    //人员变动自动解除权限
    public function SyncSpeed()
    {
        $db = Yii::app()->db_eel;
        $sql = " select userid from developer_AuthAssignment group by userid";
        $results = $db->createCommand($sql)->queryAll();
        $userids = array();
        foreach ($results as $key => $value) {
            if($value['userid']){
                $uid = $value['userid'];
                $userids []= $uid;
            }
        }
        $ids = implode(',',$userids);
        $results = self::getUserStatus($ids);
        $userLeave = array();
        foreach ($results as $key => $value) {
            if($value->status==2){
                $userLeave[] = $value->id;
            }
        }
        $userLeave = implode(',', $userLeave);
        //delete  and log
        $deleteMsg = '';
        if($userLeave){
            $deletesql = " delete from developer_AuthAssignment where userid in (".$userLeave.")";
            $sql = " select itemname,userid from developer_AuthAssignment where userid in (".$userLeave.")";
            $data = $db->createCommand($sql)->queryAll();
            foreach ($data as $key => $value) {
                $deleteMsg .="{$value['itemname']}-{$value['userid']},";
            }
            $db->createCommand($deletesql)->execute();
        }
        $logContent = "\t\n[".date('Y-m-d H:i:s')."]--delete from developer_AuthAssignment:".$deleteMsg."\t\n";
        file_put_contents("/home/work/websites/developer/protected/runtime/deleteuserleave.log",$logContent,FILE_APPEND);

       // echo "done";

    }

    
    public function getUserStatus($uid) {
        $output = yii::app()->curl->get("http://api.speed.meilishuo.com/user/show?token=e98cfc1a4f23ae1699919c505ae2ba04&id=".$uid);
        $results = (array)json_decode($output['body']);
        if($results){
            $results = $results['data'];
            return $results;
        }else{
            return array();
        }
    }

    public function SyncAuthApply()
    {
        $db = Yii::app()->db_eel;
        $sql = " select applicant,leader from developer_Apply group by applicant";
        $results = $db->createCommand($sql)->queryAll();
        $leaderChange = array();
        foreach ($results as $key => $value) {
            $leaderid = self::getLeaderId($value['applicant']);
            if($value['leader']!=$leaderid&&!empty($leaderid))
            {
                $leaderChange[$value['applicant']] = $leaderid ;
            }
        }
        //update and log
        $updateMsg = '';
        foreach ($leaderChange as $key => $value) {
            $sql = " update developer_Apply set leader = {$value} where applicant = {$key}";
            $db->createCommand($sql)->execute();
            $updateMsg .="{$value}-{$key},";
        }
        $logContent = "\t\n[".date('Y-m-d H:i:s')."]--update developer_Apply:".$updateMsg."\t\n";
        file_put_contents("/home/work/websites/developer/protected/runtime/updateLeader.log",$logContent,FILE_APPEND);

       // echo "done";
    }

    public function getLeaderId($uid) {
        $output = yii::app()->curl->get("http://api.speed.meilishuo.com/user/get_superior?token=e98cfc1a4f23ae1699919c505ae2ba04&id=".$uid);
        $results = (array)json_decode($output['body']);
        if($results){
            $results = $results['data'];
            if(!$results) return '';
            $leader = $results->id;
            return $leader;
        }else{
            return '';
        }
    }

    public function getDepartSecond(){
        $common = new NewCommonManager;
        $depart = $common->getSecondeDepartInfo();

        $var = array();
        foreach ($depart  as $key => $value) {
            $users = $common->getDepartUsers($value['depart_id']);
            if($users){
               $department = $common->getUserDepart($users[0]['id']);
               $var[$value['depart_id']] =  $department;
            }
        }
        return $var;
    }

    //对比信息
    public function getCompareMsg()
    {
        $auth             = new AuthManager();
        $departDetail     = self::getDepartSecond();
        $sdb              = Yii::app()->sdb_eel;
        $db               = Yii::app()->sdb_eel;
        $type             = self::ITEM_TYPE;
        $defaultid        = self::DEFAULT_ID;
        $sql = "select * from developer_AuthItem where type = {$type} and departid <> $defaultid ";
        $results = $sdb->createCommand($sql)->queryAll();
        $depart = array();
        foreach ($results as $key => $value) {
            $depart [$value['departid']] = $value['name'];
        }
        foreach ($depart as $key => $value) {
            if(isset($departDetail[$key])&&$departDetail[$key]!=$value){
               $con = '改变的 id=>'.$key.' 原来的depart=>'.$value.' -----   现在的depart=>'.$departDetail[$key]."\t\n";
               file_put_contents("/home/work/websites/developer/protected/runtime/departMessage.txt",$con,FILE_APPEND);
            }
        }
        foreach ($depart as $key => $value) {
            if(isset($departDetail[$key])&&$departDetail[$key]==$value){
                $con = '没变的 id=>'.$key.' 原来的depart=>'.$value.' -----   现在的depart=>'.$departDetail[$key]."\t\n";
                file_put_contents("/home/work/websites/developer/protected/runtime/departMessage.txt",$con,FILE_APPEND);
            }
        }
        $have = array();
        $nohave = array();
        foreach ($depart as $key => $value) {
            $have [] = $key;
        }
        foreach ($departDetail as $key => $value) {
            if(!in_array($key, $have)){
                $nohave[] = array('id'=>$key,'depart'=>$value);
                $con = '新增的 id=>'.$key.' depart=>'.$value."\t\n";
                file_put_contents("/home/work/websites/developer/protected/runtime/departMessage.txt",$con,FILE_APPEND);
            }
        }
        echo "done";
        //return array('相同'=>$yes,'不相同'=>$no,'没有用'=>$nohave);
    }
}