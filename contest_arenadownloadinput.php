<?php
session_start();
include_once 'utils/ValidateSignedIn.php';
// $id=$_GET['id'];
//$id=2;
// echo "string";
if(isset($_GET['pid']) && isset($_GET['cmpid'])){
	$problemId = $_GET['pid'];
    $campaignId = $_GET['cmpid'];

    include_once 'data_objects/DAOCampaign.php';
    $campaignData = DAOCampaign_getCampaignData($campaignId);
    // print_r($campaignData);

    include_once 'data_objects/DAOConcurso.php';
    $contestData = DAOConcurso_getContestData($campaignData['id_concurso']);
    $contestPhase = DAOConcurso_getContestPhase($campaignData['id_concurso']);

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
        $isSubmissionPending = DAOCampaign_isSubmissionPending($campaignData['id_concurso'], $campaignId, $problemId);
        if(!$isSubmissionPending){
            processDownload($campaignData['id_concurso'],$problemId, $campaignId);    
        }else{
            include_once 'container.php';
            include_once 'CustomTags.php';
            showPage("X.X", false, parrafoError('You have already download'), "");
            die;
        }
        

    }else if ($contestPhase=='FINISHED'){//practice mode
        include_once 'container.php';
        // redirectToLastVisitedPage();
        showPage("X.X", false, parrafoError('go to practice mode'), "");
    }else if ($contestPhase=='NOT_STARTED'){//invalid
        include_once 'container.php';
        showPage("X.X", false, parrafoError('not allowed to be here'), "");
    }
}
function processDownload($contestId, $problemId, $campaignId){
        include_once 'data_objects/DAOProblem.php';
        $problemData = DAOProblem_getProblemData($problemId);
        $problemName = $problemData['name'];
        // print_r($problemData);
        include_once 'data_objects/DAOCampaign.php';
        $submissionId = DAOCampaign_startSubmission($contestId, $campaignId, $problemId);
        // echo $submissionId;

        // include_once 'data_objects/DAOProblem.php';
        // $problemIO = DAOProblem_getProblemIO($problemId);
        // $inputContent = '';
        // $outputContent = '';
        // foreach ($problemIO as $key => $value) {
        //     $inputContent .= $value['case_input'];
        //     $outputContent .= $value['case_output'];
        // }
        if(!isset($_SESSION['inputContent_'.$problemId]) || !isset($_SESSION['outputContent_'.$problemId])){

            // print_r($problemIO);

            include_once 'data_objects/DAOProblem.php';
            $problemIO = DAOProblem_getProblemIO($problemId);
            $inputContent = '';
            $outputContent = '';
            foreach ($problemIO as $key => $value) {
                $inputContent .= $value['case_input'];
                $outputContent .= $value['case_output'];
            }
            $_SESSION['inputContent_'.$problemId]=$inputContent;
            $_SESSION['outputContent_'.$problemId]=$outputContent;
        }else{
            $inputContent = $_SESSION['inputContent_'.$problemId];
            $outputContent = $_SESSION['outputContent_'.$problemId];
        }

        // $result = @mysql_query($sql, conecDb());
        // $data = @mysql_result($result, 0, "input_file");
        // $name = str_replace(" ","",@mysql_result($result, 0, "nombre"));

        header("Content-type: text/in");
        header("Content-Disposition: attachment; filename=".$problemName."_in.txt");
        header("Content-Description: PHP Generated Data");
        echo $inputContent;
}
?>