<?php
session_start();
include_once 'utils/ValidateSignedIn.php';
$signedInUserId = $_SESSION['userId'];

include_once 'CustomTags.php';

if(!isset($_GET['p']) || !isset($_GET['cpg'])){
    die;
}
$campaignId = $_GET['cpg'];
$problemId = $_GET['p'];

include_once 'data_objects/DAOCampaign.php';
$campaignData = DAOCampaign_getCampaignData($campaignId);
$contestId = $campaignData['contest_id'];
include_once 'data_objects/DAOContest.php';
$contestPhase = DAOContest_getContestPhase($contestId);
$contestData = DAOContest_getContestData($contestId);
if($contestData['creator_id']!=$_SESSION['userId'] && $contestPhase!='FINISHED'){
    include_once 'container.php';
    include_once 'CustomTags.php';
    showPage('X.X', false, parrafoError('Code is not available yet'), '');
}

include_once 'data_objects/DAOProblem.php';

$isSeenByUser = DAOProblem_isAlrearySeenByUserInPractite($problemId,$signedInUserId);

if(!$isSeenByUser && $contestData['creator_id']!=$_SESSION['userId']){
    if(isset($_GET['do'])){
        include_once 'data_objects/DAOUserEvents.php';
        include_once 'GLOBALS.php';
        DAOUserEvents_logEventById($_SESSION['userId'], USER_EVENT_UNLOCK_CODE, $problemId);

        DAOProblem_markProblemAsSeenInPractice($problemId, $signedInUserId);
    }else if(!DAOProblem_isSolvedByUserInContest($problemId, $signedInUserId)){
        $seeItPath = $_SERVER['PHP_SELF'].'?cpg='.$campaignId.'&p='.$problemId.'&do';
        $seeItLink = rCLink($seeItPath, null, 'See code anyways');
        // $concursoId = DAOProblem_getProblemConcursoId($problemId);
        $solveItPath = 'contest_arena.php?id='.$contestId.'&pid='.$problemId;
        $solveItLink = rCLink($solveItPath, null, 'Solve the problem');

        $content = parrafoOK("Si resuelves el problema antes de ver una solucion, lo tendras registrado en tu perfil como un problema azul");
        $content.=parrafoOK($seeItLink.' '.$solveItLink);
        include_once 'container.php';
        showPage('X.X', false, $content, 'onload="prettyPrint()"');
        die;
    }
}




include_once 'conexion.php';

//$campaignId ="1";
//$idProblem ="1";
include_once 'data_objects/DAOCampaign.php';
$codeData = DAOCampaign_getCampaignCode($campaignId, $problemId);
$code = $codeData['code'];

$title = "[".$codeData['contest_name']."] ".$codeData['username']."'s code for ".$codeData['problem_name'];
$dataLines = explode("\n", $code);
$maxCol=0;
$maxRow=sizeof($dataLines);
foreach ($dataLines as $line) {
    $maxCol = max($maxCol,strlen($line));
}
// $data[3]= str_replace("\t", "&nbsp;&nbsp;&nbsp;", $data[3]);
$code = "\n".$code;
$code= str_replace("<", "&lt;", $code);
$code= str_replace(">", "&gt;", $code);
// $data[3]= str_replace("\n", "<br/>", $data[3]);
include_once 'utils/MiscUtils.php';
$body = formatCode($title, $code,$maxRow,$maxCol*9);
include_once 'container.php';
showPage($title, false, $body , 'onload="prettyPrint()"');
?>