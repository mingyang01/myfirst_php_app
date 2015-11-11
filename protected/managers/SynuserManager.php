<?php
class SynuserManager extends Manager {
	public function Synuser()
    {
        $db = yii::app()->db_eel;
        $sql = "select * from t_eel_admin_user";
        $results = $db->createCommand($sql)->queryAll();
        $staffArray = $this->common->getAllStaff('false');
        $allStaff = $this->common->getSpeedId('false');
        $eel_user = array();
        $redata = array();
        $haveno = array();
        foreach ($allStaff as $key => $value) {
            $allStaff[strtolower($key)] =$value;
        }
        foreach ($results as $key => $value) {
            $username = strtolower($value['username']);
            if(isset($allStaff[$username]))
            {
                $user_id = $value['user_id'];
                $speedid = $allStaff[$username];
                $sql = "update t_eel_admin_user set tmp =? where user_id =?";
                $db->createCommand($sql)->execute(array($speedid,$user_id));
                $redata [] =array('code'=>1,'message'=>"Update user '$username' success");
            }
            else
            {
                $haveno [] =array('code'=>0,'message'=>"Have no this '$username' in speed");
            }
            $eel_user [] = strtolower($value['username']);
            
        }

        foreach ($staffArray as $key => $value) {
            $speed_user = strtolower($value['username']);
            if(!in_array($speed_user, $eel_user))
            {
                $sql = "insert into t_eel_admin_user (username,user_type,ctime,tmp,password,used_path,realname) values(?,?,?,?,?,?,?)";
                $db->createCommand($sql)->execute(array($speed_user,'1',date('Y:m:d H:m:s'),$value['id'],'','1',$value['name']));
                $redata [] =array('code'=>1,'message'=>"insert user '$speed_user' success");
            }
        }
        $redata = array("success"=>$redata,"fail"=>$haveno);
        return $redata;
    }

    public function SynDepartMessage()
    {
        $staffArray = $this->common->getAllStaff('false');
        $results = array();
        foreach ($staffArray as $key => $value) {
            $results[$value['id']] = $value;
        }
        $db = yii::app()->db_eel;
        $sql = "select * from t_eel_admin_user";
        $data = $db->createCommand($sql)->queryAll();
        foreach ($data as $key => $value) {
            if($value['tmp'])
            {
                if(isset($results[$value['tmp']])){
                    $user = $results[$value['tmp']];
                    $departid = isset($user['departid'])?$user['departid']:0;
                    $departname = isset($user['depart'])?$user['depart']:'';
                    $sql = "update t_eel_admin_user set departid =? ,departname=? where tmp =?";
                    $db->createCommand($sql)->execute(array($departid,$departname,$value['tmp']));
                }
                
            }
        }
    }
}
