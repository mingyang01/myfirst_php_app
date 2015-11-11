<?php
/**
*
*  测试问件，请勿使用
*
*/
class PrivateTestController extends Controller
{
    public function actionMenu($uid, $business=null, $domain=true, $cbusiness='global') {
        $result = array("code"=>1, "message"=>"", "data"=>array());
        if (!$uid) {
            $result['code'] = 0;
            $result['message'] = "param uid is required!";
            echo json_encode($result);
            yii::app()->end();
        }
        echo json_encode($this->getMenuTest($business, $uid, $domain, true, $cbusiness));
    }

    public  function getMenuTest($business, $uid, $domain=true,$status=true,$cbusiness='global') {

        $where = '';
        $url = "CONCAT('http://' , b.description, a.url) url";
        if($business) {
            $where .= " where a.business='$business'";
        }

        if(!$domain || $domain == 'false')
            $url = " a.url url";
        $whole = array();
        $db = Yii::app()->sdb_eel;
        $results = array();
        $sql = "select b.work, b.business ,a.id, b.item point , b.url, first, second, third
        from developer_menu a left join(
select b.`cname` business , b.`business` work ,a.id id, $url, a.item
from developer_function a, developer_business b where a.business = b.business) b
on a.function = b.id" . $where." order by rank desc";
        $items = $db->createCommand($sql)->queryAll();

        $function = new FunctionManager();
        $businesses = $is_developer = array();
        if ($business == 'global') {
            foreach ($items as $val) {
                $businesses[$val['work']] = $val['work'];
                $is_developer[$val['work']] = $function->isDeveloperByid($val['work'], $uid);
            }
        }

        $have = array();
        $auth = new AuthManager();
        $col = new CollectManager();
        $remove = new RemoveAuthManager();
        $haveItem = $auth->getNewAuthFunction($uid);
        $developerFlag = $function->isDeveloperByid($business,$uid);
        $superFlag = $auth->isSupper($uid);
        $collect = $col->collet($uid,$domain, $cbusiness);
        if ($business == 'global') {
            $white_lists = $auth->whitelists();
        } else {
            $white_lists = $auth->whitelists($business);
        }

        foreach ($items as $key=>$item) {
            $mkey = $item["first"] .'/'. $item["second"];
            $removeAuth = $remove->getRemoveAuth($uid, $item['point']);
            if($removeAuth){
                unset($items[$key]);
                continue;
            }
            $is_strick = $auth->strickFunction($item['point']);
            if((!$superFlag && !$developerFlag && !isset($white_lists[$item['point']])) || $is_strick) {
                if ($business == 'global' && $is_developer[$item['work']]) {
                    if(!isset($results[$mkey])) {
                        $results[$mkey] = array();
                        $results[$mkey]["name"] = $item["second"];
                        $results[$mkey]["url"] = $item["url"];
                        $results[$mkey]["child"] = array();
                        $results[$mkey]["business"] = $item["work"];
                        $results[$mkey]["id"] = $item["id"];
                    }

                    if (trim($item["third"])) {
                        $results[$mkey]["child"][] = array("id"=>$item["id"],"name"=>$item["third"], "url"=>$item["url"], "child"=>array(),"business"=>$item["work"]);
                        if (count($results[$mkey]["child"]) > 1) {
                            $results[$mkey]["url"] = '#';
                            unset($items[$key]);
                        }
                    }
                    continue;
                } else {
                    if(!in_array($item['point'], $haveItem)) {
                        unset($items[$key]);
                        continue;
                    }
                }
            }

            if(!isset($results[$mkey])) {
                $results[$mkey] = array();
                $results[$mkey]["name"] = $item["second"];
                $results[$mkey]["url"] = $item["url"];
                $results[$mkey]["child"] = array();
                $results[$mkey]["business"] = $item["work"];
                $results[$mkey]["id"] = $item["id"];
            }


            if (trim($item["third"])) {
                $results[$mkey]["child"][] = array("id"=>$item["id"],"name"=>$item["third"], "url"=>$item["url"], "child"=>array(),"business"=>$item["work"]);
                if (count($results[$mkey]["child"]) > 1) {
                    $results[$mkey]["url"] = '#';
                    unset($items[$key]);
                }
            }

        }


        foreach ($items as $key => $value) {
            if(!isset($whole[$value["first"]])) {
                $whole[$value["first"]] = array();
                $whole[$value["first"]]["name"] = $value["first"];
                $whole[$value["first"]]["url"] = $value["url"];
                $whole[$value["first"]]["child"] = array();
                $whole[$value["first"]]["business"] = $value["work"];
                $whole[$value["first"]]["id"] = $value["id"];
            }

            if (trim($value["second"])) {
                $vkey = $value["first"] .'/'. $value["second"];
                $whole[$value["first"]]["child"][] = $results[$vkey];
            }
        }
        $whole['收藏夹']["name"] = "收藏夹";
        $whole['收藏夹']["url"] = "#";
        $whole['收藏夹']["child"] = array();
        $whole['收藏夹']["business"] = $business;
        $length = count($collect);
        if($length==0){
            $whole['收藏夹']["child"] [] = array('name'=>'暂无收藏','url'=>"#",'child'=>array());
        }
        foreach ($collect as $key => $value) {
             $whole['收藏夹']["child"] [] = $value;
        }
        array_unshift($whole, array_pop($whole));
        return array_values($whole);
    }

