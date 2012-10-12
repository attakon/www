<?php
session_start();
include_once 'utils/ValidateAuthor.php';

include_once 'container.php';
include_once 'CustomTags.php';

$links = array (
		rCLink('admin_myleagues.php','','My Leagues',null),
		// rCLink('admin_createcontest.php',null,'Create New Contest',''),
		rCLink('admin_myproblems.php','','My Problems',null),
		// rCLink('admin_addcontestproblems.php','','Add Problems to Existing Contest',null),
		rCLink('admin_mycontests.php','','My Contests',null)
	);
// $resetLink = rCLink('admin_resetpassword.php','','Reset User Password',null);
// $createContestLink = rCLink('admin_createcontest.php',null,'Create New Contest','');
// $createProblemLink = rCLink('admin_createproblem.php','','Create New Problem',null);
// $addProblems = rCLink('admin_addcontestproblems.php','','Add Problems to Existing Contest',null);

// $content = $resetLink.'<br/>'.
// $createContestLink.'<br/>'.$createProblemLink.'<br/>'.$addProblems;
$content='';
foreach ($links as $key => $value) {
	$content.=$value.'</br>';
}
showPage('Admin Panel', false, parrafoOK($content), '');


?>

