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

    include_once 'data_objects/DAOContest.php';
    $contestData = DAOContest_getContestData($campaignData['contest_id']);
    $contestPhase = DAOContest_getContestPhase($campaignData['contest_id']);

    if($contestPhase=='IN_PROGRESS'){
        include_once 'data_objects/DAOUser.php';
        $isUserRegistered = DAOUser_isUserRegisteredInContest($campaignData['id_usuario'],$campaignData['contest_id']);
        if(!$isUserRegistered){
            include_once 'container.php';
            include_once 'CustomTags.php';
            showPage("X.X", false, parrafoError('user not allowed'), "");
            die;
        }
        $sessionUserId = $_SESSION['userId'];

        //Check if signed in user is actually downloading its campaign Input file
        if($sessionUserId!=$campaignData['id_usuario']){
            include_once 'container.php';
            include_once 'CustomTags.php';
            showPage("X.X", false, parrafoError('user not allowed'), "");
            die;
        }
        $pendingSubmission = DAOCampaign_getPendingSubmission($campaignData['contest_id'], $campaignId, $problemId);
        if(!$pendingSubmission){
            include_once 'data_objects/DAOCampaign.php';
            $contestId=$campaignData['contest_id'];
            
            // Randomly generate a seed to shuffle
            $seed = rand(1, 10);

            $submissionId = DAOCampaign_startSubmission($contestId, $campaignId, $problemId, $seed);
            

            include_once 'data_objects/DAOUserEvents.php';
            include_once 'GLOBALS.php';
            DAOUserEvents_logEventById($sessionUserId, DOWNLOAD_CONTEST_INPUT, 'downloaded problem '.$problemId);
            
            processDownload($problemId,$seed);
        }else{
            include_once 'container.php';
            include_once 'CustomTags.php';
            showPage("X.X", false, parrafoError('You have already downloaded this input file, and we are waiting for your response.'), "");
            die;
        }
        

    }else if ($contestPhase=='NOT_STARTED'){//invalid
        include_once 'container.php';
        showPage("X.X", false, parrafoError('not allowed to be here'), "");
    }
}else if(isset($_GET['pid']) && isset($_GET['cid'])){
    $problemId =  $_GET['pid'];
    $contestId =  $_GET['cid'];
    include_once 'data_objects/DAOContest.php';
    $contestPhase = DAOContest_getContestPhase($contestId);

    if($contestPhase!='FINISHED'){
        die;
    }
    processDownload($problemId);
}else if(isset($_GET['pid'])){
    $problemId = $_GET['pid'];
    include_once 'data_objects/DAOProblem.php';
    $problemData = DAOProblem_getProblemData($problemId);
    $signedUserId = $_SESSION['userId'];
    $creatorId = $problemData['creator_id'];
    if($creatorId==$signedUserId){
        // $testSeed = 2;
        processDownload($problemId);
    }else{
        die;
    }
}

function SEOshuffle(&$items, $seed=false) {
  $original = md5(serialize($items));
  mt_srand(crc32(($seed) ? $seed : $items[0]));
  for ($i = count($items) - 1; $i > 0; $i--){
    $j = @mt_rand(0, $i);
    list($items[$i], $items[$j]) = array($items[$j], $items[$i]);
  }
  if ($original == md5(serialize($items))) {
    list($items[count($items) - 1], $items[0]) = array($items[0], $items[count($items) - 1]);
  }
}
function processDownload($problemId, $seed=null){
        include_once 'data_objects/DAOProblem.php';
        $problemData = DAOProblem_getProblemData($problemId);
        
        $problemName = $problemData['name'];

        include_once 'data_objects/DAOProblem.php';
        //[TODO] Chance of improvement. Bring only Input
        $problemIO = DAOProblem_getProblemIO($problemId);

        // print_r($problemIO);
        if($seed)
            SEOshuffle($problemIO, $seed);
        // print_r($problemIO);
        // $outputContent = '';
        $inputSize = sizeof($problemIO);
        $inputContent = $inputSize.chr(13);
        foreach ($problemIO as $key => $value) {
            $inputContent .= rtrim($value['case_input']);
            if(--$inputSize>0)
                $inputContent.="\n";
            // $outputContent .= $value['case_output'];
        }
        
        // $_SESSION['io_seed_'.$problemId]=$seed;
            // $_SESSION['inputContent_'.$problemId]=$inputContent;
            // $_SESSION['outputContent_'.$problemId]=$outputContent;
        // }else{
        //     $inputContent = $_SESSION['inputContent_'.$problemId];
        //     $outputContent = $_SESSION['outputContent_'.$problemId];
        // }

        // $result = @mysql_query($sql, conecDb());
        // $data = @mysql_result($result, 0, "input_file");
        // $name = str_replace(" ","",@mysql_result($result, 0, "nombre"));

        header("Content-type: text/in");
        header("Content-Disposition: attachment; filename=".$problemName."_in.txt");
        header("Content-Description: Input File");
        echo $inputContent;
}
?>