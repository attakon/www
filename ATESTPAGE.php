<?php
//echo mktime(0, 0, 0, 12, 12, 2008);
include "CustomTags.php";
// for($i=1;$i<10;$i++){
//     echo getSpanishDate($i, 10, 2009)."<br>";
// }
$pattern = '/^\d+ \d+$/';
$text = "1 20";

$r = preg_match($pattern, $text, $matches, PREG_OFFSET_CAPTURE);
print_r($r);
print_r($matches);
	
?>
