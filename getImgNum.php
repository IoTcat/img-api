<?php

include 'functions.php';

header('Access-Control-Allow-Origin:*');

$moe = getImgsInfo('moe');
$koino = getImgsInfo('koino');
$wallpaper = getImgsInfo('wallpaper');
$imgbed = getImgsInfo('imgbed');


$o = array();

$o["moe"] = count($moe[0]);
$o["koino"] = count($koino[0]);
$o["wallpaper"] = count($wallpaper[0]);
$o["imgbed"] = count($imgbed[0]);

$o["total"] = $o["moe"] + $o["koino"] + $o["wallpaper"] + $o["imgbed"];

echo json_encode($o);
