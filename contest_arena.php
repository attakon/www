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
print_r($userProblemData);

$firstProblemData = $problemsData[0];
$problemId = $selectedProblemId?$selectedProblemId:$firstProblemData['problem_id'];
foreach ($problemsData as $key => $value) {
    if($value['problem_id']==$problemId){
        $problemName = $value['name'];
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
                <label class="problemTitle"><?php echo$problemName?></label>
            </td>
        </tr>
        <tr>
            <td height="300" width="190" valign="top" rowspan="2">
                <table cellpadding="0" cellspacing="0" width="190" >
                    <?php
                    // while($problems = mysql_fetch_row($rsProb)){
                    foreach ($problemsData as $key => $problemValue) {
                        $classSelected = "";
                        if($problemValue['problem_id']==$problemId){
                            if($isContest){
                                $classSelected="class='contest_selectedProblem'";
                            }else{
                                $classSelected="class='selectedProblem'";
                            }
                        }
                        ?>
                    <tr>
                        <td <?php echo$classSelected?> width="190" height="25">
                            <a href="./contest_arena.php?id=<?php echo$contestId?>&pid=<?php echo $problemValue['problem_id']?>">
                                <?php echo $problemValue['name']." (".$problemValue['solved'].")"?>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </table>
            </td>
            <td <?php echo $isContest?'class = "contest_bordeable"':'class="bordeable"'; ?> height="30">
                <label>
                    <br>
                    When you are ready, download the input file.
                    <br>&nbsp;
                </label>
                <!--BEGIN SUBMIT FORM -->
                <form method="POST" action="contest_arena_process_submit.php" enctype="multipart/form-data">
                    <table border="0" width="600" height="50" >
                        <tr>
                            <td colspan="2" width="100%">
                                <?php
                                    include_once 'data_objects/DAOCampaign.php';
                                    $isSubmissionPending = DAOCampaign_isSubmissionPending($contestId, $campaignId, $problemId);
                                    $result ='';
                                    // print_r($isSubmissionPending);
                                    $downloadLink = "<a href='contest_arenadownloadinput.php?pid=".$problemId."&cmpid=".$userCampaignData['id_campaign']."'>Download Input File</a>";
                                    if($isSubmissionPending){
                                        $divId = "submission_left_time'.$contestId.'_'.$campaignId.'_'.$problemId.'";
                                        $countDown = '<div id="'.$divId.'"></div>
                                        <script type="text/javascript">
                                            timers[timerCount++]={
                                                div_name:"'.$divId.'"
                                                ,left_time:'.$isSubmissionPending['submission_left_time'].'
                                                ,end_message:"'.$downloadLink.'"
                                                };
                                        </script>';
                                        $result = $countDown;
                                    }else{
                                        $result = $downloadLink;
                                        // '<a href="contest_arenadownloadinput.php?pid='.$problemId.'&cmpid='.$userCampaignData['id_campaign'].'">Download Input File</a>';
                                    }
                                    echo $result;
                                ?>
                                
                            </td>
                        </tr>
                        <tr>
                            <td height="26" width="86">

                                <p>Your output file:</p>
                            </td>
                            <td height="26" width="386">
                                <input type="file" name="userout" size="39">
                            </td>
                        </tr>
                        <tr>
                            <td height="26" width="86">

                                <p>Your source code:</p>
                            </td>
                            <td height="26" width="386">
                                <input type="file" name="usersource" size="39">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <label class="comment">
                                    <!-- Para practicar no se requiere presentar el c&oacute;digo fuente. -->
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td height="30" width="478" colspan="2">
                                <p align="center">
                                    <input type="submit" value="Submit solution" name="B1">
                                    <input type="hidden" name="pid" value=<?php echo$problemId?> >
                                    <input type="hidden" name="cmpid" value=<?php echo$userCampaignData['id_campaign']?> >
                                </p>
                            </td>            
                        </tr>
                    </table>
                </form>
                <!--END SUBMIT FORM -->
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