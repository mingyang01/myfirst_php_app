<?php
class CollectManager extends Manager {

    public function GetCollect($domainstatus)
    {
        $db = yii::app()->db_eel;
        $sql = "select u.tmp, i.menu_title ,i.menu_url,i.domainname,u.user_id from t_eel_admin_user u,t_eel_admin_menu_info i ,t_eel_admin_collect c where c.user_id=u.user_id and i.menu_id=c.menu_id";
        $results = $db->createCommand($sql)->queryAll();
        $reData = array();
        $collect = array();
        $domain = self::getDomain();
        $getBusiness = self::getBusiness();
        foreach ($results as $key => $value) {
            if($value['tmp'])
            {
                $url = $domain[$value['domainname']].$value['menu_url'];
                $business = $getBusiness[$value['domainname']];
                if(!$domainstatus||$domainstatus=='false')
                {
                    $url = $value['menu_url'];
                }

                $reData[$value['tmp']] []= array("menu_title"=>$value['menu_title'],"url"=>$url,"domainname"=>$value['domainname'],"business"=>$business);
        
            }
        }
        return $reData;
    }

    public function getDomain()
    {
        $results = array("old"=>'http://work.meiliworks.com',"new"=>'http://works.meiliworks.com','pro'=>'http://brdht.meiliworks.com');
        return $results;
    }

    public function getBusiness()
    {
        $results = array("old"=>'work',"new"=>'works','pro'=>'brdht');
        return $results;
    }

    public function collet($uid,$domain, $cbussines='global')
    {
        $result = array("code"=>1, "message"=>"", "data"=>array());
        if (!$uid) {
            $result['code'] = 0;
            $result['message'] = "param uid is required!";
            echo json_encode($result);
            yii::app()->end();
        }
        $reArray = array();
        $allCollect = self::getCollectNew($domain, $cbussines);
        $userCollect = isset($allCollect[$uid])?$allCollect[$uid]:'';
        if (!$userCollect) {
        	return array();
        }
        foreach ($userCollect as $key => $value) {
            $reArray [] = array("name"=>$value['menu_title'],"url"=>$value['url'],"child"=>array(),"business"=>$value['business']); 
        }
        return $reArray;
    }

    public function getCollectNew($domainstatus, $cbussines='global')
    {
    	
        $db = yii::app()->db_eel;
        if (!$cbussines || $cbussines=='global') {
        	$sql = "select * from developer_collect_menu";
        } else {
        	$sql = "select * from developer_collect_menu where business='".$cbussines ."'";
        }
        
        $results = $db->createCommand($sql)->queryAll();
		if (!$results) {
			return array();
		}
        $reData = array();
        foreach ($results as $key => $value) {
            if($value['userid'])
            {
                $url = $value['domain'].$value['url'];
                $business = $value['business'];
                if(!$domainstatus||$domainstatus=="false")
                {
                    $url = $value['url'];
                }
                $reData[$value['userid']][]= array("menu_title"=>$value['setName'],"url"=>$url,"domainname"=>$value['domain'],"business"=>$value['business']);
            }
        }
        return $reData;
    }
    
    /**
     * 删除业务下某个功能的所有用户的收藏
     */
    public function deleteCollect($business, $itemName) {
    	
    	$db = yii::app()->db_eel;
    	$sql = "delete from developer_collect_menu where business='$business' and itemName='$itemName' ";
    	$ret = $db->createCommand($sql)->execute();
    	return $ret;
    }

}
