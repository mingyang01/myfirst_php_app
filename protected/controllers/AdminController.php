<?php

class AdminController extends Controller
{

    function getConnect($sql)
    {
    	$path = Yii::getPathOfAlias('application.runtime');
		$dsn = "sqlite:$path/statistic.db";
		$db = new PDO($dsn);
		$db->beginTransaction();
		$sth = $db->prepare($sql);
		$sth->execute();
		$menu = array();
		$results = $sth->fetchAll() ;
		return $results;
    }

    function getMenu()
    {
    	$db = Yii::app()->sdb_focus;
		//$sql="select first,second,sign from t_focus_menu where sign = "."'".$menuName."'";
		$sql = "select first,second,sign from t_focus_menu";
		$items = $db->createCommand($sql)->queryAll();
		for ($i=0; $i <count($items) ; $i++) { 
			$menubox [strtolower($items[$i]['sign'])] = strtolower($items[$i]['second']);
		}
		return $menubox;
		
    	
    }

    function getActionName()
    {
    	$db = Yii::app()->sdb_focus;
		//$sql="select first,second,sign from t_focus_menu where sign = "."'".$menuName."'";
		$sql = "select first,second,sign from t_focus_menu";
		$items = $db->createCommand($sql)->queryAll();
		for ($i=0; $i <count($items) ; $i++) { 
			$menubox [strtolower($items[$i]['second'])] = strtolower($items[$i]['sign']);
		}
		return $menubox;
		
    	
    }
	// 全站功能统计
	public function actionFunctionDetail()
	{

		$sql = "select *  from actions where unix between ".strtotime(date("Y-m-d 00:00:00"))." and ".strtotime(date("Y-m-d 24:00:00"));
		$flag=false;
		$data=array();
		$redata=array();
		$actionbox = $this->getActionName();
		if(!empty($_POST['datefrom'])&&!empty($_POST['dateto']))
		{
			if($_POST['datefrom']>$_POST['dateto'])
			{
				$sql = $sql;
				$flag=true;
			}else if($_POST['datefrom']==$_POST['dateto'])
			{
				$sql = "select *  from actions where unix between ".strtotime($_POST['datefrom'])." and ".(strtotime($_POST['dateto'])+86400);
			}
			else
			{
				$sql = "select *  from actions where unix between ".strtotime($_POST['datefrom'])." and ".strtotime($_POST['dateto']);	
			}
		  
		}
		else if(empty($_POST['datefrom'])&&!empty($_POST['dateto']))
		{
          $sql = "select *  from actions where unix < ".strtotime($_POST['dateto']);
		}
		else if(!empty($_POST['datefrom'])&&empty($_POST['dateto']))
		{
          $sql = "select *  from actions where unix > ".strtotime($_POST['datefrom']);
		}
		else
		{
			$sql = $sql;
		}
		if(!empty($_POST['username'])&&(!empty($_POST['datefrom'])||!empty($_POST['dateto']))&&!$flag)
		{
			$sql = $sql." and name = "."'".$_POST['username']."'";
		}
		else if(!empty($_POST['username'])&&empty($_POST['datefrom'])&&empty($_POST['dateto']))
		{
			$sql = $sql." where name = "."'".$_POST['username']."'";
		}
		else if(!empty($_POST['username'])&&$flag)
		{
			$sql = $sql." where name = "."'".$_POST['username']."'";
		}
		if(!empty($_POST['action'])&&!empty($_POST['username']))
		{
			$sql = $sql." and action = "."'".$actionbox[$_POST['action']]."'";
		}
		else if(!empty($_POST['action'])&&!empty($_POST['datefrom']))
		{
			$sql = $sql." and action = "."'".$actionbox[$_POST['action']]."'";
		}
		else if(!empty($_POST['action'])&&empty($_POST['username']))
		{
			$sql = $sql." where action = "."'".$actionbox[$_POST['action']]."'";
		}

		$results = $this->getConnect($sql);
		$menubox = $this->getMenu();
        for ($i=0; $i <count($results) ; $i++) { 

        	if(!empty($results[$i][1])&&!empty($results[$i][2])&&isset($menubox[$results[$i][2]]))
        	{
        		$data[$i]["username"] = $results[$i][1];
        		$data[$i]["action"] = $menubox[$results[$i][2]];
        		//$data[$i]["time"] = $results[$i][3];
        		$data[$i]["time"] = date('Y-m-d H:i:s',(int)$results[$i][3]);
        	}
        	
        }
        
        	$this->render('managers/functiondetail.tpl',array("data"=>$data));
        


	}
	//搜索功能

