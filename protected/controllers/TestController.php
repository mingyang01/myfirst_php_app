<?php

class TestController extends Controller
{

    public function actionExport()
    {
        $handle = file_get_contents("/home/work/websites/auth/protected/controllers/data.txt", "rw");
        $rows = explode("\n", $handle);
        $results = array();
        $userList =  $this->getRoleList();
        $userhas = array();
        $auth = yii::app()->authManager;
        $userchange = $this->common->userMap();
        foreach ($rows as $key => $value) {
            $data = explode(",", $value);
            $results [$data[0]] []=$data[1];


        }
        $i = 0;
        foreach ($results as $key => $value) {
            $curl = yii::app()->curl;
            $url = "localhost:8082/auth/taskdist";
            $tasklist = $value;
            $vars = array(
                "departid"=>46,
                "rolename"=>$key,
                "tasklist"=>$tasklist
                );
            $output = $curl->post($url, $vars);
            $redata = $output['body'];
            print_r($redata);
        }
    }

    public function actionRoleAdd()
    {
        $handle = file_get_contents("/home/work/websites/auth/protected/controllers/role.txt", "rw");
        $rows = explode("\n", $handle);
        // $results = array();
        // $userList =  $this->getRoleList();
        // $userhas = array();
        // $auth = yii::app()->authManager;
        // $userchange = $this->common->userMap();
        // foreach ($rows as $key => $value) {
        //     $data = explode(",", $value);
        //     $results [$data[0]] []=$data[1];

        // }
        // $i = 0;
        foreach ($rows as $key => $value) {
            $curl = yii::app()->curl;
            $url = "localhost:8082/auth/roleAdd";
            $vars = array(
                "departid"=>46,
                "name"=>$value,
                "disc"=>"",
                "rule"=>"",
                "data"=>""
                );
            $output = $curl->post($url, $vars);
            $redata = $output['body'];
            print_r($redata);
        }
    }

    function getRoleList()
    {
        $db = yii::app()->sdb_eel;
        $sql = "select * from t_eel_admin_relation_user";
        $results = $db->createCommand($sql)->queryAll();
        $data = array();
        foreach ($results as $key => $value) {
                $data[$value['role_id']] [] = $value['user_id'];

        }

        return $data;
    }

    public function actionAddFunction()
    {

        $handle = file_get_contents("/home/work/websites/auth/protected/controllers/data.txt", "rw");
        $rows = explode("\n", $handle);
        $results = array();
        $userList =  $this->getRoleList();
        $userhas = array();
        $auth = yii::app()->authManager;
        $userchange = $this->common->userMap();
        foreach ($rows as $key => $value) {
            $data = explode(",", $value);
            $results [$data[0]] []=$data[1];


        }
        $i = 0;
        foreach ($results as $key => $value) {
            foreach ($value as $key => $val) {
               $curl = yii::app()->curl;
                $url = "localhost:8082/function/add";
                $vars = array(
                    "business"=>'test',
                    "funname"=>$val,
                    "description"=>'/test/test',
                    "url"=>'/test/test',
                    "sign"=>'test/test'
                    );
                $output = $curl->post($url, $vars);
                $redata = $output['body'];
                print_r($redata);
            }

        }
    }

    public function actionTest()
    {
        //$apiUrl = "http://developer.meiliworks.com";
        $apiUrl = "localhost:8082";
        $business = 'work';

        //1、导入角色
        //$this->setWorkData_role($apiUrl);

        //2、导入功能、菜单、角色
        $re = $this->admin->getMenuData();
        print_r($re);exit;
        //导入功能及菜单
        //$this->setWorkData_menu($re['menu'], $apiUrl, $business);

        //3、导入功能及角色关系数据
        //$this->setWorkData_menu_role($re['role'], $apiUrl, $business);

        //4、导入用户与角色关系数据
        //$this->setWorkData_user_role($apiUrl);
    }



    static function getInstance() {
        return parent::getInstance( get_class() );
    }

    ///home/service/php/bin/php /home/work/htwebsite/mlsht/crontab.php /admin/cron_nullRealname
    public function do_cron_nullRealname()
    {
        adminModel::getInstance()->nullRealName();
    }

    function do_cron_setWorkData(){
        $apiUrl = "http://developer.meiliworks.com";
        //$apiUrl = "localhost:8082";
        $business = 'work';

        //1、导入角色
        //$this->setWorkData_role($apiUrl);

        //2、导入功能、菜单、角色
        $re = adminModel::getInstance()->getMenuData();
        print_r($re);exit;
        //导入功能及菜单
        //$this->setWorkData_menu($re['menu'], $apiUrl, $business);

        //3、导入功能及角色关系数据
        //$this->setWorkData_menu_role($re['role'], $apiUrl, $business);

        //4、导入用户与角色关系数据
        //$this->setWorkData_user_role($apiUrl);
    }

