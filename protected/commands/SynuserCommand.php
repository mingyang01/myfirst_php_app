
<?php 
class SynuserCommand extends Command {
    public function main($args)
    {
       $this->Synuser();
    }

    public function Synuser()
    {
        $result = $this->synuser->synuser();
        $this->synuser->SynDepartMessage();
    }


}