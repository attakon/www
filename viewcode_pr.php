<?php
session_start();
include_once 'utils/ValidateSignedIn.php';
$signedInUserId = $_SESSION['userId'];

include_once 'CustomTags.php';

if(!isset($_GET['pid']) || !isset($_GET['uid']) || !isset($_GET['cid'])){
    die;
}
$targetUserId = $_GET['uid'];
$problemId = $_GET['pid'];
$contestId = $_GET['cid'];

include_once 'data_objects/DAOProblem.php';

$isSeenByUser = DAOProblem_isAlrearySeenByUserInPractite($problemId, $signedInUserId);

if(!$isSeenByUser){
    if(isset($_GET['do'])){
        DAOProblem_markProblemAsSeenInPractice($problemId, $signedInUserId);
    }else if(!DAOProblem_isSolvedByUserInContest($problemId, $signedInUserId)){
        // $seeItPath = $_SERVER['PHP_SELF'].'?cpg='.$campaignId.'&p='.$problemId.'&do';
        $seeItPath = 'viewcode_pr.php?uid='.$targetUserId.'&pid='.$problemId.'&do';
        $seeItLink = rCLink($seeItPath, null, 'See code anyways');
        // $concursoId = DAOProblem_getProblemConcursoId($problemId);
        $solveItPath = 'contest_arena_pr.php?id='.$contestId.'&pid='.$problemId;
        $solveItLink = rCLink($solveItPath, null, 'Solve the problem');

        $content = parrafoOK("Si resuelves el problema antes de ver una solucion, lo tendras registrado en tu perfil como un problema azul");
        $content.=parrafoOK($seeItLink.' '.$solveItLink);
        include_once 'container.php';
        showPage('X.X', false, $content, 'onload="prettyPrint()"');
        die;
    }
}

include_once 'conexion.php';

include_once 'data_objects/DAOCampaign.php';
$codeData = DAOCampaign_getPracticeCampaignCode($targetUserId, $problemId);
$code = $codeData['code'];
if(!$codeData['code']){
    include_once 'container.php';
    showPage("no code", false, $codeData['username']." has not submitted for this problem");
    die;
}

$title = $codeData['username']."'s code for ".$codeData['problem_name'];
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