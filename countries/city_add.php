<?php
include_once( 'functions.php' );
$db = getConnect();
if ( empty( $_POST ) ) {
	show_page( 'city_add', [] );
} else {
	$city = transform( $_POST[ 'newCity' ], $db );
	$population = transform( $_POST[ 'population' ], $db );
	$query = mysqli_query( $db, "select * from city where name='$city'" );

	if ( !mysqli_num_rows( $query ) ) {
		if ( $city !== '' ) {
			$pat = "insert into city(name,population)values('$city','$population');";
			$query = mysqli_query( $db, $pat );
			header( 'Location:main.php' );
		}
	} else {
		echo( "<script> alert('Wrong!');</script>" );
		show_page( 'city_add', [] );
	}
}
mysqli_close( $db );
?>