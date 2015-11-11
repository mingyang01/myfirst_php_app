<?php

require ( "sphinxapi.php" );

/**
 * 备注
 * $cl->SetFilter('字段名', array(1)); // 过滤字段用 数组可以多个
 * $cl->SetFilterRange('字段名', $StartTime, time(), false);过滤时间段
 * $cl->SetFieldWeights(array('字段1' => 10, '字段2' => 5));设置字段权重
 * $cl->SetFilterRange('字段名',$min,$max);整数值空间范围  $cl->SetFilterFloatRange('字段名', $min, $max, $exclude=false) 浮点数值范围 sql_attr_uint sql_attr_float等类型的属性字段，也可以设置过滤范围，类似SQL的WHERE >= AND <= 
 * $cl->SetSortMode ( SPH_SORT_EXTENDED, '字段1 DESC,字段2 DESC' );排序用
 * $cl->SetLimits ( 开始位置, 条数 );//显示数量:开始 量 最大量 右偏移量
 * 如果需要搜索指定全文字段的内容，可以使用扩展匹配模式：$cl->SetMatchMode(SPH_MATCH_EXTENDED); $res=cl->Query( '@title (测试)' , "*"); $res=cl->Query( '@title (测试) @content ('网络')' , "*"); $cl->Query ( "@(brand_n,class_n)('联想电视')&@(pname)('!42')", "product" ); 或和非查询
  $cl= new sphinx();
  $info=array('audit_status'=>4,'shop_id'=>162169, 'campaign_price'=>array('max'=>200,'min'=>100,'strtype'=>'float'));
  $data=$cl->getkw('',"campaign_goods_info",$info,'campaign_price DESC',array(),1,20);
  print_r($data);
 */
class Sphinx extends CController{

    private $db_host;
    private $db_port;
    private static $defaultCl = array();

    public function __construct() {
        $this->db_host = '172.16.2.144'; //最好写成配置文件
        $this->db_port = '9312'; //最好写成配置文件
    }

    /*
      kw 搜索参数
      index 索引名称
      info 过滤数组   直接过滤  以及范围过滤
      array("字段名"=>array()或者值,
      "字段名"=array(
      "strtype"=>"mint",//tag类型  例如一个字段值为 1，2，3，4
      "val"=>array()或者数值,
      'exclude'=>0或者1或者不填 0和空标示 取值在val的范围内 1表示取值不在val范围内
      ),
      "字段名"=>array(
      'min'=>'最小值',
      'max'=>'最大值',
      'strtype'=>'float',
      'exclude'=>0或者1或者不填 0和空标示 取值在val的范围内 1表示取值不在val范围内
      )
      )
      orderstr 排序  weight DESC,id DESC
      weightarr 权重  array('字段1' => 10, '字段2' => 5)
      page 当前页数
      row 分页条数
     */

    public function getkw($kw, $index, $info = array(), $orderstr = '', $weightarr = array(), $page = 0, $row = 20) {
        if (!$cl = $this->getCl())
            return false;
        $data = array();
        $cl->SetMatchMode(SPH_MATCH_EXTENDED);
        if (!empty($info)) {
            foreach ($info as $k => $v) {
                if (is_array($v)) {
                    if (isset($v['strtype'])) {
                        if (isset($v['val']) && !is_array($v['val']))
                            $v['val'] = array($v['val']);
                        $v['exclude'] = empty($v['exclude']) ? false : true;
                        switch ($v['strtype']) {
                            case "float":
                                $cl->SetFilterFloatRange($k, $v["min"], $v["max"], $v['exclude']); // 浮点数值范围
                                break;
                            case "int":
                                $cl->SetFilterRange($k, $v["min"], $v["max"], $v['exclude']); //浮点数值范围
                                break;
                            case "mint":
                                $cl->SetFilter($k, $v['val'], $v['exclude']); //数值范围
                                break;
                        }
                    } else {
                        $cl->SetFilter($k, $v); //过滤字段多个值
                    }
                } else {
                    $cl->SetFilter($k, array($v)); //过滤单个值
                }
            }
        }

        //字段权重
        if (!empty($weightarr)) {
            $cl->SetIndexWeights($weightarr);
        }
        //排序
        if (!empty($orderstr)) {
            $cl->SetSortMode(SPH_SORT_EXTENDED, $orderstr);
        } else {
            $cl->SetSortMode(SPH_SORT_EXTENDED, '@id desc');
        }
        //$cl->SetLimits(0,$limit);
        $limitstart = ($page - 1) * $row;
        $cl->SetLimits($limitstart, (int) $row, $row * $page);
        $data = $cl->Query($kw, $index);
        return $data;
    }

    private function getCl() {
        if (empty(self::$defaultCl)) {
            self::$defaultCl = new SphinxClient ();
            self::$defaultCl->SetServer($this->db_host, $this->db_port);
            self::$defaultCl->SetArrayResult(true);
        }
        return self::$defaultCl;
    }

    /*
     * @index 为索引文件名称
     * @$keyarr 为字段数组  $keyarr=array('字段名')
     * @valuearr 为字段值   $valuearr=array('行数'=>array('字段值'),'行数'=>array('字段值'))
     * 失败返回 false 或者-1
     */

    public function updata($index, $keyarr, $valuearr) {
        if (!$cl = $this->getCl())
            return false;
        return $cl->UpdateAttributes($index, $keyarr, $valuearr);
    }

}

?>
        	        	