<?php
/**
 * 用户审计for平台
 * @author mingyang
 * @version 2015-09-15
 */
class AuditManager extends Manager {

    public static $table_name = "developer_audit_log";

    public static $file = array(
    		'audit_url'	=>	1,
    		'business'	=>	1,
    		'audit_user'=>	1,
    		'audit_ip'	=>	1,
    		'audit_flag'=>	0,
    		'status'	=>	0,
    		'fid'		=>	1,
    		'unix'		=>	0,
    );

    public function getAuditLogByBusiness($business,$user = '',$start_time = '',$end_time = '',$condition)
    {
        $db = Yii::app()->sdb_eel;
        $where = "where 1=1 ";
        empty($business)||($where .=" and business='{$business}' ");
        empty($user)||($where .=" and audit_user = '$user' ");
        empty($start_time)||($where .=" and unix >= $start_time");
        empty($end_time)||($where .=" and unix <= $end_time");
        if(!empty($condition)){
            $item = explode('-', $condition);
            $where .=" order by {$item[0]} {$item[1]}";
        }
        $table_name = self::$table_name."_201509";
        $sql = "select * from $table_name ".$where;
        $results = $db->createCommand($sql)->queryAll();
        return $results;
    }
    //获得每个平台的访问量
    public function getBusinessDetail($start_unix,$end_unix)
    {
        $db = Yii::app()->sdb_eel;
        $where = " where 1=1 ";
        empty($start_unix)||($where .=" and unix >= $start_unix");
        empty($end_unix)||($where .=" and unix <= $end_unix");
        $table_name = self::$table_name."_201509";
        $sql = "select business,count(audit_user) as pv ,count(distinct audit_user) as uv from $table_name".$where." group by business";
        $results = $db->createCommand($sql)->queryAll();
        $data = array();
        foreach ($results as $key => $value) {
            $data[$value['business']]['uv'] = $value['uv'];
            $data[$value['business']]['pv'] = $value['pv'];
        }
        return $data;
    }
    //获得某个平台访问情况
    public function getPvOfBusiness($business,$start_unix,$end_unix)
    {
        $db = Yii::app()->sdb_eel;
        $where = " where business='$business' ";
        empty($start_unix)||($where .=" and unix >= $start_unix");
        empty($end_unix)||($where .=" and unix <= $end_unix");
        $table_name = self::$table_name."_201509";
        $sql = "select business,audit_user,count(audit_user) as uv from $table_name".$where." group by audit_user";
        $results = $db->createCommand($sql)->queryAll();
        return $results;
    }

    public function VisterLog($name, $url, $business, $ip, $funname, $flag) {

    	$data = array(
    			'audit_url'	=>	$url,
    			'business'	=>	$business,
    			'audit_user'=>	$name,
    			'audit_ip'	=>	$ip,
    			'audit_flag'=>	$flag,
    			'fid'	=>	$funname,
    			'unix'		=>	time(),
    	);

    	$db = Yii::app()->db_eel;
    	$table_name = self::$table_name."_201509";
		return $db->createCommand()->insert($table_name, $data);

    }
}