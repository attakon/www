<?php
include_once 'utils/DBUtils.php';
include_once 'CustomTags.php';
include_once 'container.php';
$REQ = $_POST;
print_r($_POST);
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
$successMessage = $REQ[$REQ['__success_message']].' created succesfully';
showPage($successMessage , false, parrafoOK($successMessage ), null);

?>
