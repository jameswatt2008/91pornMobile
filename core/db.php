<?php

include(dirname(__FILE__)."/../lib/medoo.php");

use Medoo\Medoo;

$db = new medoo([
    'database_type' => 'sqlite',
    'database_file' => '/www/91.db'// '../db/91.db'
]);

	$id = $db->insert("videos",[
		"url"=>'aa',
		"link"=>'bb',
	]);
print_r($id);

$db->query("INSERT INTO videos ('url', 'link') VALUES ('aa', 'bb')");

print_r($db->select("videos","*"));