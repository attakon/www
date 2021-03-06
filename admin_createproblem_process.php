<?php
	session_start();
	include_once 'utils/ValidateAuthor.php';
    $io=$_SESSION['io'];
    $inputList=$io['inputList'];
    $outputList=$io['outputList'];

    $problemName=$_SESSION['problemName'];
    $exampleCasesCount=$_SESSION['exampleCasesCount'];

    include_once('data_objects/DAOProblem.php');
    DAOProblem_registerProblem($problemName,'1', $_SESSION['userId'],$exampleCasesCount);
    // TODO chance to improve
    $problemId = DAOProblem_getProblemByName($problemName);

    for ($i =0;$i<sizeof($inputList);$i++) {
    	DAOProblem_registerTestCase($problemId, $inputList[$i], $outputList[$i]);
    }

    unset($_SESSION['io']);
    unset($_SESSION['problemName']);
    unset($_SESSION['exampleCasesCount']);

    include_once 'container.php';
    include_once 'CustomTags.php';
    $_SESSION['message']='problem '.$problemName.' was created';
    $_SESSION['message_type']='ok';
    header('Location: admin_myproblems.php');
    // showPage('Your problem got Created! FYeah', false, parrafoOK('problem '.$problemName.' was successfully created'), null,'250');
?>