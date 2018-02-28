<?php
error_reporting(0);
#引入模块
require 'lib/phpQuery.php';
require 'lib/QueryList.php';
require 'core/readHtml.php';
require "lib/Medoo.php";

use Medoo\Medoo;
use QL\QueryList;

$db = new medoo([
    'database_type' => 'sqlite',
    'database_file' => 'db/91.db'
]);

//echo $_REQUEST["proxy"] ? 'tcp://'.$_REQUEST["proxy"] : '';

function getList($url){

	#获取URL
	/*$url = $_REQUEST["url"];

	$video=$db->select("videos","Video",["URL" => $url]);

	if($data){
		return $video[0]["Video"];
	}*/

	$html = readHtml($url,urldecode($_REQUEST["proxyip"]));
	
	$rules = array(
    //采集id为one这个元素里面的纯文本内容
    'video' => array('source','src')
	);
	$data = QueryList::Query($html,$rules)->data;
	//print_r($data);

	$link = $data[0]["video"];

    //print_r($db->id());
    if($link){
        global $db,$viewkey;

    	$db->insert("videos",[
    		"url" => $viewkey,
    		"link" => $link
    	]);
    }


	return $link;
}

//$video = getList();


#获取URL
$url = urldecode($_REQUEST["url"]);

$urlarr=parse_url($url);
parse_str($urlarr['query'],$parr);
$viewkey = $parr["viewkey"];

$dbResult=$db->select("videos","link",["url" => $viewkey]);

    //print_r($dbResult);
//$video = '';

if($dbResult){
    //global $video;
	$video = $dbResult[0];
	$catch=true;
    //echo $video;
}else{
    //global $video;
	$video = getList($url);
    //echo "src";
}
//print_r($db->select("videos","*"));

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
                    <i class="ui-icon-talk"></i>通过缓存加载文件
                </div>
            </div>
            <?php } ?>
            <?php if(!$video){ ?>
            <div class="ui-tooltips ui-tooltips-warn">
                <div class="ui-tooltips-cnt ui-border-b">
                    <i></i>获取失败，请重试或更换代理重试<a class="ui-icon-close"></a>
                </div>
            </div>
            <?php } ?>
        	<video width="100%"  controls="controls">
        		<source src="<?php echo $video; ?>" type="video/mp4">
        	</video>
		</section>
    </body>
</html>



















