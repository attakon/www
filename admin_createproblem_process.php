<?php
	include_once 'utils/ValidateAdmin.php';
    $_SESSION['io']=$io;
    $_SESSION['problemName']=$problemName;

    include_once('data_objects/DAOProblem.php');
    DAOProblem_registerProblem($problemName,'1');
    $problemName = DAOProblem_getProblemByName($problemName,'1');
    foreach ($io as $key => $value) {
    	DAOProblem_registerTestCase($problemName, $value['i'], $value['o']);
    }
    include_once 'container.php';
    include_once 'CustomTags.php';
    showPage('Reset User Password', false, parrafoOK('problem '.$problemName.' was created successfully'), null,'250');
?>