<?php

require_once('functions.php');
$db = getConnect();
$user = checkUser($db);

if(!$user) {
	header('Location:index.php');
}
if(!empty($_POST)) {
	$sql = "UPDATE users SET ";
	if($_POST['password'] !== '') {
	$password = password_hash($_POST['password'],PASSWORD_DEFAULT);
	$sql .= "password = '$password', "; 
	}
	$sql .= "name = '" . transform($_POST['name'],$db) . "', 
	login = '" . transform($_POST['login'],$db) . "' WHERE id='" .(int)$_POST['id']."'";
	mysqli_query($db,$sql);
	header('Location:index.php');
	
} else {
	show_page('cabinet',['user'=>$user]);
}