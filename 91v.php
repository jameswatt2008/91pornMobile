<?php
error_reporting(0);
#引入模块
require 'lib/phpQuery.php';
require 'lib/QueryList.php';

use QL\QueryList;

echo $_REQUEST["proxy"] ? 'tcp://'.$_REQUEST["proxy"] : '';

function getList(){

	#获取URL
	$url = $_REQUEST["url"];
	//$url = "http://www.checkip.org/";
	echo "URL is : ". $url ."<br>";

	#读取HTML
	$opts = array(
	  'http'=>array(
	    'method'=>"GET",
	    'proxy'=> $_REQUEST["proxy"] ? 'tcp://'.$_REQUEST["proxy"] : '',
	    'header'=>"Content-Type: text/xml\r\n"."charset=utf-8\r\n"."Accept-language: zh-cn\r\n"."Cookie: foo=bar\r\n",
	  )
	);

	$context = stream_context_create($opts);

	// Open the file using the HTTP headers set above
	$html = file_get_contents($url, false, $context);
	#$file = iconv("utf-8", "utf-8",file_get_contents($url, false, $context));

	//$html=str_replace("�", "", $html);
	#$html = preg_replace('/<span class="title">(.*)/', '', $html);
	//echo $html;

	//$html=str_replace("�", "", $html);
	

	$rules = array(
    //采集id为one这个元素里面的纯文本内容
    'video' => array('source','src')
	);
	$data = QueryList::Query($html,$rules)->data;
	print_r($data);
	return $data;
}

$list = getList();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="format-detection" content="telephone=no">
        <title>视频详情-91视频预览</title>
        <link rel="stylesheet" href="frozenui/css/frozen.css">
        <link rel="stylesheet" href="frozenui/css/demo.css">
    </head>
    <body>
    	<header class="ui-header ui-header-positive ui-border-b">
            <i class="ui-icon-return" onclick="history.back()"></i><h1>视频详情</h1>
        </header>

        <section class="ui-container">
        	<video width="100%"  controls="controls">
        		<source src="<?php echo $list[0]["video"]; ?>" type="video/mp4">
        	</video>
		</section>
    </body>
</html>



















