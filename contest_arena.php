<?php

include_once 'CustomTags.php';

session_start();
include_once 'utils/ValidateSignedIn.php';
// print_r($_SERVER);
if(isset($_GET['id'])){
    $contestId = $_GET['id'];
    $userId = $_SESSION['userId'];
    include_once 'data_objects/DAOCampaign.php';
    $userCampaignData = DAOCampaign_getCampaignForUser($userId,$contestId);

    include_once 'data_objects/DAOConcurso.php';
    $contestData = DAOConcurso_getContestData($contestId);

    if($userCampaignData==null){
        include_once 'container.php';
        showPage($contestData['nombre'],false,parrafoError("You are not registered in this contest"));
        die;
    }

    include_once 'data_objects/DAOConcurso.php';
    $contestPhase = DAOConcurso_getContestPhase($contestId);
    $head ='';
    switch ($contestPhase) {
        case 'NOT_STARTED':
            include_once 'container.php';
            showPage($contestData['nombre'],false,parrafoError("Contest has not started yet."));
            die;
            break;
        case 'IN_PROGRESS':
            $leftSeconds = DAOConcurso_getContestLeftSeconds($contestId);
            $head = '<div id="left_time_div_'.$contestId.'" ></div>
                    <script type="text/javascript">
                        timers[timerCount++]={
                            div_name:"left_time_div_'.$contestId.'"
                            ,left_time:'.$leftSeconds.'
                            ,end_message:"contest has finished"
                            };
                    </script>';
            break;
        case 'FINISHED':
            $head = 'Contest has finished. Practice mode now.';
            break;
    }

    

    if(!DAOConcurso_isContestOpen($contestId)){
        
    }
    
    $contestName = $contestData['nombre'];
    
    $selectedProblemId=null;
    if(isset($_GET['pid'])){
        $selectedProblemId = $_GET['pid'];    
    }
    // echo practice($contestId);
    $arenaHTML = getArenaHTML($contestId,$selectedProblemId,$userCampaignData,$contestPhase=='IN_PROGRESS',$userId);
    $content = $head.$arenaHTML;
    include_once 'container.php';
    showPage($contestName.'\'s Arena',false,$content);
}


function getArenaHTML($contestId, $selectedProblemId=null, $userCampaignData, $isContest=0,$userId){

$campaignId = $userCampaignData['id_campaign'];
$contestId = $contestId?$contestId:1;
include_once 'data_objects/DAOConcurso.php';
$problemsData = DAOConcurso_getProblems($contestId);

include_once 'data_objects/DAOCompetitor.php';
$userProblemData = DAOCompetitor_getContestProblemsForUser($userId,$contestId);
$problemsData=$userProblemData;
print_r($problemsData);

$firstProblemData = $problemsData[0];
$problemId = $selectedProblemId?$selectedProblemId:$firstProblemData['problem_id'];
// $selectedProblemData;
foreach ($problemsData as $key => $value) {
    if($value['problem_id']==$problemId){
        $selectedProblemData = $value;
    }
}
$body="";
// print_r($problemsData);
ob_start();
?>
    <table cellpadding="0" cellspacing="0" style="border-collapse: collapse"
           align="center" width="100%" height="181" >
        <tr>
            <td height="24" width="190">&nbsp;</td>
            <td height="24" >
                <label class="problemTitle"><?php echo$selectedProblemData['name']?></label>
            </td>
        </tr>
        <tr>
            <td height="300" width="190" valign="top" rowspan="2">
                <table cellpadding="0" cellspacing="0" width="190" >
                    <?php
                    // while($problems = mysql_fetch_row($rsProb)){
                    foreach ($problemsData as $key => $problemValue) {
                        $classSelected = "";

                        $style = $problemValue['solved']?"style='color:#390'":"";

                        if($problemValue['problem_id']==$problemId){
                            if($isContest){
                                $classSelected="class='contest_selectedProblem'";
                            }else{
                                $classSelected="class='selectedProblem'";
                            }
                        }
                        ?>
                    <tr>
                        <td <?php echo $classSelected?> width="190" height="25">
                            <a <?php echo $style;?> href="./contest_arena.php?id=<?php echo$contestId?>&pid=<?php echo $problemValue['problem_id']?>">
                                <?php echo $problemValue['name']." (".$problemValue['points']."pts)"?>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </table>
            </td>
            <td <?php echo $isContest?'class = "contest_bordeable"':'class="bordeable"'; ?> height="30">
                <?php 
                if($selectedProblemData['solved']=='0'){
                    include_once 'contest_arena_submission_form_html.php';
                }else{
                    ?>
                    <h4 style="color:green; text-align:center; background-color:lightgreen">Solved</h4>
                    <br/><br/><br/>
                    <?php
                }
                ?>
            </td>
        </tr>
        <tr>
            <td height="300" >&nbsp;</td>
        </tr>
    </table>
<?php
$body .= ob_get_contents();
ob_clean();
return $body;
}
?>