<?php

class PublishController extends Controller
{
    public function actionIndex() {

        $results = $this->publish->getUserBusiness();
        $this->render("publish/index.tpl",array("redata"=>$results));
    }

    public function actionAddBusiness()
    {
        $msg = "";
        if(!empty($_POST['name'])&&!empty($_POST['leader'])&&!empty($_POST['developer']))
        {
            $desc = "";
            if(!empty($_POST['desc']))
            {
              $desc = $_POST['desc'];
            }
            $creator=$this->user->name;
            $db = Yii::app()->db_eel;
            $sql = "insert into developer_business (business, developer, leader, description, unix,cname,creator) values(?,?,?,?,?,?,?)";
            $results = $db->createCommand($sql)->execute(array($_POST['name'],$_POST['developer'],$_POST['leader'],$desc,date('Y-m-d H:m:s'),$_POST['cname'],$creator));
            $msg = "添加成功";
        }
        else
        {
            $msg = "添加出错";
            echo json_encode($msg);
        }
        echo json_encode($msg);
    }

    public function actionDeleBusiness() {

        $db = Yii::app()->db_eel;

        $username = Yii::app()->user->username;

        $transaction=$db->beginTransaction();
        try {

        	$sql = "delete from developer_business where id = ".$_POST['id'];
        	$results  = $db->createCommand($sql)->execute();

        	$transaction->commit();
        } catch (Exception $e) {
        	$transaction->rollBack();
        	echo json_encode('删除失败！');
        	exit;
        }

        echo json_encode("删除成功");
    }

    public function actionUpdataBusiness() {

        $desc = "";
        if(!empty($_POST['desc'])) {
          $desc = $_POST['desc'];
        }
        $db = Yii::app()->db_eel;

        if(!empty($_POST["name"]) &&!empty($_POST["developer"]) &&!empty($_POST["leader"]) &&!empty($_POST["cname"])) {

            $sql = "update developer_business set business = ?, developer = ?, leader = ?, description = ?,unix=?,cname=?,creator=? where id =? ";
            $db->createCommand($sql)->execute(array($_POST["name"], $_POST["developer"], $_POST["leader"],$desc,date('Y-m-d H:m:s'),$_POST['cname'],Yii::app()->user->username,$_POST['id']));
            echo json_encode("更新成功");
        } else {
            echo json_encode("更新失败");
        }



    }

    public function actionAddItem()
    {
        $db = yii::app()->sdb_eel;
        $sql = "select * from developer_functionItem";
        $results = $db->createCommand($sql)->queryAll();
        if(!empty($results))
        {
            for ($i=0; $i < count($results); $i++) {
                $sql = "select business ,funname from developer_function where id = ".$results[$i]['businessId'];
                $rename = $db->createCommand($sql)->queryAll();
                if(!empty($rename))
                {

                 $results[$i]['function'] = $rename[0]['funname'];
                 $results[$i]['name'] = $rename[0]['business'];
                }
            }

        }
        $this->render("publish/additem.tpl",array("redata"=>$results));
    }
}
