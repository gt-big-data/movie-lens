<?php
set_time_limit(300); // hahaha ^^ run php run ;)
include('dbco.php');
$m= mq("SELECT * FROM `bd_movies` WHERE neighbors=''");
while($mo = mysql_fetch_array($m)) {
$myMovie = $mo['id'];
$totalDistance = array(); $weight = array();
$score = array(); $ratingOfUsers = array();
for($i = 0; $i <= 2000; $i ++) {
	$totalDistance[$i] = 0; $weight[$i] = 0; $score[$i] = 0; $ratingOfUsers[$i] = 0;
}
$rat = mq("SELECT user, rating FROM bd_movieratings WHERE movie='$myMovie' AND rating>='3'");
$listOfUsers = array();
while($rati = mysql_fetch_array($rat)) {
array_push($listOfUsers, $rati['user']);
$ratingOfUsers[$rati['user']] = $rati['rating'];
}
$other = mq("SELECT movie, rating, user FROM bd_movieratings WHERE movie!='$myMovie' AND user IN (".implode(", ", $listOfUsers).")");
while($others = mysql_fetch_array($other)) {
	if($others['rating'] >= 3) {
		$totalDistance[$others['movie']] += abs($ratingOfUsers[$others['user']]-$others['rating']);
		$weight[$others['movie']] ++;
	}
}

$n = 5; // add n extra fake average-valued distances, "Bayesian-Average" 
// by standard deviation on 100.000 ratings:
// sqrt((6110*(1-3.5296)^2 + 11370*(2-3.5296)^2 +  27145*(3-3.5296)^2 + 34174*(4-3.5296)^2 +  21201*(5-3.5296)^2)/100000)
// "average distance": 1.12567
// $stdDev = 1.12567;
$stdDev = 2;
foreach ($totalDistance as $movie => $thisDistance) {
	$score[$movie] = ($totalDistance[$movie]+$n*$stdDev)/($weight[$movie]+$n);
}
$i = 0; $neighbors = array();
while($i <= 5) {
$maxs = array_keys($score, min($score));
array_push($neighbors, $maxs[0]);
unset($score[$maxs[0]]);
$i ++;
}
echo "Movies close to Movie ".$myMovie.": ".implode(" ", $neighbors)."<br />";
mq("UPDATE bd_movies SET neighbors='".implode(",", $neighbors)."' WHERE id='$myMovie'");
}
?>