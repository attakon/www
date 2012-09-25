<?php
//$userout = $_POST['userout'];
include 'conexion.php';
include_once 'CustomTags.php';
include_once 'container.php';

session_start();
include_once 'utils/ValidateSignedIn.php';

if(!isset($_POST['pid'])) die;

$problemId = $_POST['pid'];
$campaignId = $_POST['cmpid'];

$tmpName = $_FILES['userout']['tmp_name'];

if(empty($tmpName)){
    $_SESSION['message']="file cannot be empty";
    $_SESSION['message_type']="error";
    header("Location: ".$_SESSION['lastvisitedurl']);
    die;
}
$respuesta = compareOutputs($tmpName,$problemId);

include_once 'data_objects/DAOProblem.php';
$problemData = DAOProblem_getProblemData($problemId);
$problemName = $problemData['name'];

$userId = $_SESSION['userId'];
include_once 'data_objects/DAOCampaign.php';
DAOCampaign_registerAttempt($campaignId,$problemId,'NOW()',$respuesta[0],null);

if($respuesta[0]) {
    include_once 'data_objects/DAOUserEvents.php';
    DAOUserEvents_logEvent($_SESSION['userId'],'submit_a_solution','successful for $problemName');

    //deprecating soon
    include_once('data_objects/DAOLog.php');
    $msgLog = $_SESSION['user']." solved $problemName";
    DAOLog_log($msgLog,'','');

    DAOProblem_markAsSolved($idp,$_SESSION['userId']);
    showPage("Fuck Yeah!", false, parrafoOK("Accepted Solution for ".$problemName), "");
}else {
    include_once 'data_objects/DAOUserEvents.php';
    DAOUserEvents_logEvent($_SESSION['userId'],'submit_a_solution','failed for $problemName: message:$respuesta[1]');

    // deprecating soon
    
    include_once('data_objects/DAOLog.php');
    $msgLog = $_SESSION['user']." failed solving $problemName";
    DAOLog_log($msgLog,$respuesta[1],'');

    showPage("X.X", false, parrafoError($respuesta[1]), "");
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
        print_r($correctLine);
        $correctLine = trim($correctLine);
        if(strcmp($correctLine,"")!=0) {
            $i++;
            if(!feof($file_handle)) {
                $userLine = trim(str_replace("\n", "",fgets($file_handle)));
                print_r($userLine);
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
