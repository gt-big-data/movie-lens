<?php
include('../dbco.php');
if(isset($_GET['user']) AND isset($_GET['pass']) AND isset($_GET['rating']) AND isset($_GET['movie'])) {
	$r = mq("SELECT * FROM bd_users WHERE id='".mr($_GET['user'])."' AND password='".mr($_GET['pass'])."'");
	if($re = mfa($r)) { // its indeed his account
		$user = mr($_GET['user']); $movie = mr($_GET['movie']); $rating = mr($_GET['rating']);
		$alread = mq("SELECT * FROM bd_movieratings WHERE user='$user' AND movie='$movie'");
		if($already = mfa($alread)) {mq("UPDATE bd_movieratings SET rating='$rating' WHERE id='".$already['id']."'");} // just change his old score
		else { // add a new score, yay ! :)
		mq("INSERT INTO `bd_movieratings` (`id`, `user`, `movie`, `rating`) VALUES (NULL, '".$user."', '".$movie."', '".$rating."');");
		}
		
		echo nextMovie($user);
	}
	else {
		echo "0";
	}
}
else {
	echo "0";
}
?>