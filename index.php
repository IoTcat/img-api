<?php

include './functions.php';

/* anti ddos */
/*if(!isset($_COOKIE['_token__']) || $_COOKIE['_token__'] != md5(date('Y-m-d-H'))) {
    setcookie("_token__",md5(date('Y-m-d-H')),time()+1*3600);
    header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], true, 301);
}*/


$whiteList = array(
    'www.eee.dog',
    'api.yimian.xyz',
    'home.yimian.xyz',
//    'img.yimian.xyz',
    'acg.watch',
    'iotcat.me',
    'ushio.yimian.xyz',
    'yimian.xyz',
    'guide.yimian.xyz',
    'cp-acc.yimian.xyz',
    'v2ray.yimian.xyz',
    'onedrive.yimian.xyz',
    'login.yimian.xyz',
    'user.yimian.xyz',
    'imgbed.yimian.xyz',
    'share.yimian.xyz',
    'proxy.yimian.xyz',
    'shorturl.yimian.xyz',
    'mksec.yimian.xyz',
    'monitor.yimian.xyz',
    'blog.yimian.xyz',
    'blank.com'
);

$costyPaths = array(
    'moe/img_862_1920x1080_96_null_normal.jpg'
);


header('content-type: image/png');

$path = $_REQUEST['path'];
$type = $_REQUEST['type'];
$id = $_REQUEST['id'];
$size = $_REQUEST['size'];
$display = $_REQUEST['display'];
$R18 = $_REQUEST['R18'];
$range = $_REQUEST['range'];
$format = $_REQUEST['format'];
$compress = $_REQUEST['compress'];
$command = $_REQUEST['command'];


if(!isset($type) || !($type == "moe" || $type == "koino" || $type == "head" || $type == "wallpaper" || $type == "blog" || $type == "imgbed" || $type == "easyver")) $type = "moe";
if(!isset($id)) $id = null;
if(!isset($size)) $size = null;
if(!isset($path)) $path = null; else $type = 'path';
if($display != "true") $display = false; else $display = true;
if($R18 != "true") $R18 = false; else $R18 = true;
if(isset($range) && $range > 0) $range = $range; else $range = 0;
if(!isset($format) || !($format == "jpg" || $format == "png" || $format == "webp" || $format == "bmp")) $format = null;
if(!isset($compress) || !($compress%10 == 0)) $compress = null;
if(!isset($command)) $command = null;



$__from = get_from();
if($__from == '') $__from = 'http://blank.com/';
$__from = parse_url($__from)['host'];

if($type == "moe"){
    $redis = new redis(); 
    $redis->connect('redis',6379);


    $__time = 'api/img/_time/'.$__from;
    $__num = 'api/img/_num/'.$__from;
    $__ip = 'api/img/_ip/'.$__from.'/'.getIp();

    if(!$redis->exists($__time) || !$redis->exists($__num)){
        $redis->set($__time, time());
        $redis->set($__num, -10);
        $redis->set($__ip, -1);
    }

    $_time = $redis->get($__time);
    $_num = $redis->get($__num);
    $_ip = $redis->get($__ip);

    if(time() - $_time > 60*60*24){
        $redis->set($__time, time());
        $redis->set($__num, 0);
        $redis->set($__ip, 0);
        $_num = $redis->get($__num);
        $_ip = $redis->get($__ip);
        $_time = $redis->get($__time);
    } 

    
    $redis->set($__num, $_num+1);
    $redis->set($__ip, $_ip+1);

    if(!$path && ((!in_array($__from, $whiteList) && ($_num > 10000 || $_ip > 1000)) || ($_ip > 3000))) {
        header("Location: https://api.vvhan.com/api/acgimg");
        yimian__log("log_api", array("api" => "img", "timestamp" => date('Y-m-d H:i:s', time()), "ip" => ip2long(getIp()), "_from" => get_from(), "content" => 'https://api.vvhan.com/api/acgimg')); 
        die();
    }


}





if($path){

    if(strpos($path, 'moe') !== false){
        $type = 'moe';
    }elseif(strpos($path, 'wallpaper') !== false){
        $type = 'wallpaper';
    }

    returnImg($path);
}elseif($type){

    $arr = getImgsInfo($type);

    if($id){
        $pos = array_search($id, $arr[1]);
        if($pos) $path = $type. '/' .$arr[0][$pos];
    }

    if(!$size && !$path){
        $path = $type. '/' .$arr[0][array_rand($arr[0])];
    }elseif(!$path){
        $arr_size = explode('x',$size);
        if($arr_size[0] == '*') $arr_size[0] = '0-9999';
        if($arr_size[1] == '*') $arr_size[1] = '0-9999';
        if(strpos($arr_size[0], '-')) $arr_size_length = explode('-',$arr_size[0]);
        else $arr_size_length = $arr_size[0];
        if(strpos($arr_size[1], '-')) $arr_size_high = explode('-',$arr_size[1]);
        else $arr_size_high = $arr_size[1];
        $arr_length = getMatchedKeys($arr_size_length, $arr[2]);
        $arr_high = getMatchedKeys($arr_size_high, $arr[3]);
        $arr_keys = array_intersect($arr_length, $arr_high);
        if(!count($arr_keys)){
            header('content-type: application/json');
            echo json_encode(array("err"=>"Can not find any images matching Size $size in Type $type!!"));
            die();
        }
        do{
  	    $index = array_rand($arr_keys);
	}while($R18 != true && $arr[6][$arr_keys[$index]] != "normal");
        $path = $type. '/' .$arr[0][$arr_keys[$index]];

    }

    returnImg($path);
 
}else{

    die();
}


yimian__log("log_api", array("api" => "img", "timestamp" => date('Y-m-d H:i:s', time()), "ip" => ip2long(getIp()), "_from" => get_from(), "content" => $path)); 




function returnImg($path){
    if($GLOBALS['type'] != 'wallpaper' && $GLOBALS['type'] != 'imgbed' && $GLOBALS['type'] != 'path' /*&& ((!in_array($GLOBALS['__from'], $GLOBALS['whiteList']) && ($GLOBALS['_num'] > 0 || $GLOBALS['_ip'] > 0)) || ($GLOBALS['_ip'] > 0))*/) {
	if(in_array($path, $GLOBALS['costyPaths'])){
		$url = getImgCDNFree($path);
	}else{
        	$url = getImgCDN($path);
	}
        //if(get_from()=='')$url = getImgCDN($path);
        //else $url = getImgCDNProxy($path);
        //$url = getImgOneindex($path);
      //  $url = getImg($path);
    }else{
        if($GLOBALS['type'] == 'wallpaper') {
            //$url = getImg($path);
            $url = getImgCDNwallpaper($path);
        }else{
            $url = getImg($path);
        }
    }
    if($GLOBALS['display']) echo file_get_contents($url); else header("Location: $url");
}


function getMatchedKeys($str, $arr){
    if(!is_array($str)){
        $o = array();
        foreach($arr as $key=>$val){
            if($val >= $str - $GLOBALS['range'] && $val <= $str + $GLOBALS['range']) array_push($o, $key);
        }
        return $o;
    }else{
        $o = array();
        foreach($arr as $key=>$val){
            if($val >= $str[0] - $GLOBALS['range'] && $val <= $str[1] + $GLOBALS['range']) array_push($o, $key);
        }
        return $o;
    }   
}
