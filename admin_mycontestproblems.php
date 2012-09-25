<?php
session_start();
include_once 'utils/ValidateAdmin.php';

if(isset($_GET['id']) &&  isset($_GET['addproblemid'])){
	$contestId = $_GET['id'];
	$problemId = $_GET['addproblemid'];
	include_once 'data_objects/DAOConcurso.php';
	DAOConcurso_addProblemToContest($contestId,$problemId);
}
if(isset($_GET['id']) &&  isset($_GET['delproblemid'])){
	$contestId = $_GET['id'];
	$problemId = $_GET['delproblemid'];
	include_once 'data_objects/DAOConcurso.php';
	DAOConcurso_removeProblemFromContest($contestId,$problemId);
}
if(isset($_GET['id']) &&  isset($_GET['action'])){
    if($_GET['action']=="setproblems"){

        $contestId = $_GET['id'];
        include_once 'data_objects/DAOConcurso.php';
        $contestProblems = DAOConcurso_getProblems($contestId);


        include_once 'data_objects/DAOCampaign.php';
        $campaigns = DAOCampaign_getUserCampaigns($contestId);
        $message = '';
        foreach ($campaigns as $key => $campaignValue) {
            // $campaignValue['id_usuario'];
            $campaignDetailToInsert = DAOCampaign_getCampaignsNotCreatedForUserInContest($campaignValue['id_usuario'],$contestId);
            $message .=''.sizeof($campaignDetailToInsert).' created for '.$campaignValue['username']."</br>";
            foreach ($campaignDetailToInsert as $key => $problemsToInsertValue) {
                DAOCampaign_createCampaignDetail($campaignValue['id_campaign'],$problemsToInsertValue['problem_id']);
            }
        }
        $_SESSION['message']=$message;
    }
}
if(isset($_GET['id']) &&  isset($_GET['deregisterid'])){
    $userId = $_GET['deregisterid'];
    include_once 'data_objects/DAOUser.php';
    $userData = DAOUser_getUserById($userId);

    $contestId = $_GET['id'];
    include_once 'data_objects/DAOCampaign.php';
    DAOCampaign_deregegisterUser($userId,$contestId);
    $_SESSION['message']=$userData['username'].' has just been de-registered';
}

if(isset($_GET['id'])){
	$contestId = $_GET['id'];

	include_once 'data_objects/DAOConcurso.php';
	$contestData = DAOConcurso_getContestData($contestId);
	$contestName = $contestData['nombre'];
	//Contest's Problem List 

	$tablesPC="co_problem problem, co_contest_problems contest_problems";
    $columnsPC = array(
    array("problem.problem_id",  "",     -1, ""),
    array("problem.creator_id",  "",     -1, ""),
    array("problem.name",  "Problems for ".$contestName,     160, "",""),
    array("'view'",  "View I/O", 80, "", 
        "type"=>"replacement", 
        'value' => "<a href='/admin_myproblems_io.php?pid=#{0}'>View I/O</a>"),
    array("'delete'",  "Remove From Contest", 80, "", 
        "type"=>"replacement", 
        'value' => "<a href='/admin_mycontestproblems.php?id=".$contestId."&delproblemid=#{0}'>Remove</a>")
    );
    $conditionPC = "WHERE problem.problem_id = contest_problems.problem_id ".
    "ORDER BY 1 DESC";

    include_once 'table2.php';
    $contestProblemTable = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionPC);

    

	//Available problems

	$tablesPC="co_problem problem";
    $columnsPC = array(
    array("problem.problem_id",  "",     -1, ""),
    array("problem.creator_id",  "",     -1, ""),
    array("problem.name",  "Available Problems",     160, "",""),
    array("'view'",  "View I/O", 80, "", 
        "type"=>"replacement", 
        'value' => "<a href='/admin_myproblems_io.php?pid=#{0}'>View I/O</a>"),
    array("'delete'",  "Add", -2, "", 
        "type"=>"replacement", 
        'value' => "<a href='/admin_mycontestproblems.php?id=".$contestId."&addproblemid=#{0}'>Add Problem to ".$contestName."</a>")
    );
    $conditionPC = "ORDER BY 1 DESC";

    include_once 'table2.php';
    $problemList = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionPC);

    $availableProblemsTable = $problemList->getTable();


    //Registered Users
    include_once 'data_objects/DAOConcurso.php';
    $contestProblems = DAOConcurso_getProblems($contestId);

    $leagueId = $contestData['id_temporada'];
    $columns = array(
        // array("@rownum:=@rownum+1 'rank'",  "N",     15, ""),
        array("us.id_usuario",  "username",     -1, ""),
        array("us.username",  "username",     -1, ""),
        // array("c.id_ranking",   "",             0,  "","img images/ranking gif"),
        array("us.username",    "Inscrito",  150,   "",
            "type"=>"replacement",
            'value'=>'<a class="userLink" href="/user.php?u=#{1}" >#{2}</a>'),
        // array("count(us.id_usuario)",   "campaign_detalle", 2,  ""),
        array("count(cmpd.id_problema)",  "problems assigned",     100, ""),
        array("if(count(cmpd.id_problema)=".sizeof($contestProblems).",'OK','NOT READY')",  "READY ",100, ""),
        array("'xxx'",  "problems assigned",     100, "",
            "type"=>"replacement",
            'value'=>'<a href="/admin_mycontestproblems.php?id='.$contestId.'&deregisterid=#{0}">de-register</a>')
    );

    $tables = "campaign cmp LEFT JOIN campaigndetalle cmpd ON cmp.id_campaign = cmpd.id_campaign 
    JOIN concurso con ON cmp.id_concurso = con.id_concurso 
    JOIN usuario us ON us.id_usuario = cmp.id_usuario";
// AND c.id_temporada='".$leagueId."'
        // AND cmp.id_campaign = cmpd.id_campaign
    $condition = "WHERE 
        con.id_concurso = '".$contestId."'
        GROUP BY us.id_usuario";
        // ORDER BY cmp.id_campaign";
        // AND c.id_usuario = us.id_usuario

    //END Changing from temporada to league
    include_once 'table2.php';
    $registeredUserTable = new RCTable(conecDb(),$tables,$columns,$condition);

    // button
    $button = '<a href="/admin_mycontestproblems.php?id='.$contestId.'&action=setproblems">
        Set Problems (you will not be able to add more problems)
        </a>';
	$content = $contestProblemTable->getTable().'<br/>'.$availableProblemsTable.'<br/>'.$button.'<br/>'.$registeredUserTable->getTable();



    include_once 'container.php';
    showPage($contestName.'\'s problems', false, $content, null,'370');  
}

?>