<?php
session_start();
include_once 'utils/ValidateAdmin.php';

$problemId = $_GET['pid'];
include_once 'data_objects/DAOProblem.php';
$problemData = DAOProblem_getProblemData($problemId);
$problemaName = $problemData['name'];

$tablesPC="co_problem_testcase ptc, co_problem pr , (SELECT @rownum:=0) r";
$columnsPC = array(
	array("@rownum:=@rownum+1 'order'",  "N",     15, ""),
    array("ptc.testcase_id ",  "",     -1, ""),
    array("ptc.case_input",  "Input",     -2, "","",
    	'td_atr'=>'style ="border-width:2px; border-style:ridge; font-family:courier;"'),
    array("ptc.case_output",  "Output",     -2, "","",
    	'td_atr'=>'style ="border-width:2px; border-style:ridge; font-family:courier;"')
);

$conditionPC = "WHERE ptc.problem_id = pr.problem_id ".
	" AND pr.problem_id = '".$problemId."' ".
    " ORDER BY 2 ASC ";

	include_once 'table2.php';
	$manageContestTable = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionPC);
	$manageContestTable->showLineBreaks(true);
	// $manageContestTable->setTableAtr('style ="border-width:2px; border-style:ridge;');
	include_once 'container.php';
    showPage('Problem\'s Input and Output for '.$problemaName, false, $manageContestTable->getTable(), null,'370');
?>