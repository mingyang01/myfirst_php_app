
<?php 
  include "thread.php";
 ?>
<?php

class CacheCommand extends Command {

public function main($args)
{
    $this->runlist($args['start'],$args['offset']);
}

public function help()
{
    echo "please use php yiic cache --command=xxx","\n";
}


public function runlist($start,$offset)
{
    $allStaff = $this->common->getAllStaff();
    for($i=$start;$i<$offset;$i++)
    {
        if(isset($allStaff[$i]))
        {
            MenuManager::getTest('work',$allStaff[$i]['id'],'true');
            echo "完成第".$i."\n\t";
        }
    }
}

}