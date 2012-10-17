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

    include_once 'data_objects/DAOContest.php';
    $contestData = DAOContest_getContestData($contestId);

    if($userCampaignData==null){
        include_once 'container.php';
        showPage($contestData['nombre'],false,parrafoError("You are not registered in this contest"));
        die;
    }

    include_once 'data_objects/DAOContest.php';
    $contestPhase = DAOContest_getContestPhase($contestId);
    $head ='';
    switch ($contestPhase) {
        case 'NOT_STARTED':
            include_once 'container.php';
            showPage($contestData['nombre'],false,parrafoError("Contest has not started yet."));
            die;
            break;
        case 'IN_PROGRESS':
            $leftSeconds = DAOContest_getContestLeftSeconds($contestId);
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

    

    if(!DAOContest_isContestOpen($contestId)){
        
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
include_once 'data_objects/DAOContest.php';
$problemsData = DAOContest_getProblems($contestId);

include_once 'data_objects/DAOCompetitor.php';
$userProblemData = DAOCompetitor_getContestProblemsForUser($userId,$contestId);
$problemsData=$userProblemData;
// print_r($problemsData);

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

// example cases
$exampleCases = $selectedProblemData['example_cases'];

        $tablesPC="co_problem_testcase ptc, co_problem pr , (SELECT @rownum:=0) r";
        $columnsPC = array(
        array("ptc.testcase_id ",  "",     -1, ""),
        array("ptc.case_input",  "Input",     -2, "","",
            'td_atr'=>'style ="border-width:2px; border-style:ridge; font-family:courier;"'),
        array("ptc.case_output",  "Output",     -2, "","",
            'td_atr'=>'style ="border-width:2px; border-style:ridge; font-family:courier;"'),
        array("ptc.explanation",  "Explanation",     -2, "","",
            'td_atr'=>'style ="border-width:2px; border-style:ridge; font-family:courier;"')
        );
        $conditionPC = "WHERE ptc.problem_id = pr.problem_id ".
            " AND pr.problem_id = '".$problemId."' ".
            " ORDER BY 1 ASC LIMIT ".$exampleCases;

        include_once 'table2.php';
        $manageContestTable = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionPC);
        $manageContestTable->showLineBreaks(true);


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
                if($isContest){
                    if($selectedProblemData['solved']=='0'){
                        include_once 'contest_arena_submission_form_html.php';
                    }else{
                        ?>
                        <h4 style="color:green; text-align:center; background-color:lightgreen">Solved</h4>
                        <br/><br/><br/>
                        <?php
                    }
                }else{
                    include_once 'contest_arena_submission_form_html.php';
                }
                
                ?>
            </td>
        </tr>
        <tr>
            <td height="300" style="padding-left: 10px;
                                padding-right: 10px;
                                width: 1000px;" >
            <?php echo $selectedProblemData['statement'].'<br/><h4 style="text-align:center">Example Cases</h4>'.$manageContestTable->getTable(); ?></td>
        </tr>
    </table>
<?php
$body .= ob_get_contents();
ob_clean();
return $body;
}
?>