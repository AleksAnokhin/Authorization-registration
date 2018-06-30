<?php

require_once( 'functions.php' );
$db = getConnect();

if ( isset( $_GET[ 'login' ] ) ) {
	$login = transform( $_GET[ 'login' ], $db );
	$sql = "SELECT id FROM users WHERE login='$login'";
	$query = mysqli_query( $db, $sql );
	if ( mysqli_num_rows( $query ) == 1 ) {
		$code = getHash( 6 );
		$sql = "UPDATE users SET code='$code' where login='$login'";
		$query = mysqli_query( $db, $sql );
		file_put_contents( $login . '.txt', 'http://localhost/SQL_mine/1/passrefresh.php?code= ' . $code );
		show_page('validator');
		//$_GET['code'] = $code;

	} else {
		show_page( 'passrefresh', [ 'errorText' => 'User with such login does not exist' ] );
	}
} else if ( !isset( $_GET[ 'code' ] ) && !isset( $_GET[ 'login' ] ) ) {
	show_page( 'passrefresh' );
} else if ( isset( $_GET[ 'code' ] ) && !isset( $_POST[ 'password' ] ) ) {
	$code = transform( $_GET[ 'code' ], $db );
	$sql = "SELECT id FROM users WHERE code='$code'";
	$query = mysqli_query( $db, $sql );
	if ( mysqli_num_rows( $query ) == 1 ) {
		$id = mysqli_fetch_assoc( $query );
		show_page( 'newpassinsert', [ 'id' => $id[ 'id' ] ] );
	} else {
		show_page( 'validator', [ 'errorText' => 'Incorrect code!' ] );
	}
} else if ( isset( $_POST[ 'password' ] ) ) {
	if ( $_POST[ 'password' ] == $_POST[ 'password2' ] ) {
		$id = ( int )$_POST[ 'id' ];
		$password = password_hash( $_POST[ 'password' ], PASSWORD_DEFAULT );
		mysqli_query( $db, "
			UPDATE users SET password = '$password'
			WHERE id = $id
		" );
		header( 'Location: authorize.php' );
	} else {
		show_page( 'newpassinsert', [
			'errorText' => 'Inserted passwords are not equal'
		] );
	}
}