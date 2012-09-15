<?php

if(!session_id())
	session_start();
$_SESSION['lastvisitedurl'] = $_SERVER['REQUEST_URI'];

?>