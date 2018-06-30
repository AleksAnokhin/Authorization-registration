<?php
require_once 'functions.php';
$db = getConnect();

if ( !empty( $_SESSION )AND isset( $_SESSION[ 'token' ] ) ) {
	$session = $_COOKIE[ 'PHPSESSID' ];
	mysqli_query( $db, "
		DELETE FROM connect
		WHERE session = '$session'
	" );
	unset( $_SESSION[ 'token' ] );
}
header( 'Location: index.php' );