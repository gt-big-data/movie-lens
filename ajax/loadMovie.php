<?php
include('../dbco.php');
if(isset($_GET['movie'])) {
	include("../google.php");
	$g= new GoogleImages();
	$movie = mr($_GET['movie']);
	if($movie == 0) { // trouble == random seed
		$list = array();
		$r = mq("SELECT movie FROM bd_movieratings WHERE user='".$_COOKIE['dbUser']."' ORDER BY movie");
		while($ra = mfa($r)) {
			array_push($list, $ra['movie']);
		}
	$m = mq("SELECT * FROM bd_movies WHERE id NOT IN (".implode(", ", $list).") ORDER BY RAND() LIMIT 1"); $mo = mfa($m);
	}
	else { // easy peasy
	$m = mq("SELECT * FROM bd_movies WHERE id='$movie'"); $mo = mfa($m);	
	}
	$ret =  "<div class=\"movieClass\" id=\"m".$mo['id']."\"><img src=\"".$g->get($mo['movieName'])."\" class=\"movieImg\" alt=\"".$mo['movieName']."\" title=\"".$mo['movieName']."\"  />
	<div class=\"starDiv\"><img src=\"images/x.gif\" class=\"x\" title=\"I don't know this movie\" onclick=\"addRating(-1, ".$mo['id'].");\" />";
	for($i = 2; $i <= 6; $i ++) {
    $ret .= "<img src=\"images/star".$i.".png\" class=\"stars star".$i."\" title=\"".($i-1)." Stars\" onclick=\"addRating(".($i-1).", ".$mo['id'].");\" onmouseover=\"choose(".($i-1).", ".$mo['id'].");\" />";
	}
	$ret .= "</div></div>";
echo $ret;
}
?>