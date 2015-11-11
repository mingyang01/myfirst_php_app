<?php
class PublishManager extends Manager {
    /**
     * [getBusiness 获取用户有权限的项目列表]
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function getProjectWithUserFilter($name=null) {

    	$user = Yii::app()->user;
    	$auth = new AuthManager();
    	$has_project = $auth->getUserAuthBusiness($user->id);

    	if (!isset($user)) {
    		$name = $user->username;
    	}
    	$business = $this->getProject();
    	if ($auth->isSupper($user->id)) {
    		return $business;
    	}
        $project = array();
        foreach ($business as $key => $value) {
            if ($auth->checkAccess('super', $user->id) || in_array($name, explode(',', $value['developer']))) {
                $project[$value['business']] = $value;
                continue;
            }else {

            	if (isset($has_project[$value['business']])){
            		$project[$value['business']] = $value;
            	}
            }
        }

        return $project;
    }

    public function getProject() {
        $db = Yii::app()->sdb_eel;
        $sql = "select * from developer_business";
        $results = $db->createCommand($sql)->queryAll();
        return $results;
    }

    public function hasAuth($project, $user=null) {
        $projects = $this->getProjectWithUserFilter($user);
        return in_array($project, $this->common->array_column($projects, 'business'));
    }

    /**
     * 获取用户所在部门的业务
     */
    public function getUserBusiness() {

    	$user = Yii::app()->user;
    	$uid = $user->id;
    	$depart = NewCommonManager::getUserDepart($uid);

    	$bus = new BusinessManager();
    	$businesses = $bus->getAllBusinessId();

    	$auth = new AuthManager();
    	if ($auth->isSupper($user->id)) {
    		foreach($businesses as $bval) {
    			$b_info[$bval] = $bus->getInfoByBusiness($bval);
    		}

    		return $b_info;
    	}

    	$item = new ItemManager();
    	$itemname = $item->getRolesProject($depart);

    	//获取是开发者的项目
		$username = $user->username;
		$d_business = $bus->getUserBusiness($username);

    	return array_merge($itemname, $d_business);
    }
}