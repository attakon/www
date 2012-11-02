<?php
//$userout = $_POST['userout'];
include 'conexion.php';
include_once 'CustomTags.php';


session_start();
include_once 'utils/ValidateSignedIn.php';


if(!isset($_FILES['userout']['tmp_name'])){
    include_once 'container.php';
    redirectToLastVisitedPage();
}

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
if(!isset($_POST['cmpid'])) die;

$problemId = $_POST['pid'];
$campaignId = $_POST['cmpid'];

include_once 'data_objects/DAOProblem.php';
$problemData = DAOProblem_getProblemData($problemId);

include_once 'data_objects/DAOCampaign.php';
$campaignData = DAOCampaign_getCampaignData($campaignId);

if(!$campaignData || !$problemData)
    die;

$problemName = $problemData['name'];

include_once 'data_objects/DAOContest.php';
$contestId = $campaignData['contest_id'];
$contestData = DAOContest_getContestData($contestId);
$contestPhase = DAOContest_getContestPhase($contestId);
// print_r($contestPhase);

if($contestPhase=='IN_PROGRESS'){
    // 2.- Check if the sourceName is empty
    if(empty($userSourceTempName)){
        $_SESSION['message']="source code cannot be empty";
        $_SESSION['message_type']="error";
        header("Location: ".$_SERVER["HTTP_REFERER"]);
        die;
    }

    include_once 'data_objects/DAOUser.php';
    $isUserRegistered = DAOUser_isUserRegisteredInContest($campaignData['id_usuario'],$campaignData['contest_id']);
    if(!$isUserRegistered){
        include_once 'container.php';
        showPage("X.X", false, parrafoError('user not allowed'), "");
        die;
    }
    $sessionUserId = $_SESSION['userId'];

    if($sessionUserId!=$campaignData['id_usuario']){
        include_once 'container.php';
        showPage("X.X", false, parrafoError('user not allowed'), "");
        die;
    }
    $isProblemSolved = DAOCampaign_isProblemSolved($campaignData['contest_id'], $campaignId, $problemId);

    if($isProblemSolved){
        include_once 'container.php';
        showPage("X.X", false, parrafoError('Problem has already been solved. Go pick another one ;)'), "");
        die;
    }

    // There has to be a pending submission for this problem
    $pendingSubmission = DAOCampaign_getPendingSubmission($campaignData['contest_id'], 
        $campaignId, $problemId);

    if(!$pendingSubmission){
        $lastVisitedPage = $_SESSION['lastvisitedurl'];
        $_SESSION['message']="You have to download the Input File first.";
        $_SESSION['message_type']="error";
        header("Location: ".$lastVisitedPage);
        die;
    }

    include_once 'utils/IOUtils.php';
    $answer = compareOutputs($userOutputTempName, $problemId, $pendingSubmission['io_seed']);
    

    $sourceContent = file_get_contents($userSourceTempName);
    $escapedSourceContent = mysql_real_escape_string($sourceContent);
    // $fp = fopen($userSourceTempName, 'r');
    // $escapedSourceContent = fread($fp, filesize($userSourceTempName));
    // $escapedSourceContent = mysql_real_escape_string($sourceContent);

    include_once 'data_objects/DAOCampaign.php';

    if($answer['accepted']) {
        
        DAOCampaign_registerSubmission($campaignData['contest_id'], 
        $campaignId, $problemId, 'NOW()', 
        $answer['accepted'], $escapedSourceContent);

        include_once 'data_objects/DAOUserEvents.php';
        include_once 'GLOBALS.php';
        // $problemName = mysql_escape_string($problemName);
        DAOUserEvents_logEventById($_SESSION['userId'],USER_EVENT_SUBMIT_CONTEST_SOLUTION,'successful for '.$problemName.'(id='.$problemData['problem_id'].')');

        //deprecating soon
        include_once('data_objects/DAOLog.php');
        $msgLog = $_SESSION['user']." solved $problemName";
        DAOLog_log($msgLog,'','');
        

        // DAOProblem_markAsSolved($problemId,$_SESSION['userId']);
        include_once 'container.php';
        $_SESSION['message']="Awesome! Accepted Solution for ".$problemName;
        $_SESSION['message_type']='ok';
        redirectToLastVisitedPage();
        // showPage("Fuck Yeah!", false, parrafoOK(), "");
    }else {

        DAOCampaign_registerSubmission($campaignData['contest_id'], 
        $campaignId, $problemId, 'NOW()', 
        $answer['accepted'], $escapedSourceContent,
        $answer['killer_case_id'], $answer['killed_answer']);

        include_once 'data_objects/DAOUserEvents.php';
        include_once 'GLOBALS.php';
        DAOUserEvents_logEventById($_SESSION['userId'],
            USER_EVENT_SUBMIT_CONTEST_SOLUTION,
            'failed for'.$problemName.'(id='.$problemData['problem_id'].': message:'.$answer['message']);

        // deprecating soon
        
        include_once('data_objects/DAOLog.php');
        $msgLog = $_SESSION['user']." failed solving ".$problemName;
        DAOLog_log($msgLog,$answer['message'],'');

        // include_once 'container.php';
        // showPage("X.X", false, parrafoError(), "");

        include_once 'container.php';
        $_SESSION['message']=$answer['message'];
        $_SESSION['message_type']='error';
        redirectToLastVisitedPage();
    }
}else if ($contestPhase=='FINISHED'){//invalid
    header("Location: contest_arena_pr.php?id=".$contestId);
}else if ($contestPhase=='NOT_STARTED'){//invalid
    include_once 'container.php';
    showPage("X.X", false, parrafoError('Not allowed to be here'), "");
}
?>
