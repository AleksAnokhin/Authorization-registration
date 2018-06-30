<?php
include_once('functions.php');
if(!empty($_GET['id'])) {
	$db = getConnect();
	$id = transform($_GET['id'],$db);
	$pat = "select id,name,population from city where id= $id";
	$query = mysqli_query($db,$pat);
	$city = mysqli_fetch_assoc($query);
	if(empty($_POST)) {
		show_page('city_edit', ['city'=>$city]);
	} else {
		$name = transform($_POST['city'],$db);
		if($name !== '' && $name !== $city['name']) {
			$pat = "update city set name='$name' where id = $id";
			mysqli_query($db,$pat);
			header('Location:main.php');
		} else {
			echo("<script>alert('There is such city in the list');</script>");
			show_page('city_edit', ['city'=>$city]);
		}
	}
} else {
	header('Location:main.php');
}
mysqli_close($db);


?>