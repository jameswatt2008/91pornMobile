<?php
error_reporting(0);

function readHtml($url,$proxyip=""){

	//echo "URL is : ". $url ."<br>";

	#读取HTML
	$opts = array(
	  'http'=>array(
	    'method'=>"GET",
	    'proxy'=> $proxyip=="" ? "" : 'tcp://'.$proxyip,
	    'header'=>"Content-Type: text/xml\r\n"."charset=utf-8\r\n"."Accept-language: zh-cn\r\n"."Cookie: foo=bar\r\n",
	  )
	);

	$context = stream_context_create($opts);

	// Open the file using the HTTP headers set above
	$html = file_get_contents($url, false, $context);
	#$file = iconv("utf-8", "utf-8",file_get_contents($url, false, $context));

	return $html;
}