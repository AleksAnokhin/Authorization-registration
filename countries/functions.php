<?php
session_start();
function getConnect() {
	$db = mysqli_connect('127.0.0.1','root','','world');
	mysqli_set_charset($db,'utf-8');
	return $db;
}
function show_page($page,$data=[]) {
	extract($data);
	$page.= '.html';
	if(file_exists('templates/'.$page)) {
		include_once('templates/'.$page);
	}
}
function transform($text, $db) {
	return mysqli_real_escape_string($db,$text);
}
function getHash($size=32) {
	$str = 'abcdefghijklmnopqrstuvwxyz1234567890';
	$hash = '';
	for($i = 0; $i < $size; $i++) {
		$hash.= $str[rand(0,35)];
	}
	return $hash;
}
function  checkUser($db) {
	if(isset($_SESSION['token'])) {
		$session = $_COOKIE['PHPSESSID'];
		$token = $_SESSION['token'];
		$query = mysqli_query($db,"select * from connect inner join users on connect.user_id=users.id where session = '$session';");
		$user = mysqli_fetch_assoc($query);
		if(mysqli_num_rows($query)!==0) {
			if($user['token'] !== $token) {
				mysqli_query($db, "delete from connect where session='$session';");
				unset($_SESSION['token']);
				$user = false;
			}
		} else {
			unset($_SESSION['token']);
			$user = false;
		}
	} else {
		$user = false;
	}
	return $user;
}
?>