<?php

if(!isset($_SESSION['userId']))
	session_start();
$_SESSION['lastvisitedurl'] = $_SERVER['REQUEST_URI'];

?>