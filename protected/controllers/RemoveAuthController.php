<?php

class RemoveAuthController extends Controller
{
    public function actionIndex()
    {
        $request = Yii::app()->request;
        $business = $request->getParam('business','');
        $functionName = $request->getParam('functioname','');
        $user = $request->getParam('user','');
        $msg = '';
        if($functionName&&$user&&$business){
            $msg = RemoveAuthManager::removeAuth($business,$functionName,$user);
        }else{
            $msg = "用户，功能都不能为空哦！";
        }
        $project = $this->publish->getUserBusiness();
        $params = array(
            'project'=>$project,
            'business'=>$business,
            'functioname'=>$functionName,
            'user'=>$user,
            'msg'=>$msg
            );
        $this->render('removeauth/index.tpl',$params);
    }

    public function actionRecover()
    {
        $data = $this->removeAuth->getAllRemove();

        $auth = new AuthManager();
        foreach ($data as $key => $value) {
            $data[$key]['user']=NewCommonManager::getUsernameByuid($value['user']);
            $data[$key]['op_time']=date('Y-m-d H:i:s',$value['op_time']);
        }
        $params = array('data'=>$data);
        $this->render('removeauth/recover.tpl',$params);
    }

    public function actionRecoverAuth()
    {
        $request = Yii::app()->request;
        $ids = $request->getParam('ids','');
        $db = Yii::app()->db_eel;
        $sql = "delete from developer_remove_auth where id in (".$ids.")";
        $db->createCommand($sql)->execute();
        echo json_encode("恢复成功");
    }

    //解除某人的全部权限
    public function actionRemoveAll(){
        $request = yii::app()->request;
        $username = $request->getParam('user','');
        $remove = $request->getParam('remove',false);
        $op_user = yii::app()->user->username;
        $userAuth = '';
        if($username){
            $usermsg = $this->NewCommon->getUserByName($username);
            $uid = $usermsg['id'];
            $userAuth = $this->removeAuth->getUserAuth($uid,$username,$op_user,$remove);
        }
        $this->render('removeauth/removeall.tpl',array('userAuth'=>$userAuth,'user'=>$username));
    }
}