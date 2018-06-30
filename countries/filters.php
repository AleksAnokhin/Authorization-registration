<?php
require_once('functions.php');
$db = getConnect();
$user = checkUser($db);
$and = '';
$order = '';

if(isset($_GET['sort']) and isset($_GET['column'])) {
	if($_GET['sort'] !== 'null' and $_GET['column'] !=='null') {
		$sort = $_GET['sort'];
		$column = $_GET['column'];
		$order.= " ORDER BY $column $sort ";
	}
}

if(isset($_GET['continent']) and $_GET['continent'] !== 'all') {
	$continent = $_GET['continent'];
	$and.= " AND Continent='$continent'";

}
$step = 10;
$list = (isset($_GET['list']) and (int)$_GET['list'] > 0) ? (int)$_GET['list'] : 1;
$start = ($list * $step) - $step;

$allCountries = mysqli_query($db, "SELECT COUNT(*) AS allcountries FROM country WHERE 1 $and");
$allCountries = mysqli_fetch_assoc($allCountries);

$sql = "SELECT Code,Name,Continent,countrylanguage.Language,countrylanguage.Percentage
FROM country INNER JOIN countrylanguage on country.Code=countrylanguage.CountryCode WHERE 1$and $order LIMIT $start,$step";
$query = mysqli_query($db,$sql);
$countries = mysqli_fetch_all($query,MYSQLI_ASSOC);

$continents = mysqli_query($db,"SELECT Continent FROM country");
$continents = mysqli_fetch_all($continents,MYSQLI_ASSOC);

$arr = array();

foreach($continents as $key) {
	foreach($key as $subarr=>$value) {
		if(!in_array($value,$arr)) {
			$arr[] = $value;
		}
	}
}
$continents = $arr;

show_page('filters',['countries'=>$countries,
					 'continents'=>$continents,
					 'allcountries'=> ceil($allCountries['allcountries'] / $step),
					 'list'=>$list
					 ]);
mysqli_close($db);
?>