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
    die;
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
        $seeItPath = 'viewcode.php?cpg='.$campaignId.'&p='.$problemId.'&do';
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
$code= str_replace("<", "&lt;", $code);
$code= str_replace(">", "&gt;", $code);
// $data[3]= str_replace("\n", "<br/>", $data[3]);
include_once 'utils/MiscUtils.php';
$codeArea = formatCode($title, $code,$maxRow,$maxCol*9);
include_once 'container.php';
$jqscript = '
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    <script >
        jQuery(document).ready(function() {
            prettyPrint();
            jQuery("#buttons").buttonset();
            // jQuery("#label1").css("height","20px").css("padding-top","5px");
            jQuery(".ui-button-text-only .ui-button-text")
                .css("height","10px")
                .css("display","inline");
            jQuery("#radio1").click(function() {
                jQuery("#dislike").fadeIn("slow");
                jQuery("#dislike").css("background", "no-repeat url(//s.ytimg.com/yts/imgbin/www-refresh-vfl5Dug1g.png) -44px -182px");
            });
            jQuery("#radio2").click(function() {
                jQuery("#dislike").fadeIn("slow");
                jQuery("#dislike").css("background", "no-repeat url(//s.ytimg.com/yts/imgbin/www-refresh-vfl5Dug1g.png) -114px 0");
              // alert("Handler for .click() called.");
            });
        });
    </script>';
// $body = '<div>
//         <img id="like" src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" />
//         <img id="dislike" src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" />
//         </div>'.$codeArea;
$buttons = 
    '<div id="buttons">
    <input type="radio" name="radio" id="radio1" ><label id="label1" style="font-family: courier;" for="radio1">--</label>
    <div <div style="display:inline; padding:2px;" >
        <img id="dislike"  style="vertical-align: middle; display:none" src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" />
    </div>
    <input type="radio" name="radio" id="radio2" ><label style="font-family: courier;" for="radio2">++</label>
    </div>';

$body = $jqscript.$codeArea;
// $body = $jqscript.$buttons.$codeArea;
showPage($title, false, $body ,'');

?>