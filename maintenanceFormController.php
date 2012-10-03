<?php
include_once 'utils/DBUtils.php';
include_once 'CustomTags.php';
include_once 'container.php';

// print_r($_POST);
if(isset($_POST) && isset($_POST['__table'])){
	// $REQ = $_POST;
	if(isset($_POST['__operation']) && $_POST['__operation']=='UPD'){
		$updateStatement = 'UPDATE '.$_POST['__table'].' SET '.' ';
		// print_r($_POST);
		foreach($_POST  as $key => $val){
		    if($key == '__table'){
		        break;
		    }
		    $updateStatement.=$key." = '".$val."',";
		}
		$updateStatement = substr($updateStatement, 0,strlen($updateStatement)-1);
		$updateStatement.=" WHERE ".$_POST['__upd_idfield']."='".$_POST['__upd_idvalue']."'";
		// print_r($updateStatement);
		runQuery($updateStatement);
	}else{
		$insertSt = 'INSERT INTO '.$_POST['__table']." (";
		$values = '';
		foreach($_POST  as $key => $val){
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
	}
	
	$successMessage='';
	if(isset($_POST['__redirectpage'])){
		session_start();
		$successMessage = $_POST['__success_message'];
		$_SESSION['message']=$successMessage;
		$_SESSION['message_type']='ok';
		$redirectPage = $_POST['__redirectpage'];
		header('Location: '.$redirectPage);
	}else{
		showPage($successMessage , false, parrafoOK($successMessage ), null);	
	}
	
}

?>
