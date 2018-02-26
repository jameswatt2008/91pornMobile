<?php
//error_reporting(0);
#引入模块
require 'lib/phpQuery.php';
require 'lib/QueryList.php';
require 'core/readHtml.php';

use QL\QueryList;

function getList($domain="http://www.91porn.com",$page = 1){

	$url = $domain."/video.php?category=rf&page=".$page;

	$html = readHtml($url);

	//echo $html;
	
	$html = preg_replace('/<span class="title">(.*)/', '', $html);	

	$rules = array(
    //采集id为one这个元素里面的纯文本内容
    'pic' => array('.imagechannelhd>a>img,.imagechannel>a>img','src'),
    'title' => array('.imagechannelhd>a>img,.imagechannel>a>img','title'),
    'link' => array('.imagechannelhd>a,.imagechannel>a','href'),
	);
	$data = QueryList::Query($html,$rules)->data;
	//print_r($data);
	return $data;
}


#获取URL
$domain="http://www.91porn.com";
if($_REQUEST["domain"]){
	$domain = urldecode($_REQUEST["domain"]);
}
$page=1;
if($_REQUEST["page"]){
	$page = $_REQUEST["page"];
}
$list = getList($domain,$page);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="format-detection" content="telephone=no">
        <title>视频列表-91视频预览</title>
        <!--<script type="text/javascript" src="http://tajs.qq.com/stats?sId=37342703" charset="UTF-8"></script>-->
        <link rel="stylesheet" href="frozenui/css/frozen.css">
        <link rel="stylesheet" href="frozenui/css/demo.css">
        <script src="frozenui/lib/zepto.min.js"></script>
        <!--<script src="frozenui/js/frozen.js"></script>-->
    </head>
    <body ontouchstart>
    	<header class="ui-header ui-header-positive ui-border-b">
            <h1>视频列表</h1>
        </header>

        <section class="ui-container">
		<section id="panel">
    <div class="demo-item">
        <p class="demo-desc">第<b><?php echo $page?></b>页</p>
        <div class="demo-block">
            <section class="ui-panel">
                <ul class="ui-grid-trisect">
                	<?php
                	foreach ($list as $key => $value) {  ?>              		
	                    <li data-href="91v.php?url=<?php echo $value["link"]?>&proxy=<?php echo urldecode($_REQUEST["proxy"]) ?>">
	                        <div class="ui-border">
	                            <div class="ui-grid-trisect-img">
	                                <span style="background-image:url('<?php echo $value["pic"]?>')"></span>
	                            </div>
	                            <div style="height:8%;padding: 2%">
	                                <h4 class="ui-nowrap-multi"><?php echo $value["title"]?></h4>                                
	                            </div>
	                        </div>
	                    </li>
                	<?php }	?>
                    
                </ul>
            </section>
        </div>
    </div>
		</section>
                
		<?php if($page>1){ ?>
			<div><a href="91.php?page=<?php echo $page - 1 ?>" class="ui-btn-lg">上一页</a><br></div>
		<?php } ?>		
		<a href="91.php?page=<?php echo $page + 1 ?>" class="ui-btn-lg ui-btn-primary">下一页</a>
            </div>

		</section>
		<script src="https://cdn.bootcss.com/jquery/2.1.2/jquery.min.js"></script>
		<script>

        $("[data-href]").click(function(){
        	location.href = ($(this).data('href'));
        });
        </script>
    </body>
</html>



















