<?php


$redis = new redis(); 
$redis->connect('redis',6379);

$keys = $redis->keys('api/img/_num/*');


$res = array();
foreach($keys as $key){
    $res[$key] = $redis->get($key);
}

arsort($res);

foreach($res as $a => $b)
echo "$a => $b <br>";
