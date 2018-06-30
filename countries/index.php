<?php
require_once('functions.php');
$db = getConnect();
$user = checkUser($db);

if($user) {
	show_page('index',['name'=>$user['name'],'auth'=>true]);
} else {
	show_page('index',['auth'=>false]);
}


?>