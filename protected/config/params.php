<?php

$on = array(  
    'TOKEN' => 'e98cfc1a4f23ae1699919c505ae2ba04',
    'SPEED' => 'http://api.speed.meilishuo.com'
);

$off = array(
    'TOKEN' => 'e98cfc1a4f23ae1699919c505ae2ba04' ,
    'SPEED' => 'http://api.speed.meilishuo.com'
);

return !YII_DEBUG? $on : $off;
