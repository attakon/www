<?php
//$userout = $_POST['userout'];
include 'conexion.php';
include_once 'CustomTags.php';


session_start();
include_once 'utils/ValidateSignedIn.php';

if(!isset($_POST['pid'])) die;
if(!isset($_POST['cmpid'])) die;

$problemId = $_POST['pid'];
$campaignId = $_POST['cmpid'];

$userOutputTempName = $_FILES['userout']['tmp_name'];
$userSourceTempName = $_FILES['usersource']['tmp_name'];


if(empty($userOutputTempName)){
    $_SESSION['message']="output file cannot be empty";
    $_SESSION['message_type']="error";
    // header("Location: ".$_SESSION['lastvisitedurl']);
    header("Location: ".$_SERVER["HTTP_REFERER"]);
    die;
}
// print_r($_SERVER);
if(empty($userSourceTempName)){
    $_SESSION['message']="source code cannot be empty";
    $_SESSION['message_type']="error";
    header("Location: ".$_SERVER["HTTP_REFERER"]);
    die;
}

include_once 'data_objects/DAOCampaign.php';
$campaignData = DAOCampaign_getCampaignData($campaignId);
// print_r($campaignData);

include_once 'data_objects/DAOConcurso.php';
$contestData = DAOConcurso_getContestData($campaignData['id_concurso']);
$contestPhase = DAOConcurso_getContestPhase($campaignData['id_concurso']);
// print_r($contestPhase);

if($contestPhase=='IN_PROGRESS'){
    include_once 'data_objects/DAOUser.php';
    $isUserRegistered = DAOUser_isUserRegisteredInContest($campaignData['id_usuario'],$campaignData['id_concurso']);
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
    $isProblemSolved = DAOCampaign_isProblemSolved($campaignData['id_concurso'], $campaignId, $problemId);
    if($isProblemSolved){
        include_once 'container.php';
        showPage("X.X", false, parrafoError('Problem has already been solved. Go pick another one ;)'), "");
        die;
    }
    $respuesta = compareOutputs($userOutputTempName,$problemId);

    


    $sourceContent = file_get_contents($userSourceTempName);
    $escapedSourceContent = mysql_real_escape_string($sourceContent);
    // $fp = fopen($userSourceTempName, 'r');
    // $escapedSourceContent = fread($fp, filesize($userSourceTempName));
    // $escapedSourceContent = mysql_real_escape_string($sourceContent);

    include_once 'data_objects/DAOCampaign.php';

    DAOCampaign_registerSubmission($campaignData['id_concurso'], $campaignId, $problemId,'NOW()',$respuesta[0],$escapedSourceContent);


    include_once 'data_objects/DAOProblem.php';
    $problemData = DAOProblem_getProblemData($problemId);
    $problemName = $problemData['name'];

    if($respuesta[0]) {
        include_once 'data_objects/DAOUserEvents.php';
        DAOUserEvents_logEvent($_SESSION['userId'],'submit_a_solution','successful for '.$problemName);

        //deprecating soon
        include_once('data_objects/DAOLog.php');
        $msgLog = $_SESSION['user']." solved $problemName";
        DAOLog_log($msgLog,'','');

        // DAOProblem_markAsSolved($problemId,$_SESSION['userId']);
        include_once 'container.php';
        $_SESSION['message']="Accepted Solution for ".$problemName;
        $_SESSION['message_type']='ok';
        redirectToLastVisitedPage();
        // showPage("Fuck Yeah!", false, parrafoOK(), "");
    }else {
        include_once 'data_objects/DAOUserEvents.php';
        DAOUserEvents_logEvent($_SESSION['userId'],'submit_a_solution','failed for $problemName: message:$respuesta[1]');

        // deprecating soon
        
        include_once('data_objects/DAOLog.php');
        $msgLog = $_SESSION['user']." failed solving ".$problemName;
        DAOLog_log($msgLog,$respuesta[1],'');

        include_once 'container.php';
        showPage("X.X", false, parrafoError($respuesta[1]), "");
    }
}else if ($contestPhase=='FINISHED'){//practice mode
    include_once 'container.php';
    // redirectToLastVisitedPage();
    showPage("X.X", false, parrafoError('go to practice mode'), "");
}else if ($contestPhase=='NOT_STARTED'){//invalid
    include_once 'container.php';
    showPage("X.X", false, parrafoError('not allowed to be here'), "");
}

function compareOutputs($tmpName, $problemId) {

    // print_r($tmpName);
    if(isset($_SESSION['outputContent_'.$problemId])){
        $correctOutputContent = $_SESSION['outputContent_'.$problemId];
        unset($_SESSION['outputContent_'.$problemId]);
        unset($_SESSION['inputContent_'.$problemId]);
    }else{
        $lastVisitedPage = $_SESSION['lastvisitedurl'];
        $_SESSION['message']="You have to download the input file first.";
        $_SESSION['message_type']="error";
        header("Location: ".$lastVisitedPage);
        die;
    }
    $correct = explode("\n", $correctOutputContent);
    $file_handle = fopen($tmpName, "r");
    $i = 0;
    $res;

    foreach($correct as $correctLine) {
        // print_r($correctLine);
        $correctLine = trim($correctLine);
        if(strcmp($correctLine,"")!=0) {
            $i++;
            if(!feof($file_handle)) {
                $userLine = trim(str_replace("\n", "",fgets($file_handle)));
                // print_r($userLine);
                if(strcmp($correctLine, $userLine)!=0) {
                    fclose($file_handle);
                    return array(false,"Wrong Answer: line ".$i." tu salida[".$userLine."] esperado[".$correctLine."]");
                    // break;
                }
            }else {
                fclose($file_handle);
                return array(false,"Respuesta incorrecta tu salida[] esperado[".$userLine."]");
                // break;
            }
        }
    }
    fclose($file_handle);
    $res = array(true,true);
    return $res;
}
?>
