<?php

class MenuTreeController extends Controller
{
	public function actionIndex($business="")
	{
        $menuTree = $this->menuTree->getMenuTree($business);
        $functions = $this->function->getFunctions($business);
        $username = yii::app()->user->username;
        $project = $this->publish->getProjectWithUserFilter($username);
        $this->render("menu/menu-tree.tpl",array('menuTree'=>$menuTree,"business"=>$business,"functions"=>$functions,"project"=>$project));
	}

	public function actionAddMenuTree()
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
                    else if(empty($second)&&empty($third))
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
                        $data = array("msg"=>"菜单重复","id"=>$results[0]['id'],"flag"=>false);
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
                        $first = $_POST['first'];
                        $second = $_POST['second'];

                        $sql = "select second from developer_menu where first ='$first' ";
                        $results = $db->createCommand($sql)->queryAll();
                        if(count($results)>1)
                        {
							$sql = "select id from developer_menu where first ='$first' and second='' ";
							$results = $db->createCommand($sql)->queryAll();
							if($results)
							{
								$id = $results[0]['id'];
								$sql = "delete from developer_menu where id ='$id' ";
								$db->createCommand($sql)->execute();
							}

                        }

                        $sql = "select second from developer_menu where first ='$first' and second ='$second'  ";
                        $results = $db->createCommand($sql)->queryAll();
                        if(count($results)>1)
                        {
							$sql = "select id from developer_menu where first ='$first' and second='$second' and third='' ";
							$results = $db->createCommand($sql)->queryAll();
							if($results)
							{
								$id = $results[0]['id'];
								$sql = "delete from developer_menu where id ='$id' ";
								$db->createCommand($sql)->execute();
							}

                        }

                    }
                    
                
            } catch (Exception $e) {
                echo $e;
            }
           
            $data = array("msg"=>"添加成功","flag"=>true);
        }

        echo json_encode($data);
	}
}
