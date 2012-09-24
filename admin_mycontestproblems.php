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
    array("'view'",  "View I/O", 80, "", "replacement", 
        'value' => "<a href='/admin_myproblems_io.php?pid=#{0}'>View I/O</a>"),
    array("'delete'",  "Remove From Contest", 80, "", "replacement", 
        'value' => "<a href='/admin_mycontestproblems.php?id=".$contestId."&delproblemid=#{0}'>Remove</a>")
    );
    $conditionPC = "WHERE problem.problem_id = contest_problems.problem_id ".
    "ORDER BY 1 DESC";

    include_once 'table2.php';
    $contestProblemList = new RCTable(conecDb(),$tablesPC,10,$columnsPC,$conditionPC);

    

	//Available problems

	$tablesPC="co_problem problem";
    $columnsPC = array(
    array("problem.problem_id",  "",     -1, ""),
    array("problem.creator_id",  "",     -1, ""),
    array("problem.name",  "Available Problems",     160, "",""),
    array("'view'",  "View I/O", 80, "", "replacement", 
        'value' => "<a href='/admin_myproblems_io.php?pid=#{0}'>View I/O</a>"),
    array("'delete'",  "Add", -2, "", "replacement", 
        'value' => "<a href='/admin_mycontestproblems.php?id=".$contestId."&addproblemid=#{0}'>Add Problem to ".$contestName."</a>")
    );
    $conditionPC = "ORDER BY 1 DESC";

    include_once 'table2.php';
    $problemList = new RCTable(conecDb(),$tablesPC,10,$columnsPC,$conditionPC);

    $availableProblemsTable = $problemList->getTable();


	$content = $contestProblemList->getTable().'<br/>'.$availableProblemsTable;

    include_once 'container.php';
    showPage($contestName.'\'s problems', false, $content, null,'370');  
}

?>