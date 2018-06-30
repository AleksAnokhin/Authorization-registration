<?php
include_once('functions.php');
$db = getConnect();
$pat = 'select id,name,population from city order by name';
$query = mysqli_query($db,$pat);
$arrCity = mysqli_fetch_all($query, MYSQLI_ASSOC);
show_page('cities',['arrCity'=>$arrCity]);
mysqli_close($db);
?>