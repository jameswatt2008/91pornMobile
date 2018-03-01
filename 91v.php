<?php
//error_reporting(0);
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

function getProxy(){

    $url = "https://free-proxy-list.net/";
    $html = readHtml($url);

    $rules = array(
    //采集id为one这个元素里面的纯文本内容
    'ip' => array('tbody>tr:lt(20)','html',"-td:gt(0) td"),
    'port' => array('tbody>tr:lt(20)','html',"-td:gt(1) -td:eq(0) td"),
    'country' => array('tbody>tr:lt(20)','html',"-td:gt(2) -td:lt(1) td"),
    );
    $data = QueryList::Query($html,$rules)->data;
    //print_r($data);
    return $data;
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
            <i class="ui-icon-return" onclick="history.back()"></i><a href="91.php" style="position: absolute;left: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;列表页</a><h1>视频详情</h1><button onclick="window.location.href='index.php';" class="ui-btn">回首页</button>
        </header>

        <section class="ui-container">
        	<?php if($video){ ?>
        	<div class="ui-tooltips ui-tooltips-guide">
                <div class="ui-tooltips-cnt ui-tooltips-cnt-link ui-border-b">
                    <i class="ui-icon-talk"></i><?php if($catch) echo "通过缓存"?>加载成功
                </div>
            </div>
            <?php } ?>

            <?php if(!$video){ ?>
            <div class="ui-tooltips ui-tooltips-warn">
                <div class="ui-tooltips-cnt ui-border-b">
                    <i></i>获取失败，请刷新或更换代理重试
                </div>
            </div>
            <?php $proxyList=getProxy(); ?>

            <form class="ui-form-item ui-form-item-r ui-border-b" method="get" action="91v.php">
                
                <input type="hidden" name="title" value="<?php echo($_REQUEST["title"])?>">
                <input type="hidden" name="url" value="<?php echo($_REQUEST["url"])?>">
                <div class="ui-select" style="margin:0;margin-right: 120px">
                    <select name="proxyip">
                        <option value="">无</option>
                        <?php foreach ($proxyList as $key => $value) {
                            echo '<option '. (urldecode($_REQUEST["proxyip"]) == ($value["ip"].':'.$value["port"]) ? "selected" : "") .' value="'.$value["ip"].':'.$value["port"].'">'.$value["ip"].'['.$value["country"].']</option>';
                        } ?>
                    </select>
                </div>
                <button type="submit" class="ui-border-l">重 试</button>
            </form>
            <?php } ?>

            <p class="demo-desc"><?php echo urldecode($_REQUEST["title"]) ?></p>
        	<video width="100%"  controls="controls">
        		<source src="<?php echo $video; ?>" type="video/mp4">
        	</video>
		</section>
    </body>
</html>



