    public function checkAccess($operation, $uid,$strtolower =true, $params=array())
    {

        if(!$strtolower || $strtolower == 'false')
        {
            $operation = strtolower($operation);
        }
        $auth = yii::app()->authManager;
        $business = explode('/', $operation);
        $business = $business[0];
        $removeAuth = RemoveAuthManager::getRemoveAuth($uid,$operation);
        if($removeAuth){
            return false;
        }

        $isstrick = 1;
        //权限严格验证部分功能
        if (!$isstrick) {
            if($this->superRule($uid) || $this->function->isDeveloper($business,NewCommonManager::getUsernameByuid($uid)) || $this->whitelistRule($operation))
            {
                return true;
            }
        }

        $access=$auth->checkAccess($operation,$uid);
        //$access=Yii::app()->getAuthManager()->checkAccess($operation,$uid);
        return $access;
    }

    public function actionCheckAccess($point, $uid, $params=""){
        if($params)
            @$params = json_decode($params, true);

    $db = yii::app()->db_eel;
    $sql = "select controller,action from developer_whitelist";
    $results = $db->createCommand($sql)->queryAll();
    $options = array();
    foreach ($results as $key => $value) {
        $options []= strtolower($value['controller'])."/".strtolower($value['action']);
    }
    $auth = yii::app()->authManager;
    $whiteListFlag = in_array(strtolower($point), $options);
    if(!$auth->getAuthItem($point)&&!$whiteListFlag) {
        $message ='你没有该功能的权限，<a href="developer.meiliworks.com/auth/distribution" target="_blank">请点击跳转到申请页</a>申请权限,如还有问题请联系运营人员：<a href="mailto:dongpingsui@meilishuo.com">dongpingsui@meilishuo.com</a>；或者开发人员：<a href="mailto:linglingqi@meilishuo.com">linglingqi@meilishuo.com</a>';
        $result = array("code"=>0, "message"=>$message, "data"=>array());
        echo json_encode($result);
    }
    else
    {
        $sign = explode("action/", $point);
        if(count($sign)>0)
        {
                $sign = $sign[1];
            }
            else
            {
               $sign =  $point;
            }
            //$funname = $this->function->getFunname($sign);
            $funname = " ";
            $result = array("code"=>1,"function"=>$funname, "message"=>"", "data"=>array());
            $result['data']['checked'] = $this->checkAccess($point, $uid, $params);
            echo json_encode($result);
        }

    }

    public function strickFunction($operate) {
        $stricks = ConfigManage::strickCheckFunction();
        if (isset($stricks[$operate])) {
            return true;
        }else {
            return false;
        }
    }

}