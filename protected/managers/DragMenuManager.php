<?php
/**
 * action管理
 * @author mingyang
 * @version 2015-09-01
 */
class DragMenuManager extends Manager {

    public function getRankList($list){
        $listId = array();
        $this->getListId($list,$listId);
        return $listId;
    }

    function getListId($list,&$listId){
        foreach ($list as $key => $value) {
            if(!isset($value['children'])){
                $listId [] = $value['id'];
                continue;
            }else{
                $list = $value['children'];
                $this->getListId($list,$listId);
            }
        }
    }

    public function menuSort($list){
        $rankId = $this->getRankList($list);
        $db = yii::app()->db_eel;
        $rank = count($rankId);
        foreach ($rankId as $key => $value) {
            $sql = "update developer_menu set rank={$rank} where id = {$value} ";
            $db->createCommand($sql)->execute();
            $rank--;
        }
        echo json_encode("保存排序成功！");
    }
}