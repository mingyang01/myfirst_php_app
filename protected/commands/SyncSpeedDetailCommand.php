<?php 

/**
 * 同步部门信息，离职权限解除，申请权限因部门改变的不畅通
 * @author mingyang
 */

class SyncSpeedDetailCommand extends Command {

    public function main($args)
    {
       $this->Synuser();
    }

    public function Synuser()
    {
        $this->SyncDepart->getCompareMsg();
        //同步部门id
        $this->SyncDepart->updateDepartId();
        //更新部门名称
        $this->SyncDepart->getDepartDetail();
        //离职人员 权限解除
        $this->SyncDepart->SyncSpeed();
        //同步申请权限流程
        $this->SyncDepart->SyncAuthApply();
    }


}