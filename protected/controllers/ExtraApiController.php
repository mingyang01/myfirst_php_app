<?php
class ExtraApiController extends Controller {
    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                'actions'=>array("menu"),
                'users'=>array('?'),
            ),

            array('deny',
                'users'=>array('?'),
            ),
        );
    }

    public function actionMenu($uid, $business=null, $domain=true, $cbusiness='global') {
        $result = array("code"=>1, "message"=>"", "data"=>array());
        if (!$uid) {
            $result['code'] = 0;
            $result['message'] = "param uid is required!";
            echo json_encode($result);
            yii::app()->end();
        }
        echo json_encode($this->menu->getMenuTest($business, $uid, $domain, true, $cbusiness));
    }
    

   
}
