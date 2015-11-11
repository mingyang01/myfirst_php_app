<?php
/**
 * 统计各平台访问的log
 * @author linglingqi@meilishuo.com
 * /usr/local/bin/php /home/work/websites/developer/protected/yiic.php Queun Visitelog 1
 */
class VisitelogCommand extends Mq {

	public function process($datas) {

		foreach($datas as $data) {
			if (!$data['uid'] || !$data['ip'] || !$data['url']) {
				return false;
			}

			//根据uid获取用户信息
			$name = NewCommonManager::getUsernameByuid($data['uid']);
			//对URL进行处理;根据子权限点查看父权限点，查到副权限点然后找到对应的功能；如果不存在，则存入权限点和提供一个默认的功能名称
			//调用频率比较高，所有的读库操作需要加缓存
			$url = $data['url'];
			$fun = new FunctionManager();
			$function = $fun->getFunnameByUrl($url);

			$redis = new Redisdb();
			$key = 'auth_check';
			$flag = $redis->get($key, array($function[0]['item'], $data['uid']));
			if ($flag === false) {
				$auth = new AuthManager();
				$flag = $auth->checkAccess($function[0]['item'], $data['uid'] );
				if ($flag) {
					$flag = 1;
				} else {
					$flag = 0;
				}
				$redis->set($key, array($function[0]['item'], $data['uid']), $flag);
			}

			//获取业务号
			$bus = new BusinessManager();
			$url_arr = parse_url($data['url']);
			$host = $url_arr['host'];
			$path = "/".trim($url_arr['path'], '/');
			if (substr($path, 0, 6) == '/jinbi') {
				$host = $url_arr['host'] .'/jinbi';
			}
			$businesses = $bus->getBusinessByHost($host);
			$business = $businesses ? $businesses['business'] : '空';
			$fid = isset($function[0]['id']) ? $function[0]['id'] : 0;
			//数据入库
			$audit = new AuditManager();
			$audit->VisterLog($name, $url, $business, $data['ip'], $fid, $flag);
		}
	}
}
