<?php
include("dbco.php");
set_time_limit(300); // hahaha ^^ run php run ;)
$handle = fopen("users.txt", "r");
$i = 0;
if ($handle) {
    while (($line = fgets($handle)) !== false) {
    $i ++;
	$tok = explode("|", $line); $id = $tok[0]; $name = $tok[3];
	mysql_query("INSERT INTO `furro`.`bd_users` (`id`, `user`, `password`, `skip`) VALUES (NULL, '".$name.$id."', '', '');");
    }
} else {
	echo "lol";
}
?>