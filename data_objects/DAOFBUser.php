<?php
include_once ("utils/DBUtils.php");

function DAOFBUser_isFBUserRegistered($fbID){
   $query = "SELECT count(id)  FROM fb_users WHERE id = '".$fbID."'";
   $n = getRow($query);
   return $n==1;
}

function DAOFBUser_register($fbUser){
	DAOFBUser_insertHometownIfNotExists($fbUser['hometown']);
   	$insertQ = "INSERT INTO fb_users(id, name, first_name, last_name, link,
      username, hometown_id, location_id, education_id, gender, updated_time) VALUES
    ('".$fbUser['id']."', '".$fbUser['name']."', '".$fbUser['first_name']."', '".$fbUser['last_name']."', '".$fbUser['link']."', '".$fbUser['username']."', '".$fbUser['']."')";
    runQuery($insertQ);
}

function DAOFBUser_insertHometownIfNotExists($fbHometown){
	$query = "SELECT hometown_id FROM hometown where hometown_id = '".$fbHometown['id']."'";
	$n = getRow($query);
	if($n==0){
		$insert = "INSERT INTO hometown (hometown_id, name) VALUES ('".$fbHometown['id']."','".$fbHometown['name']."')";
		runQuery($query);
	}
}

function DAOFBUser_insertLocationIfNotExists($fbLocation){
	$query = "SELECT location_id FROM location where location_id = '".$fbLocation['id']."'";
	$n = getRow($query);
	if($n==0){
		$insert = "INSERT INTO location (location_id, name) VALUES ('".$fbLocation['id']."','".$fbLocation['name']."')";
		runQuery($query);
	}
}
?>