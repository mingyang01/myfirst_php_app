<?php
/**
* 可拖拽的菜单
* @author mingyang
* @version 2015-09-01
**/
class DragMenuController extends Controller{
    public function ActionIndex(){
        $request = Yii::app()->request;
        $business = $request->getparam('business','');
        $project = $this->publish->getUserBusiness();
        if(!$business) {$business = 'focus';}
        $menu = $this->menu->getMenuTest($business,'945');
        array_shift($menu);
        $param = array('menu'=>$menu,'project'=>$project,'business'=>$business);
        $this->render('dragmenu/index.tpl',$param);
    }

    public function ActionSaveRank(){
        $request = yii::app()->request;
        $list = $request->getparam('list',array());
        $list = json_decode($list,true);
        $list = (array)$list;
        if(array_key_exists('length', $list)){
            echo json_encode('排序并没有变化啊');
        }
        $this->dragMenu->menuSort($list);
    }
}