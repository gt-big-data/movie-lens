<?php
$arr = array(1 => 3 ,2 => 7,3 => 11, 5 => 2,4 => 5.5);
$maxs = array_keys($arr, max($arr));
array_splice($arr, $maxs[0], 1);
print_r($arr);

?>