<?php
include_once("data_objects/DAOUserEvents.php");
session_start();
$req = $_GET['req'];
if($req=='cs' && $_SESSION['userId']!=null ){                     //close session
    

   	DAOUserEvents_logEvent($_SESSION['userId'],'log_out','');

	$_SESSION['user']=null;
    $_SESSION['userId']=null;
    $_SESSION['wpass']=null;

    
    header('Location: '.$_SESSION['lastvisitedurl']);

}
?>
