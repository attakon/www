<?php
	// echo $_SERVER['DOCUMENT_ROOT'];
	// include_once $_SERVER['DOCUMENT_ROOT'].'/helpers/config_options.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/data_objects/DAOUserEvents.php';
	$arr = DAOUserEvents_getUsersSubmissions();
	// print_r($arr);
	echo json_encode($arr);
?>	