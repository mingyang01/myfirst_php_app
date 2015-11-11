<?php

class MenuController extends Controller {
	public function actionIndex($business="developer") {

		$project = $this->publish->getUserBusiness();
		foreach ($project as $key=>$val) {
			if($val['business'] == 'data平台') {
				unset($project[$key]);
			}
		}

        if (!count($project)) {
            $this->render('error/404.tpl', array('message'=>"没有权限访问此页面！"));
            Yii::app()->end();
        }

        if ($business == "developer" && !isset($project[$business])) {
            $business = $project[0];
            $business = $business['business'];
        }

        $functions = $this->function->getFunctions($business);
        $this->render("menu/index.tpl",array("business"=>$business,
            "functions"=>$functions,
            "project"=>$project));
    }

    public function actionGetMenuList($business="",$funname="") {

        $project = $this->publish->getProjectWithUserFilter($this->user->username);
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $db = Yii::app()->sdb_eel;
        $like = '';
        if($funname) {
            $like = " and (first like '%$funname%' or second like '%$funname%' or third like '%$funname%' ) ";
        }
        $sql="select * from developer_menu where business ='$business' ".$like;
        $total =$db->createCommand($sql)->queryAll();
        $total = count($total);
        $sql="select * from developer_menu";
        $where = " where business='$business' ".$like." limit $offset,$rows";
        $sql .= $where;
        $items = $db->createCommand($sql)->queryAll();

        $fun = new FunctionManager();
        foreach ($items as $key=>$ival) {
        	$tmp_function = $fun->getFunctionInfoById($ival['function']);
        	$items[$key]['funname'] = $tmp_function['funname'];
        }

        $functions = $this->function->getFunctions($business);
        $results = array("rows"=>$items,"total"=>$total, "business"=>$business,
            "functions"=>$functions,
            "project"=>$project);
        echo json_encode($results);
    }

    public function actionAdd()
    {
        $results="";

        $function = $_POST['function'];
        $url = '';
        $sign = '';

        if(!empty($_POST['business'])&&!empty($_POST['first']))
        {

            $db = Yii::app()->db_eel;
            try {
                    $business = $_POST['business'];
                    $first = $_POST['first'];
                    $second = $_POST['second'];
                    $third = $_POST['third'];
                    if(empty($third))
                    {
                        $sql = "select * from developer_menu where business ='$business' and first='$first' and second='$second' ";
                        $results = $db->createCommand($sql)->queryAll();
                    }
                    else if(empty($second))
                    {
                        $sql = "select * from developer_menu where business ='$business' and first='$first' ";
                        $results = $db->createCommand($sql)->queryAll();
                    }
                    else
                    {
                        $sql = "select * from developer_menu where business ='$business' and first='$first' and second='$second' and third='$third' ";
                        $results = $db->createCommand($sql)->queryAll();
                    }
                    if(!empty($results))
                    {
                        $data = array("msg"=>"菜单重复","id"=>$results[0]['id']);
                        echo json_encode($data);
                        return;
                    }
                    else
                    {
                        $sql = "insert into developer_menu (business,first,second,third,creator,url,sign,function) values(?,?,?,?,?,?,?,?)";
                        $db->createCommand($sql)->execute(
                        array($_POST['business'],$_POST['first'],$_POST['second'],$_POST['third'],
                        '',$url,$sign,$function)
                        );

                    }


            } catch (Exception $e) {
                echo $e;
            }

            $data = array("msg"=>"添加成功");
        }

        echo json_encode($data);
    }

    public function actionUpdata() {

    	$username = yii::app()->user->name;
        $db = Yii::app()->db_eel;
        $sql = "update developer_menu set business = ?, first = ?,
            second = ?, third = ?,function=?,creator=? where id =? ";
        $db->createCommand($sql)->execute(array($_POST["business"], $_POST["first"],
            $_POST["second"],$_POST['third'],$_POST["function"],
            $username,$_POST['id']));
        $data = array("msg"=>"更新成功");
        echo json_encode($data);


    }

    public function actionDelete()
    {
        $db = Yii::app()->db_eel;
        $sql = "delete from developer_menu where id = ".$_POST['id'];
        $db->createCommand($sql)->execute();
        $data = array("msg"=>"删除成功");
        echo json_encode($data);
    }

    public function actionGetFunction($business)
    {
        $db = Yii::app()->db_eel;
        $sql = "select funname,id from developer_function where business = '$business' ";
        $results = $db->createCommand($sql)->queryAll();
        echo json_encode($results);
    }

    public function actionGetMessage($functionid)
    {
        $db = Yii::app()->db_eel;
        $sql = "select developer_business.business,developer_function.id, developer_function.funname,developer_menu.function,developer_function.business,developer_business.cname from developer_business,  developer_function,developer_menu where developer_business.business=developer_function.business and developer_menu.function=developer_function.id and developer_menu.function='$functionid' ";
        $results = $db->createCommand($sql)->queryAll();
        // $function = array();
        $business = $results[0]['business'];
        // //$function['functionid'] = $results[0]['function'];
        // $function['funname'] = $results[0]['funname'];
        $sql = "select funname,id from developer_function where business ='$business' ";
        $functions = $db->createCommand($sql)->queryAll();
        $sql = "select business,cname from developer_business";
        $businesses = $db->createCommand($sql)->queryAll();
        // $results = array("business"=>$business,"funnow"=>$function);
        $results = array("data"=>$results[0],"functions"=>$functions,"businesses"=>$businesses);
        echo json_encode($results);
    }

    public function actionMenuTree()
    {
        $this->render("menu/menu-tree.tpl");
    }
}
