<?php
session_start();
include_once 'utils/ValidateSignedIn.php';

include_once 'container.php';
include_once 'CustomTags.php';

$links = array (
		rCLink('user_password_reset.php','','Reset My Password',null)
		// rCLink('admin_user_events.php','','See All User Events',null),
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
showPage('Your Settings', false, parrafoOK($content), '');


?>

