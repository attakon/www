<?php
	session_start();
	include_once 'utils/ValidateAdmin.php';
    $io=$_SESSION['io'];
    $problemName=$_SESSION['problemName'];

    include_once('data_objects/DAOProblem.php');
    DAOProblem_registerProblem($problemName,'1', $_SESSION['userId']);
    $problemId = DAOProblem_getProblemByName($problemName);
    foreach ($io as $key => $value) {
    	DAOProblem_registerTestCase($problemId, $value['i'], $value['o']);
    }
    include_once 'container.php';
    include_once 'CustomTags.php';
    showPage('Your problem got Created! FYeah', false, parrafoOK('problem '.$problemName.' was successfully created'), null,'250');
?>