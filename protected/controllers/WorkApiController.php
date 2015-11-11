<?php

class WorkApiController extends Controller
{

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array("GetItemName","InsertCollect","GetCollection","AddCollection","DeleteCollection"),
                'users'=>array('?'),
            ),

            array('deny',
                'users'=>array('?'),
            ),
        );
    }

    public function actionGetItemName($url,$sid,$jsoncallback)
    {
        $functionName = $this->function->urlToFunctionName($url,$sid);
        $menu = $this->function->getMenuByUrl($url,$sid);
        $results = array();
        if($functionName)
        {
            $results = array('code'=>1,'results'=>$functionName);
            if($menu) 
            {
                $results = array('code'=>1,'results'=>$menu);
            }
        }
        else
        {
            $results = array('code'=>0,'message'=>" $url may be not exist! ");
        }
        echo $jsoncallback."(".json_encode($results).")";
    }

    public function actionInsertCollect($sid,$itemName,$business,$realName,$url,$jsoncallback)
    {
        $db = yii::app()->db_eel;
        $collectColmun = $this->work->getCollect($sid);
        
        if(!empty($url)&&!empty($realName))
        {
            if(in_array($itemName,$collectColmun[$business]))
            {
                echo $jsoncallback."(".json_encode("添加成功！").")";
                return ;
            }
            $sql = "select b.description from developer_function f,developer_business b where f.business=b.business and f.funname='$itemName' and f.business='$business' ";
            $results = $db->createCommand($sql)->queryAll();
            if($results)
            {
                $results = $results[0];
                $domain = "http://".$results['description'];
            }
            $sql = "insert into developer_collect_menu (itemName,url,userid,setName,domain,unix,business) values(?,?,?,?,?,?,?)";
            $db->createCommand($sql)->execute(array($itemName,$url,$sid,$realName,$domain,date('Y:m:d H:m:s'),$business));
        }
        else
        {
            echo $jsoncallback."(".json_encode(" url 和标题不能为空").")";
        }
        echo $jsoncallback."(".json_encode("添加成功！").")";
        
    }

    public function actionDeleteCollect($sid,$itemName,$business,$jsoncallback)
    {
        $db = yii::app()->db_eel;
        $sql = "delete from developer_collect_menu where userid ='$sid' and business='$business' and itemName='$itemName' ";
        $db->createCommand($sql)->execute();
        echo $jsoncallback."(".json_encode("删除成功").")";
    }

    //  public function actionCollectExport()
    // {
    //     $db = yii::app()->db_eel;
    //     $collect = $this->collect->GetCollect();
    //     $domain = $this->getDomain();
    //     $busines = $this->getBusiness();
    //     foreach ($collect as $key => $va) {
    //         foreach ($va as $ke => $value) {
    //             $sql = "insert into developer_collect_menu (itemName,url,userid,setName,domain,unix,business) values(?,?,?,?,?,?,?)";
    //             $db->createCommand($sql)->execute(array($value['menu_title'],$value['url'],$key,$value['menu_title'],$domain[$value['domainname']],date('Y:m:d H:m:s'),$busines[$value['domainname']]));
        
    //         }
    //     }
    // }

    // public function getDomain()
    // {
    //     $results = array("old"=>'http://work.meiliworks.com',"new"=>'http://works.meiliworks.com','pro'=>'http://brdht.meiliworks.com');
    //     return $results;
    // }

    // public function getBusiness()
    // {
    //     $results = array("old"=>'work',"new"=>'works','pro'=>'brdht');
    //     return $results;
    // }

    // public function actionshow()
    // {
    //     var_dump($this->collect->collet('172',1));
    // }

    public function actionGetCollection($url='',$userid='',$business = '')
    {
        $functionName = $this->function->urlToFunctionName($url,$userid,$business);
        $menu = $this->function->getMenuByUrl($url,$userid,$business);
        $results = array();
        if(!empty($url)&&!empty($userid))
        {
            if($functionName)
            {
                $results = array('code'=>1,'results'=>$functionName);
                if($menu) 
                {
                    $results = array('code'=>1,'results'=>$menu);
                }
            }
            else
            {
                $results = array('code'=>0,'message'=>" $url may be not exist! ");
            }
            
        }
        else
        {
            $results = array('code'=>0,'message'=>" param url and  userid  is required! ");
            
        }
        //echo $jsoncallback."(".json_encode($results).")";
        echo json_encode($results);
    }

    public function actionAddCollection($userid='',$itemName='',$url='',$business='')
    {
        $db = yii::app()->db_eel;
        $collectColmun = $this->work->getCollect($userid);
        
        if(!empty($url)&&!empty($userid)&&!empty($itemName)&&!empty($business))
        {
            if(in_array($itemName,$collectColmun[$business]))
            {

                echo json_encode(array('code'=>0,"message"=>'add already!'));
                //echo $jsoncallback."(".json_encode("添加成功！").")";
                return ;
            }
            $sql = "select b.description from developer_function f,developer_business b where f.business=b.business and f.funname='$itemName' and f.business='$business' ";
            $results = $db->createCommand($sql)->queryAll();
            if($results)
            {
                $results = $results[0];
                $domain = "http://".$results['description'];
                $sql = "insert into developer_collect_menu (itemName,url,userid,setName,domain,unix,business) values(?,?,?,?,?,?,?)";
                $db->createCommand($sql)->execute(array($itemName,$url,$userid,$itemName,$domain,date('Y:m:d H:m:s'),$business));
                echo json_encode(array('code'=>1,"message"=>'add success!'));
            }
            else
            {
                echo json_encode(array('code'=>0,"message"=>'have no $itemName function in $business '));
            }
            
        }
        else
        {
            echo json_encode(array('code'=>0,"message"=>'param url ,business ,  itemName and userid is  required!'));
            //echo $jsoncallback."(".json_encode(" url 和标题不能为空").")";
        }
        //echo $jsoncallback."(".json_encode("添加成功！").")";
        
    }

    public function actionDeleteCollection($userid ='',$itemName='',$business='')
    {
        if(!empty($userid)&&!empty($itemName)&&!empty($business))
        {
            $db = yii::app()->db_eel;
            try {
                $sql = "select * from developer_collect_menu where userid ='$userid' and business='$business' and itemName='$itemName' ";
                $results = $db->createCommand($sql)->queryAll();
                if($results)
                {
                    $sql = "delete from developer_collect_menu where userid ='$userid' and business='$business' and itemName='$itemName' ";
                    $db->createCommand($sql)->execute();
                    echo json_encode(array('code'=>1,"message"=>'delete success!'));
                }
                else
                {
                    echo json_encode(array('code'=>0,"message"=>'have no this collection'));
                }
                
            } catch (Exception $e) {
                 echo json_encode(array('code'=>0,"message"=>'delete fail!'));
            }
            //echo $jsoncallback."(".json_encode("删除成功").")";
            
        }
        else
        {
            echo json_encode(array('code'=>0,"message"=>'param business ,  itemName and userid is  required!'));
        }
        
    }


}