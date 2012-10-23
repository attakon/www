<?php
//$userout = $_POST['userout'];
include 'conexion.php';
include_once 'CustomTags.php';


session_start();
include_once 'utils/ValidateSignedIn.php';

$userOutputTempName = $_FILES['userout']['tmp_name'];
$userSourceTempName = $_FILES['usersource']['tmp_name'];

// 1.- Check if the output file is empty
if(empty($userOutputTempName)){
    $_SESSION['message']="output file cannot be empty";
    $_SESSION['message_type']="error";
    // header("Location: ".$_SESSION['lastvisitedurl']);
    header("Location: ".$_SERVER["HTTP_REFERER"]);
    die;
}
// 2 Check if the arguments are sent.
if(!isset($_POST['pid'])) die;
if(!isset($_POST['cid'])) die;

$problemId = $_POST['pid'];
$contestId = $_POST['cid'];

include_once 'data_objects/DAOProblem.php';
$problemData = DAOProblem_getProblemData($problemId);
if(!$problemData)
    die;

$problemName = $problemData['name'];

include_once 'data_objects/DAOContest.php';
$contestData = DAOContest_getContestData($contestId);
$contestPhase = DAOContest_getContestPhase($contestId);

if($contestPhase!='FINISHED'){
    die;
}

$isSourceFileEmpty = empty($userSourceTempName);

if(!$isSourceFileEmpty){
    $sourceContent = file_get_contents($userSourceTempName);
    $escapedSourceContent = mysql_real_escape_string($sourceContent);
    // $_SESSION['message']="source code cannot be empty";
    // $_SESSION['message_type']="error";
    // header("Location: ".$_SERVER["HTTP_REFERER"]);
    // die;
}
include_once 'data_objects/DAOProblem.php';
include_once 'utils/IOUtils.php';
$answer = compareOutputs($userOutputTempName, $problemId);

if($answer['accepted']){
    include_once 'data_objects/DAOUserEvents.php';
    include_once 'GLOBALS.php';
    DAOUserEvents_logEventById($_SESSION['userId'], LOG_SUBMIT_PRACTICE_SOLUTION, 'successful for '.$problemName);

    //deprecating soon
    include_once('data_objects/DAOLog.php');
    $msgLog = $_SESSION['user']." solved ".$problemName;
    DAOLog_log($msgLog,'','');


    if(!$isSourceFileEmpty){
        DAOProblem_markAsSolved($problemId,$_SESSION['userId'],$escapedSourceContent);
    }else{
        DAOProblem_markAsSolved($problemId,$_SESSION['userId']);
    }
    $_SESSION['message']="(Practice Mode) Accepted Solution for ".$problemName;
    $_SESSION['message_type']='ok';
}else{
    $failedMessage = 'failed for '.$problemName.': message:'.$answer['message'];
    include_once 'data_objects/DAOUserEvents.php';
    DAOUserEvents_logEventById($_SESSION['userId'], LOG_SUBMIT_PRACTICE_SOLUTION, $failedMessage);

    // deprecating soon
    include_once('data_objects/DAOLog.php');
    $msgLog = $_SESSION['user']." failed solving ".$problemName;
    DAOLog_log($msgLog,$answer['message'],'');

    $_SESSION['message']="(Practice Mode) Denied Solution for ".$problemName.". ".$answer['message'];
    $_SESSION['message_type']='error';

    // include_once 'container.php';
    // showPage("X.X", false, parrafoError($failedMessage), "");
}
include_once 'container.php';
redirectToLastVisitedPage();
?>
