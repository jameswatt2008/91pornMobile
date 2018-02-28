<?php

include("../Medoo.php");

use Medoo\Medoo;

$db = new medoo([
    'database_type' => 'sqlite',
    'database_file' => dirname(__FILE__).'/../db/91.db'
]);

/*	$id = $db->insert("videos",[
		"url"=>'aa',
		"link"=>'bb',
	]);
print_r($id);
*/
//$db->query("INSERT INTO videos ('url', 'link') VALUES ('aa', 'bb')");

print_r($db->select("videos","*"));
