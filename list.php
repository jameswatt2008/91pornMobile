<?php

require 'lib/phpQuery.php';
require 'lib/QueryList.php';

use QL\QueryList;

$html = <<<STR
<div id="one">
    <div class="two">
        <a href="http://querylist.cc">QueryList官网</a>
        <img src="http://querylist.com/1.jpg" alt="这是图片">
        <img src="http://querylist.com/2.jpg" alt="这是图片2">
    </div>
    <span>其它的<b>一些</b>文本</span>
</div>        
STR;
$rules = array(
    //采集id为one这个元素里面的纯文本内容
    'text' => array('#one','text'),
    //采集class为two下面的超链接的链接
    'link' => array('.two>a','href'),
    //采集class为two下面的第二张图片的链接
    'img' => array('.two>img:eq(1)','src'),
    //采集span标签中的HTML内容
    'other' => array('span','html')
);
$data = QueryList::Query($html,$rules)->data;
print_r($data);