	public function actionStatistic()
	{
		$sql = "select action, count(user) as pv, count(distinct user) as uv from (select * from actions where unix between ".strtotime(date("Y-m-d 00:00:00"))." and ".strtotime(date("Y-m-d 24:00:00")).") group by action";
		if(!empty($_POST['datefrom'])&&!empty($_POST['dateto']))
		{
			if($_POST['datefrom']>$_POST['dateto'])
			{
				$sql = $sql;
			}
			else if($_POST['datefrom']==$_POST['dateto'])
			{
				$sql = "select action, count(user) as pv, count(distinct user) as uv from (select * from actions where unix between ".strtotime($_POST['datefrom'])." and ".(strtotime($_POST['dateto'])+86400).") group by action";	
			}
			else
			{
				$sql = "select action, count(user) as pv, count(distinct user) as uv from (select * from actions where unix between ".strtotime($_POST['datefrom'])." and ".strtotime($_POST['dateto']).") group by action";	
			}
		  
		}
		else if(!empty($_POST['datefrom'])&&!empty($_POST['dateto']))
		{
		$sql = "select action, count(user) as pv, count(distinct user) as uv from (select * from actions where unix < ".strtotime($_POST['dateto']).") group by action";
          
		}
		else if(!empty($_POST['datefrom'])&&empty($_POST['dateto']))
		{
          $sql = "select action, count(user) as pv, count(distinct user) as uv from (select * from actions where unix > ".strtotime($_POST['datefrom']).") group by action";
		}
		else
		{
			$sql = $sql;
		}


		$results = $this->getConnect($sql);
		$menubox = $this->getMenu();
		$menu = array();
		$uvresults = array();
		$pvresults = array();
		for ($i=0; $i <count($results) ; $i++) {
		    
		       if(isset($menubox[$results[$i][0]]))
		       {
		       	$menu [] = $menubox[$results[$i][0]];
	        	$uvresults[] = $results[$i][1];
	        	$pvresults[] =$results[$i][2];
		       }
			    
		     
			
        }
       
        	$this->render('managers/charshow.tpl',array("menu"=>$menu,"pv"=>$uvresults,"uv"=>$pvresults));
     
	}

	public function actionUserPv ()
	{
		
		$sql = "select name,user,action, count(user) as pv from (select * from actions where unix between ".strtotime(date("Y-m-d 00:00:00"))." and ".strtotime(date("Y-m-d 24:00:00")).") group by user";	
		if(!empty($_POST['datefrom'])&&!empty($_POST['dateto']))
		{
			if($_POST['datefrom']>$_POST['dateto'])
			{
				$sql = $sql;
			}
			else if($_POST['datefrom']==$_POST['dateto'])
			{
				$sql = "select name,user,action, count(user) as pv from (select * from actions where unix between ".strtotime($_POST['datefrom'])." and ".(strtotime($_POST['dateto'])+86400).") group by user";	
			}
			else
			{
				$sql = "select name,user,action, count(user) as pv from (select * from actions where unix between ".strtotime($_POST['datefrom'])." and ".strtotime($_POST['dateto']).") group by user";	
			}
		  
		}
		else if(!empty($_POST['datefrom'])&&!empty($_POST['dateto']))
		{
		$sql = "select name,user,action,  count(user) as pv from (select * from actions where unix < ".strtotime($_POST['dateto']).") group by user";
          
		}
		else if(!empty($_POST['datefrom'])&&empty($_POST['dateto']))
		{
          $sql = "select name,user,action, count(user) as pv from (select * from actions where unix > ".strtotime($_POST['datefrom']).") group by user";
		}
		else
		{
			$sql = $sql;
		}
		
		$results = $this->getConnect($sql);
		$menubox = $this->getMenu();
		$data = array();
		foreach ($results as $key => $value) {
			
			if(!empty($results[$key]['name'])||!empty($results[$key]['NAME']))
			{
				if(empty($results[$key]['NAME']))
				{
					$data[$results[$key]['name']] = $results[$key]['pv'];
				}
				else
				{
					$data[$results[$key]['NAME']] = $results[$key]['pv'];
				}
				
			}
						
		}
        arsort($data,SORT_NUMERIC);

        foreach ($data as $key => $value) {
        	$results['name'] []= $key;
        	$results['pv'] []= $value;
        }

		$this->render('managers/userpv.tpl',array("data"=>$results['name'],"pv"=>$results['pv']));

    }

    public function actionMenuUpdata()
    {
    	
    	$db = Yii::app()->db_focus;
    	$sql = "update t_focus_menu set business = ?, first = ?, second = ?, third = ?,url=?,creator=? , sign = ? where id =? ";
        $db->createCommand($sql)->execute(array($_POST["business"], $_POST["first"], $_POST["second"], " ",$_POST["url"],yii::app()->user->username,$_POST["sign"],$_POST['id']));
        $results="更新成功";
		echo json_encode($results);


    }

    public function actionMenuAdd()
    {
    	$results="";
    	if(!empty($_POST['business'])&&!empty($_POST['first'])&&!empty($_POST['second'])
			&&!empty($_POST['url'])&&!empty($_POST['sign']))
		{
			
			$db = Yii::app()->db_focus;
			$sql = "insert into t_focus_menu (business,first,second,third,creator,url,sign) values(?,?,?,?,?,?,?)";
			$db->createCommand($sql)->execute(array($_POST['business'],$_POST['first'],$_POST['second']," ",yii::app()->user->username,$_POST['url'],$_POST['sign']));
		    $results="添加成功";
		}
		
		echo json_encode($results);
	}

	public function actionDele()
	{
		$db = Yii::app()->db_focus;
		$sql = "delete from t_focus_menu where id = ".$_POST['id'];
        $db->createCommand($sql)->execute();
        $results="删除成功";
		echo json_encode($results);
	}

    public function actionMenuManage()
	{
		 $db = Yii::app()->sdb_focus;
         $sql="select * from t_focus_menu";
         $items = $db->createCommand($sql)->queryAll();
         foreach ($items as $key  ) {
         	$data []=$key;
         }
         krsort($data);
		$this->render("managers/menumanage.tpl",array("data"=>$data));
	}
}

