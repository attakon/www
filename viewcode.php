<?php
session_start();
include_once 'utils/ValidateSignedIn.php';
$signedInUserId = $_SESSION['userId'];

include_once 'CustomTags.php';

$idCampaign = $_GET['cpg'];
$problemId = $_GET['p'];

include_once 'data_objects/DAOCampaign.php';
$campaignData = DAOCampaign_getCampaignData($idCampaign);
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
        DAOProblem_markProblemAsSeenInPractice($problemId, $signedInUserId);
    }else if(!DAOProblem_isSolvedByUserInContest($problemId, $signedInUserId)){
        $seeItPath = $_SERVER['PHP_SELF'].'?cpg='.$idCampaign.'&p='.$problemId.'&do';
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

//$idCampaign ="1";
//$idProblem ="1";
$queryData = "SELECT us.username, prob.name, con.nombre, cd.successful_source_code FROM
     usuario us, concurso con, co_problem prob, campaigndetalle cd, campaign ca
     WHERE us.id_usuario = ca.id_usuario AND
        ca.contest_id = con.contest_id AND
        ca.id_campaign = cd.id_campaign AND
        prob.problem_id = cd.problem_id AND
        ca.id_campaign = '".$idCampaign."' AND
        prob.problem_id = '".$problemId."'";
//echo $queryData;
$rsData = mysql_query($queryData, conecDb()) or die ($queryData);
$data = mysql_fetch_row($rsData);

$title = $data[2]." > Problema ".$data[2]."(".$data[1].") > c&oacute;digo de ".$data[0];
$dataLines = explode("\n", $data[3]);
$maxCol=0;
$maxRow=sizeof($dataLines);
foreach ($dataLines as $line) {
    $maxCol = max($maxCol,strlen($line));
}
// $data[3]= str_replace("\t", "&nbsp;&nbsp;&nbsp;", $data[3]);
$data[3]="\n".$data[3];
$data[3]= str_replace("<", "&lt;", $data[3]);
$data[3]= str_replace(">", "&gt;", $data[3]);
// $data[3]= str_replace("\n", "<br/>", $data[3]);
$body = formatCode($title, $data[3],$maxRow,$maxCol*9);

?>
    
<?php 

include_once 'container.php';
showPage($title, false, $body , 'onload="prettyPrint()"');

function formatCode($title, $body, $row, $col) {
    // echo $body;
    ob_start();

    ?>

<link href="styles/prettify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="js/prettify.js"></script>
<link href="styles/sunburst.css" type="text/css" rel="stylesheet" />
<!-- <link href="styles/son-of-obsidian.css" type="text/css" rel="stylesheet" /> -->

<div style="text-align:center">
    <pre class="prettyprint" style="width:<?php echo $col?>px; text-align:left">
        <?php echo $body;?>
    </pre>
</div>

    <?php
    $r = ob_get_contents();
    ob_end_clean();
    return $r;
}
?>
