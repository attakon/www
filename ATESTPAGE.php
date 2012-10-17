<?php
//echo mktime(0, 0, 0, 12, 12, 2008);
include "CustomTags.php";
// for($i=1;$i<10;$i++){
//     echo getSpanishDate($i, 10, 2009)."<br>";
// }

//regex test 
// $pattern = '/^\d+ \d+$/';
// $text = "1 20";

// $r = preg_match($pattern, $text, $matches, PREG_OFFSET_CAPTURE);
// print_r($r);
// print_r($matches);

// mail test
// $message = "Line 1\nLine 2\nLine 3";

// // In case any of our lines are larger than 70 characters, we should use wordwrap()
// $message = wordwrap($message, 70);

// $headers = "From: HuaHCoding <notifications@huahcoding.com>\r\n";

// // Send

// $accepted = mail('erreauele@gmail.com', 'My Subject', $message, $headers);
// echo $accepted;
// // mail test

include_once 'emailing.php';
sendWelcomeEmail('erreauele@gmail.com');
	
?>
