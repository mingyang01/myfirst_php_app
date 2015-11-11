#!/bin/bash

PHP=/home/work/runtime/php/bin/php
for((i=0;i<=1000;i=i+100));
do
{
echo “正在进行”.$i.’--’;
start=$i
((offset=$i+100))
$PHP yiic.php cache --start=$start  --offset=$offset
echo “已经完成”.$i.”—-”;
}&
done


