<?php

include 'functions.php';

$type = $_GET['type'];
$limit = $_GET['limit'];
$sort = $_GET['sort'];

if(!isset($type) || !($type == 'moe' || $type == 'wallpaper' || $type == 'head' || $type == 'koino' || $type == 'imgbed')) $type = 'moe';
if(!isset($limit)) $limit = 30;
if(!isset($sort)) $sort = 'random';



$data = getImgsInfo($type);

if($sort == 'random') shuffle($data[0]);
if($sort == 'reverse') $data[0] = array_reverse($data[0]);
if($limit >= 0) $data[0] = array_slice($data[0], 0, $limit);

$o = array();

foreach($data[0] as $val){
    $o[] = array(
        "name" => $val,
        "url" => 'https://api.yimian.xyz/img/?path='.$type.'/'.$val
    );
}


echo json_encode($o);
