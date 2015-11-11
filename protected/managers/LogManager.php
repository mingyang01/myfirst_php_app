<?php
class LogManager extends Manager {
	public function printLog($operateUser,$who,$do,$what,$time)
	{
		$html = $operateUser."----".$who."---".$do."---".$what."---".$time."\t\n";
        file_put_contents("/home/work/websites/developer/protected/runtime/distribution-auth.log",$html,FILE_APPEND);
	}
	
	
	public static function writeLog($file="ll.log", $content='') {
		$file ="/home/work/websites/developer/protected/runtime/". $file;
		$html = $content. "\t".date('Y-m-d H:i:s')."\r\n";
		file_put_contents($file, $html, FILE_APPEND);
	}
	
}