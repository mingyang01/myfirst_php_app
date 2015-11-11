<?php
/**
 * 简化数组操作的方法
 * @package helper
 * @author  linglingqi@meilishuo.com
 */
class HelperManager extends Manager {
    
    /**
     * 将一个二维数组转换为 HashMap，并返回结果
     *
     * 用法1：
     * <code>
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $hashmap = Helper_Array::hashmap($rows, 'id', 'value');
     *
     * print_r($hashmap);
     *   // 输出结果为
     *   // array(
     *   //   1 => '1-1',
     *   //   2 => '2-1',
     *   // )
     * </code>
     *
     * 如果省略 $value_field 参数，则转换结果每一项为包含该项所有数据的数组。
     *
     * 用法2：
     * <code>
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1'),
     *     array('id' => 2, 'value' => '2-1'),
     * );
     * $hashmap = Helper_Array::hashMap($rows, 'id');
     *
     * print_r($hashmap);
     *   // 输出结果为
     *   // array(
     *   //   1 => array('id' => 1, 'value' => '1-1'),
     *   //   2 => array('id' => 2, 'value' => '2-1'),
     *   // )
     * </code>
     *
     * @param array  $arr 数据源
     * @param string $key_field 按照什么键的值进行转换
     * @param string $value_field 对应的键值
     * @param boolean $force_string_key 强制使用字符串KEY
     *
     * @return array 转换后的 HashMap 样式数组
     */
    public function hashmap($arr, $key_field, $value_field = null, $force_string_key = false) {
        $ret = array();
        if ($value_field) {
            foreach ($arr as $row) {
                $key       = $force_string_key ? (string)$row[$key_field] : $row[$key_field];
                $ret[$key] = $row[$value_field];
            }
        } else {
            foreach ($arr as $row) {
                $key       = $force_string_key ? (string)$row[$key_field] : $row[$key_field];
                $ret[$key] = $row;
            }
        }
        return $ret;
    }

}