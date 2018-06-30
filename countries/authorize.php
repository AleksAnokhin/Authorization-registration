<?php
require_once('functions.php');
$db = getConnect();
if ( !empty( $_SESSION ) && isset( $_SESSION[ 'token' ] ) ) {
	header( 'Location:index.php' );
}
if(empty($_POST)) {
	show_page('authorize');
} else {
	$name = transform( $_POST[ 'name' ], $db );
	$login = transform( $_POST[ 'login' ], $db );
	$password = transform( $_POST[ 'password' ], $db );
	$query = mysqli_query($db,"select * from users where login='$login';");
	if(mysqli_num_rows($query) == 1) {
		$user= mysqli_fetch_assoc($query);
		if(password_verify($password,$user['password'])){
			$id = $user['id'];
			$session = $_COOKIE['PHPSESSID'];
			$token = getHash();
			$sql = "DELETE FROM connect WHERE user_id='$id'";
			$delete = mysqli_query($db, $sql);
			if($delete) {
				$sql = "INSERT INTO connect(user_id,session,token)
				VALUES('$id','$session','$token')";
				$insert = mysqli_query($db,$sql);
				$_SESSION['token'] = $token;
				header('location:index.php');
			}
			
		} else {
			show_page( 'authorize', [ 'errorText' => 'Incorrect password!' ] );
		}
	} else {
		show_page( 'authorize', [ 'errorText' => 'Incorrect user!' ] );
	}
}

?>