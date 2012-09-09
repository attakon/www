<?php
//chdir('./forums');
require_once('./global.php');
$url = "./login.php?" . $session[sessionurl] . "do=logout&logouthash=" . $vbulletin->userinfo['logouthash'];

header("Location: $url");
?>