    function setWorkData_role($apiUrl){
        $curl = yii::app()->curl;
        $reRole = adminModel::getInstance()->getRoleData();
        $apiRole = $apiUrl."/auth/roleAdd";
        foreach ($reRole as $r){
            $postRole = array(
                    'name'  => $r['role_name'],
                    'item'  => '规则与风控部－规则与风控'
                );
            $reRole = $curl->post($apiRole, $postRole);
        }
        echo '角色导入完成！';
    }

    function setWorkData_menu($re, $apiUrl, $business){
        $curl = yii::app()->curl;
        foreach ($re as $r){
            //功能接口
            $api1 = $apiUrl."/function/add";
            $post1 = array(
                    'business'  => $r['business'],
                    'funname'   => $r['funname'],
                    'url'   => $r['url'],
                    'sign'  => $r['sign']
                );
            $re1 = $curl->post($api1, $post1);
            //print_r($re1['body']);exit;
            $reArr = json_decode($re1['body'], true);
            //print_r($reArr);exit;
            $funId = $reArr['functionid'];
            //print_r($funId);exit();

            //添加action
            if (! empty($funId)){
                if (substr($r['url'], 0, 1) == '/'){
                    $action = substr($r['url'], 1);
                }else{
                    $action = $r['url'];
                }
                $api1_1 = $apiUrl."/function/addaction";
                $post1_1 = array(
                        'business'  => $r['business'],
                        'id'    => $funId,
                        'controller'    => 'work_space',
                        'action'    => $action
                    );
                $re1_1 = $curl->post($api1_1, $post1_1);
                //print_r($re1_1['body']);exit;
                $reArr = json_decode($re1_1['body'], true);
            }

            if (! empty($funId)){
                //菜单接口
                $api2 = $apiUrl."/menu/add";
                $post2 = array(
                        'business'  => $business,
                        'first' => $r['first'],
                        'second'    => $r['second'],
                        'third' => $r['third'],
                        'function'  => $funId
                    );
                $re2 = $curl->post($api2, $post2);

               // print_r($re2['body']);exit;
            }else{
                echo '功能接口返回空！';
                print_r($r);
            }

        }
        echo '功能导入完成！';
    }

    function setWorkData_menu_role($re, $apiUrl, $business){
        $curl = yii::app()->curl;
        $api = $apiUrl."/auth/taskdist";
        foreach ($re as $roleName=>$taskList){
            $post = array(
                    'departid'  => 46,
                    'rolename'  => $roleName,
                    'tasklist'  => $taskList
                );
            $reApi = $curl->post($api, http_build_query($post));
        }
        echo '菜单及功能关系数据导入完成！';
    }

    function setWorkData_user_role($apiUrl){
        $curl = yii::app()->curl;
        $api = $apiUrl."/auth/roleUserAdd";

        $re = adminModel::getInstance()->getUserRoleData();
        foreach ($re as $roleName=>$userIds){
            $post = array(
                    'departid'  => 46,
                    'selected'  => $userIds,
                    'role'      => $roleName
                );
            $reApi = $curl->post($api, http_build_query($post));
            //print_r($reApi['body']);
        }
        echo '用户及角色关系数据导入完成！';
    }


    public  function actionInsertItem($business = '')
    {
        $db = yii::app()->db_eel;
        $auth = yii::app()->authManager;
        $sql = "select item from developer_function";
        $results = $db->createCommand($sql)->queryAll();
        foreach ($results as $key => $value) {
            if(!$auth->getAuthItem($value['item']))
            {
                $task = $auth->createTask($value['item']);
                echo "success"."-----".$value['item']."<br/>";
            }
            else
            {
                 echo "fail"."-----".$value['item']."<br/>";
            }
        }
    }

    public function actionAuthGroup()
    {
        // $curl = yii::app()->curl;
        // $url = "developer.meiliworks.com/api/CheckGroup";
        // //$url = "localhost:8082/api/CheckGroup";
        // $points = array('119');

        // $vars = array(
        //     "business"=>'data平台',
        //     "points"=>$points,
        //     "uid"=>314455
        //     );
        // $output = $curl->post($url, $vars);
        // $redata = $output['body'];
        // print_r($redata);
        $curl = yii::app()->curl;
        $url = "developer.meiliworks.com/api/CheckGroup";
        $points = array('报表20');

        $vars = array(
            "business"=>'data平台',
            "points"=>$points,
            "uid"=>35897879
        );
        $output = $curl->post($url, $vars);
        $redata = $output['body'];
        print_r($redata);
    }
    public function actionAutoAuth()
    {
        $curl = yii::app()->curl;
        $url = "developer.meiliworks.com/api/AutoAuth";
        $id = array(111,146,144,148,150,152,154,156,158,160,162);
        $vars = array(
            "id"=>$id
            );
        $output = $curl->post($url, $vars);
        $redata = $output['body'];
        print_r($redata);
    }

