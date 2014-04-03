<?php
include("dbco.php");
set_time_limit(300); // hahaha ^^ run php run ;)
$handle = fopen("items.txt", "r");
$i = 0;
if ($handle) {
    while (($line = fgets($handle)) !== false) {
    $i ++;
	$tok = explode("|", $line); $id = $tok[0]; $name = mr($tok[1]);
	// mysql_query("INSERT INTO `furro`.`bd_movies` (`id`, `movieName`) VALUES ('$id', '$name');");
    }
} else {
	echo "lol";
}
?>