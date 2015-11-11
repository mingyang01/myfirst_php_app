<?php
$configs = array();

$on = "/home/work/conf/mysql/op.mysql.ini";
$off = dirname(__FILE__)."/mysql.ini";

file_exists($on) ? $lines=file($on) : $lines=file($off);

// 这两个库很特殊,同构但数据不同,所以在配置后添加了suffix做区分,
// 为避免影响其他平台,不统一加到mysql.ini
$lines[] = "db=storm_mid_data host=172.16.0.95 port=3305 weight=1 user=storm pass=bluecat master=0 suffix=bj\n";
$lines[] = "db=storm_mid_data host=10.0.0.82 port=3305 weight=1 user=storm pass=bluecat master=0 suffix=gz\n";

// if merge = true , the configuration will merge to before one as a slave
// if direct = true, the configured database will connect without tunnel
function parse(&$configs, $lines, $merge=true, $direct=false){
    foreach($lines as $line) {
        if(trim($line) === '' || $line[0] === '#' || $line[0] === '/') continue;
        $fields = explode(" ", $line);
        $dbname = substr(trim($fields[0]), 3); // db=xxxx
        $hostname = substr(trim($fields[1]), 5); // host=xxxx
        $port = substr(trim($fields[2]), 5); // port=xxxx
        $user = substr(trim($fields[4]), 5); // user=xxxx
        $pass = substr(trim($fields[5]), 5); // port=xxxx
        $ismaster = trim(substr(trim($fields[6]), 7)) === "1"; // port=xxxx
        
        $suffix = '';
        if (isset($fields[7])) {
            $suffix = '_'.trim(substr(trim($fields[7]), 7)); 
        }

        $config = array(
            'connectionString' => "mysql:host={$hostname};port={$port};dbname={$dbname}",
            'emulatePrepare' => true,
            'username' => $user,
            'password' => $pass,
            'charset' => 'utf8'
        );

        if($ismaster) {
            $configs["db_{$dbname}{$suffix}"] = $config;
            $configs["db_{$dbname}{$suffix}"]["class"] = "CDbConnection";
        } else {
            $config['port'] = $port;
            $config['host'] = $hostname;
            $config['db'] = $dbname;
            $config['database'] = "sdb_{$dbname}{$suffix}";
            $config['direct'] = $direct;
            
            $configs["sdb_{$dbname}{$suffix}"]["class"] = "DbConnection";
            
            if ($merge) {
                $configs["sdb_{$dbname}{$suffix}"]["dbs"][] = $config;
            } else {
                $configs["sdb_{$dbname}{$suffix}"]["dbs"] = array($config);
            }
        }
    }
}

parse($configs, $lines);

// load developer localhost direct databases
if(file_exists(dirname(__FILE__)."/local/direct.ini")){
    $lines = file(dirname(__FILE__)."/local/direct.ini");
    parse($configs, $lines, false, true);
}

return $configs;
