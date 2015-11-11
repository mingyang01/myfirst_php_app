<?php

class ItemController extends Controller
{
	function numToAuthority($num)
	{
		switch($num)
		{
			case 0:return "普通";
			case 1:return "共享";
			case 2:return "特殊";

		}
	}

	public function actionIndex()
	{
		$msg = array();
		$db = Yii::app()->sdb_eel;
		$functionid = $_GET['functionid'];
		$sql = "select * from developer_AuthItem,developer_functionItem where developer_functionItem.item=developer_AuthItem.name";
		$results = $db->createCommand($sql)->queryAll();
		foreach ($results as $key => $value) {
			$authflag []= $this->numToAuthority($value['authflag']);
		}
		krsort($results);
		krsort($authflag);
        $this->render("item/index.tpl",array("data"=>$results,"message"=>$msg,"authflag"=>$authflag,"functionid"=>$functionid));
	}

	public function actionAddSubitem()
	{
		$desc = "";
		$msg = "";
		$data = "";
		$bzirule = "";
        $functionid = $_POST['functionid'];
		$auth=Yii::app()->authManager;
		$db = Yii::app()->db_eel;
		if(!empty($_POST['description']))
		{
			$desc = $_POST['description'];
		}
		if(!empty($_POST['data']))
		{
			$data = $_POST['data'];
		}
		if(!empty($_POST['bzirule']))
		{
			$bzirule = $_POST['bzirule'];
		}
		if(!empty($_POST['name'])&&!empty($_POST['ename']))
		{
			$sql = "select item from developer_function where id=".$functionid;
			$item = $db->createCommand($sql)->queryAll();
			$item = $item[0]['item']."/".$_POST['ename'];
			$sql = "select * from developer_functionItem where item='".$item."'";
			$results = $db->createCommand($sql)->queryAll();
			if(empty($results))
			{
				
				$auth->createOperation($item,$desc,$bzirule,$data);
				$sql = "insert into developer_functionItem (businessId,subitem,description,authflag,authority,unix,item) values(?,?,?,?,?,?,?)";
				$db->createCommand($sql)->execute(array($functionid,$_POST['name'],$desc,$_POST['authflag'],$_POST['ename'],date('Y-m-d h:m:s'),$item));
				$msg['msg']= "添加成功!";	
				$msg['class']= "alert alert-success";	
			}
			else
			{
				$msg ['msg']= "添加失败，功能点名称不能重复";
				$msg['class']= "alert alert-danger";
			}
			
		}
		else
		{
			$msg ['msg']= "功能名称或者功能的英文名不能为空";
			$msg['class']= "alert alert-danger";
		}
		$sql = "select * from developer_AuthItem,developer_functionItem where developer_functionItem.item=developer_AuthItem.name";
		$results = $db->createCommand($sql)->queryAll();
		foreach ($results as $key => $value) {
			$authflag []= $this->numToAuthority($value['authflag']);
		}
		krsort($results);
		krsort($authflag);
        $this->render("item/index.tpl",array("data"=>$results,"message"=>$msg,"authflag"=>$authflag,"functionid"=>$functionid));
	}

	public function actionItemDelete()
	{
		$db = Yii::app()->db_eel;
		
		try {
			$sql = "delete from developer_AuthItem where developer_AuthItem.name in (select item from developer_functionItem where id=".$_POST['id'].")";
			$db->createCommand($sql)->execute();
			$sql = "delete from developer_functionItem where id=".$_POST['id'];
			$db->createCommand($sql)->execute();
		} catch (Exception $e) {
			echo json_encode("删除失败");
		}
		echo json_encode("删除成功");

	}
}