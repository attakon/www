<?php
include_once 'utils/DBUtils.php';
include_once 'CustomTags.php';
include_once 'container.php';

// print_r($_POST);
if(isset($_POST) && isset($_POST['__table'])){
	$REQ = $_POST;
	$insertSt = 'INSERT INTO '.$REQ['__table']." (";
	$values = '';
	foreach($REQ  as $key => $val){
	    if($key == '__table'){
	        break;
	    }
	    $insertSt.=$key.",";
	    $values.="'".$val."',";
	}
	$insertSt = substr($insertSt, 0, strlen($insertSt)-1);
	$values = substr($values, 0, strlen($values)-1);
	$insertSt .=") VALUES (".$values.")";
	runQuery($insertSt);
	$successMessage = $REQ[$REQ['__success_message']].' has been created';

	if(isset($REQ['__redirectpage'])){
		session_start();
		$_SESSION['message']=$REQ[$REQ['__success_message']].' has been created';
		$_SESSION['message_type']='ok';
		$redirectPage = $REQ['__redirectpage'];
		header('Location: '.$redirectPage);
	}else{
		showPage($successMessage , false, parrafoOK($successMessage ), null);	
	}
	
}

?>
