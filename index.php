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

$chinaMobCidrs = [
   "36.128.0.0/10",
   "39.128.0.0/10",
   "43.239.172.0/22",
   "43.251.244.0/22",
   "45.121.68.0/22",
   "45.121.72.0/22",
   "45.121.172.0/22",
   "45.121.176.0/22",
   "45.122.96.0/21",
   "45.123.152.0/22",
   "45.124.36.0/22",
   "45.125.24.0/22",
   "45.253.72.0/22",
   "61.14.240.0/22",
   "61.14.244.0/22",
   "103.20.112.0/22",
   "103.21.176.0/22",
   "103.35.104.0/22",
   "103.61.156.0/22",
   "103.61.160.0/22",
   "103.62.24.0/22",
   "103.62.204.0/22",
   "103.62.208.0/22",
   "103.192.0.0/22",
   "103.192.144.0/22",
   "103.193.140.0/22",
   "103.203.164.0/22",
   "103.205.108.0/22",
   "103.205.116.0/22",
   "103.222.196.0/22",
   "111.0.0.0/10",
   "112.0.0.0/10",
   "117.128.0.0/10",
   "120.192.0.0/10",
   "175.176.188.0/22",
   "183.192.0.0/10",
   "211.103.0.0/17",
   "211.136.0.0/14",
   "211.140.0.0/15",
   "211.142.0.0/17",
   "211.142.128.0/17",
   "211.143.0.0/16",
   "218.200.0.0/14",
   "218.204.0.0/15",
   "218.206.0.0/15",
   "221.130.0.0/15",
   "221.176.0.0/13",
   "223.64.0.0/11",
   "223.96.0.0/12",
   "223.112.0.0/14",
   "223.116.0.0/15",
   "223.120.128.0/17",
   "223.121.128.0/17",
   "223.122.128.0/17",
   "223.123.128.0/17",
   "223.124.0.0/14"
];

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
		if (matchCIDRs(getIp(), $GLOBALS['chinaMobCidrs'])){
        		$url = getImgCDNFree($path);
		}else{
        		$url = getImgCDN($path);
		}
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

function matchCIDR($addr, $cidr) {
     list($ip, $mask) = explode('/', $cidr);
     return (ip2long($addr) >> (32 - $mask) == ip2long($ip) >> (32 - $mask));
}


function matchCIDRs($addr, $cidrs){
        foreach($cidrs as $cidr){
                if (matchCIDR($addr, $cidr)){
                        return true;
                }
        }
        return false;
}
