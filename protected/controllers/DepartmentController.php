<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DepartmentController
 *
 * @author hancheng bai
 */
class DepartmentController  extends Controller
{
    //put your code here
    public function actionIndex($business= '',$username= '',$funname = '') {

        $strOpUser = yii::app()->user->username;
        $arrOpUserInfo = NewCommonManager::getUserByName($strOpUser);
        $arrDepartManagers = $this->auth->getDepartManage($arrOpUserInfo['depart']);
//        print_r($this->auth->getDepartFunctions($arrOpUserInfo['depart']));
//        var_dump($this->auth->getNewAuthFunction(690));
//        exit;
//         $arrDepartManagers = $this->auth->getDepartManage($arrOpUserInfo['depart']);
//        if(!$this->isDepartLeader())
//        {
//            Yii::app()->request->redirect('/site/ApiAuthErrorHandler');
//            exit;
//        }
//        else
//        {
        $arrDepartUsers = $this->common->getDepartUsers(NewCommonManager::getUserDepartId($arrOpUserInfo['id']));
        $arrBusiness = $this->publish->getUserBusiness();
        $this->render('department/index.tpl',array('business'=>$business,'username'=>$username,'funname'=>$funname, 'arrDepartUsers'=>$arrDepartUsers, 'arrBusiness'=>$arrBusiness));
//        }
    }

    public function actionViews($business = '',$username = '',$funname='', $filter=0)
    {
        $strOpUser = yii::app()->user->username;
        $arrOpUserInfo = NewCommonManager::getUserByName($strOpUser);
        $return = array();
        $results = array();
        $data =array();
        $uid = $arrOpUserInfo['id'];
        if(empty($arrOpUserInfo) || $username == '' || empty($uid))
        {
            return;
        }

        $auth = yii::app()->authManager;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 10;
        $intStart = intval($page-1)*$rows;

        $arrDepartFuncs = $this->auth->getDepartFunctions($arrOpUserInfo['depart']);
        $tb = $business."/";
        foreach($arrDepartFuncs as $df)
        {
            if(($p=strpos($df, $tb)) !== FALSE)
            {
                $tmp = array(
                    'id' => $uid,
                    'business' => $business,
                    'name' => substr($df, strlen($tb)),
                    'item' => $df,
                    'username' => $username,
                    'funname' => substr($df, strlen($tb)),
                );
                $results[] = $tmp;
            }
        }

        $intTotal = count($results);
        $results = array_slice($results, $intStart, $rows);
        foreach($results as $k=>$r)
        {
            if($this->auth->checkAccess($r['item'],$uid))
            {
                $results[$k]['status'] = '有权限';
            }
            else
            {
                $results[$k]['status'] = '无权限';
            }
        }


        $return['rows'] = $results;
        $return['total'] = $intTotal;
        header('HTTP/1.1 200 OK');
        echo json_encode($return);exit;
    }

    public function actionRevokeAuth(array $item,$uid)
    {
        $strOpUser = yii::app()->user->username;
        $arrOpUserInfo = $this->common->getUser($strOpUser);
        $strName = NewCommonManager::getUsernameByuid($uid);
        $auth = yii::app()->authManager;
        $arrUser = $this->common->getUser($strName);
        foreach ($item as $key => $value) {
           if($auth->getAuthItem($value['item'])) {
                $auth->revoke($value['item'],$uid);
                SyslogManager::Write($strOpUser, SyslogManager::FUNCTION_REVOKE_USER, $strName, $value['item']);
                //$this->log->printLog($operateUser,$name,'revoke',$value['item'],$time);
            }
        }
        echo json_encode("解除权限成功");
    }


    public function actionSendEmail($business = '',$username = '', $mailto='')
    {
        $strOpUser = yii::app()->user->username;
        $arrOpUserInfo = $this->common->getUser($strOpUser);
        $return = array();
        $results = array();
        $data =array();
        $auth = yii::app()->authManager;
        $uid = $this->auth->getSid($username);
        if(empty($arrOpUserInfo) || $username == '' || empty($uid))
        {
            return;
        }


        $cname = NewCommonManager::getUserRealnameByuid($uid);
        $arrDepartFuncs = $this->auth->getDepartFunctions($arrOpUserInfo['depart']);
        $tb = $business."/";
        foreach($arrDepartFuncs as $df)
        {
            if(($p=strpos($df, $tb)) !== FALSE)
            {
                $tmp = array(
                    'id' => $uid,
                    'business' => $business,
                    'name' => substr($df, strlen($tb)),
                    'item' => $df,
                    'username' => $username,
                    'funname' => substr($df, strlen($tb)),
                    'cname' => $cname,
                );
                if($this->auth->checkAccess($df,$uid))
                {
                    $tmp['status'] = '有权限';
                }
                else
                {
                    $tmp['status'] = '无权限';
                }
                $results[] = $tmp;
            }
        }
        $html = "";
        $html .= "<table border=\"1\" >";
        $html .="<thead><tr><th>用户</th><th>功能名称</th><th>状态</th></tr></thead>";
        $html .="<tbody>";
        foreach($results as $r)
        {
            $html .= "<tr><td>{$r['cname']}</td>";
            $html .= "<td>{$r['item']}</td>";
            $html .= "<td>{$r['status']}</td></tr>";
        }
        $html .="</tbody></table>";
//        $titles = array('功能名', '用户', '状态');
//        $columns = array('name', 'cname', 'status');
//        $strContent = $this->common->bulidHtml($titles, $columns, $results);
        $strSubject = $cname."在".$business."的权限情况";
        $bolRet = $this->mail->sendCommMail($mailto, $strSubject, $html, array(), true);
        header('Content-type: application/json');
        if($bolRet)
        {
            echo json_encode(array('data'=>'发送成功'));
        }
        else
        {
            echo json_encode(array('data'=>'发送失败'));
        }
    }


    private function isDepartLeader()
    {
        $strOpUser = yii::app()->user->username;
        $arrOpUserInfo = $this->common->getUser($strOpUser);
        $arrDepartManagers = $this->auth->getDepartManage($arrOpUserInfo['depart']);
//        $arrOpUserInfo['id']=443;
        if(empty($arrDepartManagers) || !in_array($arrOpUserInfo['id'], $arrDepartManagers))
        {
            return FALSE;
        }
        return TRUE;
    }
}
