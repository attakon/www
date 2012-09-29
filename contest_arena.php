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
            $leftTime = DAOConcurso_getContestLeftTime($contestId);
            $head = '<div id="left_time_div_'.$contestId.'" ></div>
                    <script type="text/javascript">
                        timers[timerCount++]=new Array("left_time_div_'.$contestId.'", '.$leftTime.');
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

$contestId = $contestId?$contestId:1;
include_once 'data_objects/DAOConcurso.php';
$problemsData = DAOConcurso_getProblems($contestId);

include_once 'data_objects/DAOCompetitor.php';
$userProblemData = DAOCompetitor_getContestProblemsForUser($userId,$contestId);
$problemsData=$userProblemData;
print_r($userProblemData);

$firstProblem = $problemsData[0];
$selectedProblemId = $selectedProblemId?$selectedProblemId:$firstProblem['problem_id'];
foreach ($problemsData as $key => $value) {
    if($value['problem_id']==$selectedProblemId){
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
                        if($problemValue['problem_id']==$selectedProblemId){
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
                                <a href="contest_arenadownloadinput.php?id=<?php echo $selectedProblemId ?>">Download Input File</a>
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
                                    <input type="hidden" name="pid" value=<?php echo$selectedProblemId?> >
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