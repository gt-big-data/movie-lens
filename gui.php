<?php
include("dbco.php");
echo "<body><link rel=\"stylesheet\" media=\"screen\" type=\"text/css\" title=\"Design\" href=\"css/gui.css\" />";
echo "<script type=\"text/javascript\" src=\"JS/jquerymin.js\"></script>";
echo "<script type=\"text/javascript\" src=\"JS/jquerycookie.js\"></script>";
echo "<script type=\"text/javascript\" src=\"JS/gui.js\"></script>";
$idU = 0; $pass = "";
if(isset($_COOKIE['dbUser']) AND isset($_COOKIE['dbPass'])) {
    $idU = mr($_COOKIE['dbUser']); $pass = mr($_COOKIE['dbPass']);
}
$u = mq("SELECT * FROM bd_users WHERE id='$idU' AND password='$pass'");
if(!$us = mfa($u)) {
    echo "<div id=\"loginDiv\">";
?>
    <div id="signInDiv"><div id="inTitle">Sign in</div>
    <div id="contain1">
    <input type="text" name="user" id="user" class="logInput" placeholder="Username" />
    <input type="password" name="pass" id="pass" class="logInput" placeholder="Password" /><br /><br />
    <input type="submit" name="login" onclick="login();" id="login" value="Sign in" /><br />
<?php
echo "</div></div>";
}
else { // you're logged in

$rating = array(); $neighbors = array();
$i = 0; $lastMovie = 0;
$r = mq("SELECT movie, rating FROM bd_movieratings WHERE user='$idU' ORDER BY id");
$ratings = ""; $list = array();
while($ra = mfa($r)) {
if($ratings != "") {$ratings .= ", ";}
array_push($list, $ra['movie']);
$ratings .= '{"m": '.$ra['movie'].', "r": '.$ra['rating'].'}';
$lastMovie = $ra['movie'];
}
$neighborList = "";
$m = mq("SELECT id, neighbors FROM bd_movies WHERE id IN (".implode(", ", $list).")");
while($mo = mysql_fetch_array($m)) {
if($neighborList != "") {$neighborList .= ", ";}
$neighborList .= '{"m": '.$mo['id'].', "n": "'.$mo['neighbors'].'"}';
}
$ratings = "[".$ratings."]";
$neighborList = "[".$neighborList."]";
echo "<script type=\"text/javascript\">initMovies('$ratings', '$neighborList');
nextMovie($lastMovie);
</script>";
echo "</body>";
}
?>