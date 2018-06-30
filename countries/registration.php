<?php
require_once( 'functions.php' );
$db = getConnect();
if ( !empty( $_SESSION ) && isset( $_SESSION[ 'token' ] ) ) {
	header( 'Location:index.php' );
}
if ( empty( $_POST ) ) {
	show_page( 'registration' );
} else {
	$name = transform( $_POST[ 'name' ], $db );
	$login = transform( $_POST[ 'login' ], $db );
	$password = transform( $_POST[ 'password' ], $db );
	$password2 = transform( $_POST[ 'password2' ], $db );

	if ( $password == $password2 ) {
		$count = mysqli_query( $db, "select id from users where login ='$login'" );
		if ( mysqli_num_rows( $count ) == 0 ) {
			$password = password_hash( $password, PASSWORD_DEFAULT );
			mysqli_query( $db, "insert into users(name,login,password)
			values('$name','$login','$password');" );
			$id = mysqli_insert_id( $db );
			$session = $_COOKIE[ 'PHPSESSID' ];
			$token = getHash();
			mysqli_query( $db, "insert into connect(user_id,session,token)
			values('$id','$session','$token');" );
			$_SESSION[ 'token' ] = $token;
			header("location:index.php");
		} else {
			show_page( 'registration', [ 'errorText' => 'Such login has already been used' ] );
		}
	} else {
		show_page( 'registration', [ 'errorText' => 'Your passwords are not equal' ] );
	}
}

?>