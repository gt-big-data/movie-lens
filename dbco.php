<?php
mysql_connect("localhost", "root", "");
mysql_select_db("furro");
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Europe/Paris');   // On met l'heure à jour !!! :D
mysql_query("SET CHARACTER SET utf8 ");
function mq($quer) {return mysql_query($quer);}
function mfa($qq) {return mysql_fetch_array($qq);}
function mr($qq) {return mysql_real_escape_string($qq);}
function nextMovie($idU) {
	include("google.php");
	$g= new GoogleImages();
	//SELECT * FROM `bd_movies` t1 WHERE NOT EXISTS(SELECT * FROM `bd_movieratings` t2 WHERE (t2.user='1' AND t2.movie = t1.id)) ORDER BY id LIMIT 1
	// this would do the job but it runs way too slow on mysql
	$n = mysql_query("SELECT MAX(movie) AS max FROM bd_movieratings WHERE user='$idU'"); $ne = mysql_fetch_array($n);
	$nextMovie = $ne['max']+1;
	$r = mysql_query("SELECT * FROM bd_movies WHERE id='".$nextMovie."' LIMIT 4"); $re = mysql_fetch_array($r);
	$ret =  "<div class=\"movieClass\" id=\"m".$re['id']."\"><img src=\"".$g->get($re['movieName'])."\" class=\"movieImg\" alt=\"".$re['movieName']."\" title=\"".$re['movieName']."\"  />
	<div class=\"starDiv\"><img src=\"images/x.gif\" class=\"x\" title=\"I don't know this movie\" onclick=\"addRating(-1, ".$re['id'].");\" />";
	for($i = 1; $i <= 6; $i ++) {
    $ret .= "<img src=\"images/star".$i.".png\" class=\"stars star".$i."\" title=\"".($i-1)." Stars\" onclick=\"addRating(".($i-1).", ".$re['id'].");\" onmouseover=\"choose(".($i-1).", ".$re['id'].");\" />";
	}
	$ret .= "</div></div>";
	return $ret;
}
?>