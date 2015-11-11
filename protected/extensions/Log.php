<?php
/**
 * 记录日志处理.
 * 记录位置为: (/home/work/logs/developer/)
 *
 * @author  linglingqi@meilishuo.com
 */
abstract class Log {

	protected $log_path = '/home/work/logs/developer/';

    /**
     * 记录应用日志
     * @param string $file
     * @param string $content
     * @param $flags FILE_APPEND 追加| 0 覆盖方式写入
     * @return mixed
     */
    static public function writeApplog($file, $content, $flags=FILE_APPEND) {
        $file = self::getAppLogFilePath($file);
        $dir  = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
            chmod($dir, 0777);
        }
        $content = '[' . date('Y-m-d H:i:s') . ']' . $content . "\r\n";
        return file_put_contents($file, $content, $flags);
    }

    /**
     * 获取日志文件的完整路径
     * @param $file 文件名
     * @return string
     */
    static public function getAppLogFilePath($file) {
        return self::getAppLogDir() . $file . '.log';
    }

    /**
     * 获取日志根目录
     *
     * @return string
     */
    static public function getAppLogDir() {
        return $this->log_path . date('Y/md/');
    }

    /**
     * 此方法每天只记录一条日志, 适用于不需要记录日志详细信息的情况,如果是第一次写入日志，返回true
     *
     * @param string $file    日志文件
     * @param string $content 日志内容
     * @return bool
     */
    static public function writeDailyLog($file, $content) {
        $log_file_path = self::getAppLogDir() . $file . '.daily.log';

        $content = '[' . date('Y-m-d H:i:s') . '] ' . (is_string($content) ? $content : json_encode($content)) . "\r\n";

        $first_time = false;
        if (!file_exists($log_file_path)) {
            $first_time = true;

            $dir = dirname($log_file_path);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }
        }
        file_put_contents($log_file_path, $content);

        return $first_time;
    }

    /**
     * 特殊的日志文件，每天日志文件最多只记录$max_line_count行。
     * 单位时间($duration)内连续发生($max_line_count行)日志记录时，返回-1
     * @param string $log_path
     * @param string $msg
     * @param int    $max_count 单位时间内连续发生的次数
     * @param int    $duration 单位为s
     * @return int|mixed  返回-1表示错误日志在$duration秒内发生了$max_count次
     */
    public static function logFailed($log_file = '', $msg='', $max_count=5, $duration=60) {
        $log_file_path = self::getAppLogDir() . $log_file . '.sum.log';
        $file_contents  = @file_get_contents($log_file_path);
        $time          = time();
        if ($file_contents !== false) {
            $file_contents = explode("\n", $file_contents);
        } else {
            $file_contents = array();
        }

        //删除空行
        $log_contents = array();
        foreach($file_contents as $line) {
            if(!empty($line)) {
                $log_contents[] = $line;
            }
        }

        $show_trigger_fail = false;

        $error_msg = sprintf('[Waring] ###time:%s###  msg:[%s]', $time, Helper_Json::encode($msg));
        Comm_Util::isCli() && printf(Comm_Util::warningText($error_msg)."\n");

        if (count($log_contents) == 0) {
            $log_contents[] = sprintf('total_fail_cnt:1 ###from:[%s] ###to:[%s]', date('Ymd H:i:s',$time), date('Ymd H:i:s',$time));
            $log_contents[] = $error_msg;
        } else {
            $first_line = array_shift($log_contents);
            $file_line_count = count($log_contents);

            //更新total_fail_cnt
            $total_fail_cnt = $file_line_count; //默认值
            $start_time = sprintf("from:[%s]", date('Ymd H:i:s',$time));
            $parts = explode('###', $first_line);
            if (count($parts) == 3) { //读取上一次的值
                $sub_parts = explode(':', $parts[0]);
                $total_fail_cnt = intval($sub_parts[1]);
                $start_time = trim($parts[1]);
            }

            $total_fail_cnt += 1;
            $first_line = sprintf('total_fail_cnt:%d ###%s ###to:[%s]', $total_fail_cnt, $start_time, date('Ymd H:i:s',$time));

            array_unshift($log_contents, $error_msg);
            array_unshift($log_contents, $first_line);

            if ($file_line_count > $max_count) {
                $last_fail_time = self::parseTime($log_contents[$file_line_count - 1]);
                //                printf("%d %d %d\n", $time, $last_fail_time, $time - $last_fail_time);
                if ($last_fail_time && $time - $last_fail_time < $duration) {
                    Comm_Util::isCli() && printf(Comm_Util::errorText("Warning trigger!!!\n"));
                    //发送报警后，清空队列，重新记录错误日志

                    $log_contents = array_splice($log_contents, 0, 2);
                    $show_trigger_fail = true;
                } else {
                    $log_contents = array_splice($log_contents, 0, $max_count);
                }
            }
        }

        $write_ok = file_put_contents($log_file_path, implode("\n", $log_contents));
        if($show_trigger_fail) {
            return -1;
        }
        return $write_ok;
    }

    /**
     * 解析#后的时间戳
     * @param $line '[Error] ###time:1398756535### msg:["This is error msg"]'
     * @return false | int
     */
    protected static function parseTime($line) {
        $parts = explode('###', $line);
        if(count($parts) < 3) {
            return false;
        }

        $parts = explode(':', $parts[1]);
        return count($parts)==2 ? $parts[1] : false;
    }
}