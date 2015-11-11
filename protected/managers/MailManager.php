<?php
class MailManager extends Manager {

	const FENGKONG_MAIL = 'quanxian';

    public function sendMail($to,$user,$status) {

        $headers = 'From: <developer@meilishuo.com>' . "\r\n";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $subject = "权限申请处理";
        $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
        switch ($status) {
            case 'apply':
                $to  = $to."@meilishuo.com";
                $message = "您有新的权限申请需要处理\r\n"."权限申请来自 ".$user."\r\n请到http://developer.meiliworks.com/apply/applycheck  中处理\r\n谢谢。\r\n来自美丽说权限管理平台（该邮件为系统自动发送，请勿回复）";
                mail($to,$subject,$message,$headers);
                LogManager::writeLog('mail.log', "leader revice:".$to ."\t". $user);
                break;

            case 'refuse':
                $to  = $to."@meilishuo.com";
                $message = "你的申请被拒绝\r\n请到http://developer.meiliworks.com/apply/myapply  查看原因\r\n谢谢。\r\n来自美丽说权限管理平台（该邮件为系统自动发送，请勿回复）";
                mail($to,$subject,$message,$headers);
                LogManager::writeLog('mail.log', "refuse revice:".$to ."\t". $user);
                break;

            case 'group':
                foreach ($to as $key => $value) {
                    $to  = $value."@meilishuo.com";
                    $message = "您有新的权限申请需要处理\r\n"."权限申请来自 ".$user."\r\n请到http://developer.meiliworks.com/apply/applycheck  中处理\r\n谢谢。\r\n来自美丽说权限管理平台（该邮件为系统自动发送，请勿回复）";
                    mail($to,$subject,$message,$headers);
                    LogManager::writeLog('mail.log', "group revice:".$to ."\t". $user);
                }
                break;
            case 'success':
                $to  = $to."@meilishuo.com";
                $message = "你的 '".$user."' 权限申请成功\r\n请到http://developer.meiliworks.com/apply/index  查看已经获得的权限\r\n谢谢。\r\n来自美丽说权限管理平台（该邮件为系统自动发送，请勿回复）";
                mail($to,$subject,$message,$headers);
                LogManager::writeLog('mail.log', "sucess give privilige:".$to ."\t". $user);
                break;
            case 'manager':
            	if (is_array($to)) {
            		$mail = array();
            		foreach($to as $val) {
            			$mail[] = $val ."@meilishuo.com";
            		}
            		$to = implode(",", $mail);
            	}else {
            		$to = $to ."@meilishuo.com";;
            	}
            	$message ="您通过了权限申请，具体：".$user;
            	mail($to,$subject,$message,$headers);
            	LogManager::writeLog('mail.log', "manager give privige:".$to ."\t". $user);
            	break;
            case 'managerRef':
            		if (is_array($to)) {
            			$mail = array();
            			foreach($to as $val) {
            				$mail[] = $val ."@meilishuo.com";
            			}
            			$to = implode(",", $mail);
            		}else {
            			$to = $to ."@meilishuo.com";;
            		}
            		$message ="您拒绝了权限申请，具体：".$user;
            		mail($to,$subject,$message,$headers);
            		LogManager::writeLog('mail.log', "manager refuse privige:".$to ."\t". $user);
            		break;
        }
    }

    /**
     * 自定义文案发邮件
     * @param  $to 发送给的用户名
     * @param  $subject 主题
     * @param  $content 内容
     */
    public function sendCommMail($to, $subject, $content, $ext=array(), $bolHtml=FALSE) {
    	$headers = 'From: <developer@meilishuo.com>' . "\r\n";
        if($bolHtml)
        {
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        }
    	$subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
    	!is_array($to) && $to = array($to);
    	$to_address = array();
    	foreach ($to as $val) {
    		$to_address[] = $val ."@meilishuo.com";
    	}
    	$to = implode(",", $to_address);
    	if(isset($ext['to'])) {
    		$to = $to .','. $ext['to'];
    	}
    	$ret = mail($to, $subject, $content, $headers);
    	return true;
    }

    public function sendWarnning($content) {

    	$time = time();
    	$redis = new Redisdb();
    	$key = 'warn_mail';
    	$old_time = $redis->get($key);
    	if($time-$old_time>600) {
    		$to = Config::get('mailconf.warn');
    		$subject = "报警邮件";
    		$ret = self::sendCommMail($to, $subject, $content, true);
    		$redis->set($key, array(), $time);
    	}
    	return true;
    }

}
