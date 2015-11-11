<?php
/**
 * 检测角色接口
 * @author mingyang@meilishuo.com
 * @version 2015-7-22
 */



class ApiForTuanController extends Controller
{   
     public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions'=>array("CheckRole"),
                'users'=>array('?'),
            ),

            array('deny',
                'users'=>array('?'),
            ),
        );
    }

    public function ActionCheckRole($role = '',$sid = '') 
    {
        /**
         * 检测某人是否具有某个角色
         * @param $role 角色名称，$sid用户的speed id
         */
        $auth = yii::app()->authManager;
        if(!empty($sid))
        {
            $roles = $auth->getAuthItems('2',$sid);
            $reDate = array();
            foreach ($roles as $key => $value) {
                $reDate[] = $key;
            }
            $flag = in_array($role, array_values($reDate));
            $results = array('code'=>1,'checkStatus'=>$flag);
        }
        else
        {
            $results = array('code'=>0,'message'=>"params sid is required");
        }
        echo json_encode($results);
    }
}
