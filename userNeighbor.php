<?php
// some parameters to play with
$k = 5; // number of user neighbors
$k2 = 3; // number of recommendations
$n = 3; // bayesian average param
$n2 = 2; // number of average ratings added
set_time_limit(300); // hahaha ^^ run php run ;)
include('dbco.php');
$myUser = 944;
$totalDistance = array(); $weight = array();
$score = array(); $ratingOfMovies = array();
for($i = 0; $i <= 1000; $i ++) {
	$totalDistance[$i] = 0; $weight[$i] = 0; $score[$i] = 0; $ratingOfMovies[$i] = 0;
}

$rat = mq("SELECT movie, rating FROM bd_movieratings WHERE user='$myUser' AND rating!='-1'");
$listOfMovies = array();
while($rati = mfa($rat)) {
array_push($listOfMovies, $rati['movie']);
$ratingOfMovies[$rati['movie']] = $rati['rating'];
}
$other = mq("SELECT movie, rating, user FROM bd_movieratings WHERE user!='$myUser' AND rating!='-1' AND movie IN (".implode(", ", $listOfMovies).")");
while($others = mfa($other)) {
		$totalDistance[$others['user']] += abs($ratingOfMovies[$others['movie']]-$others['rating']);
		$weight[$others['user']] ++;
}
// calculate distances, get nearest neighbors
$stdDev = 4;
foreach ($totalDistance as $user => $thisDistance) {
	$score[$user] = ($totalDistance[$user]+$n*$stdDev)/($weight[$user]+$n);
}
$i = 1; $neighbors = array();
while($i <= $k) {
$maxs = array_keys($score, min($score));
array_push($neighbors, $maxs[0]);
unset($score[$maxs[0]]);
$i ++;
}
// we have the neighbors, find the best recommendations of these neighbors
$allMoviesTotal = array(); $allMoviesCount = array(); $allMoviesScore = array();
$rati = mq("SELECT movie, rating FROM bd_movieratings WHERE rating>='3' AND user IN (".implode(", ", $neighbors).") AND movie NOT IN (".implode(", ", $listOfMovies).")");
while($ratin = mfa($rati)) {
$mov = $ratin['movie']; $rat = $ratin['rating'];
if(in_array($ratin['movie'], $listOfMovies) or $mov == 1) {echo "WTF<br />";}
else {
if(!isset($allMoviesTotal[$mov])) {$allMoviesTotal[$mov] = $rat; $allMoviesCount[$mov] = 1;}
else {$allMoviesTotal[$mov] += $rat; $allMoviesCount[$mov] ++;}
}
}
// calculate avg score, get max scores
$avgScore = 3;
foreach ($allMoviesTotal as $movie => $total) {
	$allMoviesScore[$movie] = ($allMoviesTotal[$movie]+$n2*$avgScore)/($allMoviesCount[$movie]+$n2);
}
$i = 1; $recommend = array();
while($i <= $k2) {
// echo max($allMoviesScore)."<br/>";
$maxs = array_keys($allMoviesScore, max($allMoviesScore));
array_push($recommend, $maxs[0]);
unset($allMoviesScore[$maxs[0]]);
$r = mq("SELECT movieName FROM bd_movies WHERE id='".$maxs[0]."'");
if($re = mfa($r)) {echo $re['movieName']."<br />";}
$i ++;
}
// echo "Hey: ".implode(",", $listOfMovies);
?>