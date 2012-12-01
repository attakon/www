<?php
session_start();
include_once 'utils/ValidateAuthor.php';


//BEGIN validate ownership
if(isset($_GET['id'])){
    $contestId = $_GET['id'];
    include_once 'data_objects/DAOContest.php';
    $contestData = DAOContest_getContestData($contestId);
    if(!isOwner($contestData['creator_id'])){
        include_once 'container.php';
        include_once 'CustomTags.php';
        showPage('This is not your page',false,parrafoError("not a valid page"));
    }
}else{
    include_once 'container.php';
    include_once 'CustomTags.php';
    showPage('This is not your page',false,parrafoError("not a valid page"));
    die;
}
//END validate ownership

if(isset($_GET['id']) &&  isset($_GET['delproblemid'])){
	$contestId = $_GET['id'];
    if(DAOContest_getContestPhase($contestId)=='FINISHED'){
        $_SESSION['message']='You cannot delete problems because this contest has already finished.';
        $_SESSION['message_type']='error';
        redirectToLastVisitedPage();
    }
	$problemId = $_GET['delproblemid'];
    include_once 'data_objects/DAOProblem.php';
    $problemData = DAOProblem_getProblemData($problemId);

    DAOContest_removeProblemFromContest($contestId,$problemId);
    $_SESSION['message']='problem '.$problemData['name'].'was successfully removed';

    include_once 'container.php';
    redirectToLastVisitedPage();
}
else if(isset($_GET['id']) &&  isset($_GET['deregisterid'])){
    $contestId = $_GET['id'];
        if(DAOContest_getContestPhase($contestId)=='FINISHED'){
        $_SESSION['message']='Contest has finished. If you want to disqualify a contestant, send an email to raul@huahcoding.com';
        $_SESSION['message_type']='error';
        redirectToLastVisitedPage();
    }

    $deregisterId = $_GET['deregisterid'];
    include_once 'data_objects/DAOUser.php';
    $userData = DAOUser_getUserById($deregisterId);


    include_once 'data_objects/DAOCampaign.php';
    DAOCampaign_deregegisterUser($contestId, $deregisterId);
    $_SESSION['message']=$userData['username'].' was successfully de-registered';
    
    include_once 'container.php';
    redirectToLastVisitedPage();
}
else if(isset($_GET['id']) &&  isset($_GET['disinviteid'])){
    $disinviteid = $_GET['disinviteid'];
    include_once 'data_objects/DAOUser.php';
    $userData = DAOUser_getUserById($disinviteid);

    $contestId = $_GET['id'];
    include_once 'data_objects/DAOContest.php';
    DAOContest_uninviteUser($contestId, $disinviteid);
    $_SESSION['message']=$userData['username'].' was successfully disinvited';

    // include_once 'container.php';
    
    include_once 'container.php';
    redirectToLastVisitedPage();
    // redirectToLastVisitedPage();
}
else if(isset($_GET['id'])){
	$contestId = $_GET['id'];

	include_once 'data_objects/DAOContest.php';
	$contestData = DAOContest_getContestData($contestId);
	$contestName = $contestData['nombre'];

	//Contest Problem List 
	$tablesPC="co_problem problem, co_contest_problems left join languages on(co_contest_problems.problem_language_id=languages.language_id)";
    $columnsPC = array(
    array("problem.problem_id",  "",     -1, ""),
    array("problem.creator_id",  "",     -1, ""),
    array("problem.name",  "Problems for ".$contestName,     160, "",""),
    array("languages.name",  "Statement language",     -2, "",""),
    array("co_contest_problems.points",  "points",     -2, "",""),
    array("'view'",  "View I/O", 80, "", 
        "type"=>"replacement", 
        'value' => "<a href='./admin_myproblems_io.php?pid=#{0}'>View I/O</a>"),
    array("'delete'",  "Remove From Contest", 80, "", 
        "type"=>"replacement", 
        'value' => "<a href='./admin_mycontestproblems.php?id=".$contestId."&delproblemid=#{0}'>Remove</a>")
    );
    $conditionPC = "WHERE problem.problem_id = co_contest_problems.problem_id ".
    " AND co_contest_problems.contest_id='".$contestId."'".
    " ORDER BY co_contest_problems.order ASC";

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
        'value' => "<a href='./admin_myproblems_io.php?pid=#{0}'>View I/O</a>"),
    array("'view'",  "Add Problem", -2, "", 
        "type"=>"replacement", 
        'value' => "<a href='./admin_mycontestproblems_add.php?cid=".$contestId."&pid=#{0}'>Add Problem..</a>"),
    // array("'add'",  "Add", -2, "", 
    //     "type"=>"replacement", 
    //     'value' => "<a href='./admin_mycontestproblems.php?id=".$contestId."&addproblemid=#{0}'>Add Problem to ".$contestName."</a>")
    );
    $conditionPC = "WHERE problem.creator_id = '".$_SESSION['userId']."'
        ORDER BY 1 DESC";

    include_once 'table2.php';
    $problemList = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionPC);

    $availableProblemsTable = $problemList->getTable();


    //Registered Users
    include_once 'data_objects/DAOContest.php';
    $contestProblems = DAOContest_getProblems($contestId);

    $leagueId = $contestData['league_id'];
    $columns = array(
        // array("@rownum:=@rownum+1 'rank'",  "N",     15, ""),
        array("us.id_usuario",  "username",     -1, ""),
        array("us.username",  "username",     -1, ""),
        // array("c.id_ranking",   "",             0,  "","img images/ranking gif"),
        array("us.username",    "Registered Users",  150,   "",
            "type"=>"replacement",
            'value'=>'<a class="userLink" href="./user.php?u=#{1}" >#{2}</a>'),
        // array("count(us.id_usuario)",   "campaign_detalle", 2,  ""),
        array("count(cmpd.problem_id)",  "problems assigned",     100, ""),
        array("if(count(cmpd.problem_id)=".sizeof($contestProblems).",'OK','NOT READY')",  "READY ",100, ""),
        array("'xxx'",  "problems assigned",     100, "",
            "type"=>"replacement",
            'value'=>'<a href="./admin_mycontestproblems.php?id='.$contestId.'&deregisterid=#{0}">de-register</a>')
    );

    $tables = "campaign cmp LEFT JOIN campaigndetalle cmpd ON cmp.id_campaign = cmpd.id_campaign 
    JOIN concurso con ON cmp.contest_id = con.contest_id 
    JOIN usuario us ON us.id_usuario = cmp.id_usuario ";
    
    $condition = "WHERE 
        con.contest_id = '".$contestId."'
        GROUP BY us.id_usuario
        ORDER BY cmp.id_campaign";
        // ORDER BY cmp.id_campaign";
        // AND c.id_usuario = us.id_usuario

    //BEGIN Changing from temporada to league
    include_once 'table2.php';
    $registeredUserTable = new RCTable(conecDb(),$tables,$columns,$condition);

    // button
    // $sealLink = '<a href="./admin_mycontestproblems.php?id='.$contestId.'&action=setproblems">
    //     [Create Campaigns Details]
    //     </a>';
    // $publishLink = '<a href="./admin_mycontestproblems.php?id='.$contestId.'&action=publish">
    //     [Publish Contest]
    //     </a>';

    $invitesHTML = '';
    if($contestData['is_invitational']){
        $fields = array(
        'user_id'=>
        array('type'=>'list',
            'label'=>'Username',
            'list'=>array(
                'table'=>'usuario',
                'idField'=>'id_usuario',
                'labelField'=>'username',
                'condition'=>''
            )),
        'contest_id'=>array('type'=>'hidden', 'value'=>$contestId)
        );
        include_once 'maintenanceForm.php';
        $invitesForm = new RCMaintenanceForm('co_contest_invites',$fields,null,'Reset',null);
        $invitesForm->setButtonName('invite user');
        $invitesForm->setSuccessMessage('successfully invited');
        $invitesForm->setOnSuccessRedirectPage("admin_mycontestproblems.php?id=".$contestId);
        $invitesHTML = $invitesForm->getForm();

        //

        $columns = array(
            array("ci.user_id",  "",     -1, ""),
            array("us.username",  "username",     -2, ""),
            
            array("'xxx'",  "disinvite",     100, "",
                "type"=>"replacement",
                'value'=>'<a href="./admin_mycontestproblems.php?id='.$contestId.'&disinviteid=#{0}">dis-invite</a>'),
             array("'send_email'",  "send email",     100, "",
                "type"=>"replacement",
                'value'=>'<a href="./admin_mycontestproblems.php?id='.$contestId.'&emailid=#{0}">send invitation email</a>')
        );

        $tables = "co_contest_invites ci join usuario us on(us.id_usuario = ci.user_id)";
    // AND c.league_id='".$leagueId."'
            // AND cmp.id_campaign = cmpd.id_campaign
        $condition = "WHERE 
            ci.contest_id = '".$contestId."'";
            // GROUP BY us.id_usuario";
            // ORDER BY cmp.id_campaign";
            // AND c.id_usuario = us.id_usuario

        //END Changing from temporada to league
        include_once 'table2.php';
        $invitesTable = new RCTable(conecDb(),$tables,$columns,$condition);
        $invitesHTML.=$invitesTable->getTable();
    }
        

	$content = $contestProblemTable->getTable().
        '<br/>'
        // .$sealLink.'<br/>'
        // .$publishLink.'<br/>'.'<br/>'
        .$availableProblemsTable.'<br/>'
        .$registeredUserTable->getTable().'<br/>'
        .$invitesHTML;

    include_once 'container.php';
    showPage($contestName.'\'s problems', false, $content, null,'370');  
}
