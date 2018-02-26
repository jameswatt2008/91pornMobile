<?php
//error_reporting(0);
#引入模块
require 'lib/phpQuery.php';
require 'lib/QueryList.php';
require 'core/readHtml.php';
require 'core/db.php';

use QL\QueryList;

echo $_REQUEST["proxy"] ? 'tcp://'.$_REQUEST["proxy"] : '';

function getList($url){

	#获取URL
	/*$url = $_REQUEST["url"];

	$video=$db->select("videos","Video",["URL" => $url]);

	if($data){
		return $video[0]["Video"];
	}*/

	$html = readHtml($url,$_REQUEST["proxy"]);
	
	$rules = array(
    //采集id为one这个元素里面的纯文本内容
    'video' => array('source','src')
	);
	$data = QueryList::Query($html,$rules)->data;
	//print_r($data);

	$link = $data[0]["video"];

	$db->insert("videos",[
		"URL" => $url,
		"Video" => $link
	]);


	return $link;
}

//$video = getList();


#获取URL
$url = $_REQUEST["url"];

$dbResult=$db->select("videos","Video",["URL" => $url]);

if($dbResult){
	$video = $dbResult[0]["Video"];
	$catch=true;
}else{
	$video = getList($url);
}


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
        	<?php if($catch){ ?>
        	<div class="ui-tooltips ui-tooltips-guide">
                <div class="ui-tooltips-cnt ui-tooltips-cnt-link ui-border-b">
                    <i class="ui-icon-talk"></i><?php echo $catch; ?>
                </div>
            </div>
            <?php } ?>
        	<video width="100%"  controls="controls">
        		<source src="<?php echo $list[0]["video"]; ?>" type="video/mp4">
        	</video>
		</section>
    </body>
</html>



