    public function actionAutoItem()
    {
        $curl = yii::app()->curl;
        $url = "developer.meiliworks.com/api/AutoItem";
        //$url = "localhost:8082/api/AutoItem";
        $vars = array(
            "business"=>'test',
            "funname"=>"测试11",
            "description"=>'测试11',
            "url"=>'/data/data',
            "sign"=>'data/data'
            );
        $output = $curl->post($url, $vars);
        $redata = $output['body'];
        print_r($redata);
    }

    public function actionMenuTest()
    {

       var_dump($this->menu->getMenuTest("work","193","false"));

    }



    public function actionClear($uid)
    {
        $redis = new Redis();
        $redis->connect('172.16.8.159');
        $redis->select(9);

        $rekey = "Menu".$uid;
        $redis->delete($rekey);
    }

    public function actionAllStaff()
    {
        $redis = new Redis();
        $redis->connect('172.16.8.159');
        $redis->select(9);

        $rekey = "Menu".$uid;
    }

    public function actionAutoSubmit()
    {
        $curl = yii::app()->curl;
        $url = "developer.meiliworks.com/api/AutoVerify";
        //$url = "localhost:8082/api/AutoVerify";
        $vars = array(
            "business"=>'test',
            "funname"=>"测试",
            );
        $output = $curl->post($url, $vars);
        $redata = $output['body'];
        print_r($redata);
    }

  public function actionIndex() {
        echo json_encode($this->common->userMap());
    }

    public function actionRequest() {
        echo json_encode($this->auth->getRoleUsers("技术部-数据智能/roleadmin"));
    }

    public function actionCheck() {
        $db = yii::app()->sdb_eel;
        $auth = Yii::app()->authManager;

        $sql = "select a.action, b.item from developer_action a, developer_function b where a.functionid = b.id";
        $results = $db->createCommand($sql)->queryAll();
        foreach ($results as $key => $value) {
            echo "check " . $value['item'] . "=>" .$value['action'] .":   " . $auth->hasItemChild($value['item'], $value['action']);
            echo "</br>";
            if(!$auth->hasItemChild($value['item'], $value['action']) ) {
                $auth->addItemChild($value['item'], $value['action']);
                echo "<h1 style='color:red;'>Duang" .$value['item'] . $value['action']."</h1>";
            }
        }
        // $auth->addItemChild($item,$action);
    }

    public function actionImport() {
        $id = $_GET['id'];
        $role = $_GET['role'];

        if (isset($_GET['role']) && isset($_GET['id'])) {
            # code...
            $users = array();

            $db = yii::app()->sdb_eel;
            $auth = Yii::app()->authManager;
            $sql = "select user_id id from t_eel_admin_relation_user where role_id = $role";
            $results = $db->createCommand($sql)->queryAll();
            $map = $this->common->userMap();

            $function = $this->function->getFunction($id);
            $n = $function[0]['item'];
            $count = 0;
            foreach ($results as $key => &$value) {
                $s = $map[$value['id']];

                if ($this->auth->checkAccess($n, $s['id'])) {
                    $s['status'] = '已有';
                } else {
                    $s['status'] = '没有';
                    if (isset($_GET['import'])) {
                        $auth->assign($n, $s['id']);
                        $count++;
                    }
                }

                $users[] = $s;
            }



            if (empty($function)) {
                echo "function id not exist!";
                Yii::app()->end();
            }

            echo $this->render('test/import.tpl', array('id'=>$id, 'role'=>$role, 'users'=>$users, 'function'=>$function, 'count'=>$count));
            Yii::app()->end();

        }

        echo $this->render('test/import.tpl', array('count'=>$count));
    }

    public function actionTestSync(){
        // $res = $this->newCommon->getSecondeDepartInfo();
        // echo "<pre>";
        // var_dump($res);
        // echo "</pre>";
        // exit;
        $res = $this->syncDepart->getDepartSecond();
        // $this->syncDepart->getDepartDetail();
        // $this->syncDepart->getCompareMsg();

        echo "<pre>";
        var_dump($res);
        echo "</pre>";
    }

}


