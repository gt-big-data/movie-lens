<?php
include('../dbco.php');
if(isset($_GET['user']) AND isset($_GET['pass'])) {
	$r = mq("SELECT * FROM bd_users WHERE user='".mr($_GET['user'])."' AND password='".md5(mr($_GET['pass']))."'");
	if($re = mfa($r)) {
		echo $re['id']."^!bd!^".md5(mr($_GET['pass']));
	}
	else {echo "0^!bd!^none";}
}
else {
	echo "0^!bd!^none";
}
?>