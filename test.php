<?php
<?php
// 引入依赖库
require 'vendor/autoload.php';
// 使用源码安装时引入SDK代码库
require 'obs-autoloader.php';

// 声明命名空间
use Obs\ObsClient;

include '/mnt/config/huaweicloud/config.php';


function handler($event, $context) {
		// 创建ObsClient实例
	$obsClient = new ObsClient([
	      'key' => '',
	      'secret' => '',
	      'endpoint' => '',
	]);

	// 使用访问OBS
	$resp = $obsClient -> listObjects([
	       'Bucket' => 'yimian-image'
	]);

	$s = var_dump($resp['Contents']);

	// 关闭obsClient
	$obsClient -> close();

    return '{"statusCode": 200, "isBase64Encoded": false, "headers": {}, "body": "'.$s.'"}';
}
