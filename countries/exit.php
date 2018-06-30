<?php
require_once('functions.php');
$db = getConnect();

if(!empty($_SESSION) && isset($_SESSION['token'])) {
	$session = $_COOKIE['PHPSESSID'];
	mysqli_query($db,"delete from connect where session='$session'");
	unset($_SESSION['token']);
}
header('location:index.php');
?>