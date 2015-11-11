<?php
class WhitelistController extends Controller
{
	public function actionIndex($project)
	{

		$this->render("whitelist/index.tpl",array("project"=>$project));
	}

	public function actionGetWhiteList($project)
	{
		$db = Yii::app()->sdb_eel;
		$sql = "select * from developer_whitelist where project = '$project' ";
		$results = $db->createCommand($sql)->queryAll();
		echo json_encode($results);
	}

	public function actionAddList() {

		$db = Yii::app()->db_eel;
		$action = $comment = "";
		$creator = Yii::app()->user->username;

		if(empty($_POST['project'])) {
			Json::fail('项目不能为空');
		}

		if(empty($_POST['action'])) {
			Json::fail('aciton不能为空');
		}

		if(!empty($_POST['comment'])) {
			$comment = $_POST['comment'];
		}

		try {
			$sql = "insert into developer_whitelist (project, controller, action, creator, comment) values(?,?,?,?,?)";
			$db->createCommand($sql)->execute(array($_POST['project'], $_POST['controller'], $_POST['action'], $creator, $comment));
			Json::succ('操作成功');
		} catch (Exception $e) {
			Json::fail("添加失败");
		}

	}

	public function actionUpdateList()
	{
		$db = Yii::app()->db_eel;
		$action = $comment = "";
		$creator = Yii::app()->user->username;
		if(!empty($_POST['action']))
		{
			$action = $_POST['action'];
		}
		if(!empty($_POST['comment']))
		{
			$comment = $_POST['comment'];
		}

		try {
			$sql = "update developer_whitelist set controller = ?,action = ?,creator = ?,comment = ? where id = ? ";
			$db->createCommand($sql)->execute(array($_POST['controller'],$action,$creator,$comment,$_POST['id']));
			Json::succ('操作成功');
		} catch (Exception $e) {
			Json::fail("添加失败");
		}
	}

	public function actionDeleteList() {

		$id = $_POST['id'];
		$db = Yii::app()->db_eel;
		try {
			$sql = "delete from developer_whitelist where id= '$id' ";
			$db->createCommand($sql)->execute();
			Json::succ('操作成功');
		} catch (Exception $e) {
			Json::fail("添加失败");
		}


	}

}