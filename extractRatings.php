<?php
include("dbco.php");
set_time_limit(300); // hahaha ^^ run php run ;)
$handle = fopen("data.txt", "r");
$i = 0;
if ($handle) {
    while (($line = fgets($handle)) !== false) {
    $i ++;
	$tok = explode(" ", $line); $user = $tok[0]; $movie = $tok[1]; $rating = $tok[2];
	if($rating > 5 or $rating < 0 or (count($tok) < 3)) {echo "Error<br />";}
	else {
	// mysql_query("INSERT INTO `bd_movieratings` (`id`, `user`, `movie`, `rating`) VALUES (NULL, '".$user."', '".$movie."', '".$rating."');");
	if(($i%100) == 0) {echo "I: ".$i."<br />";}
	}
    
    }
} else {
	echo "lol";
}
?>