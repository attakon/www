<?php
session_start();
include_once 'utils/ValidateAdmin.php';

//include_once $_SERVER['DOCUMENT_ROOT'].'/huahcoding.com/'.'data_objects/DAOPermissions.php';
//echo $_SERVER['DOCUMENT_ROOT'].'/huahcoding.com';
//$userId =$_SESSION['userId'];
//test 2
//$userId =1;
//echo $userId;
//echo DAOPermissions_isUserGrantedWithPermission($userId, 'admin_button', 'Y');
//
//test 3
//echo '<!DOCTYPE html>
//    <html>';
//echo '<html>';

$fields = array(
    'nombre'=> 
        array('label'=>'League Name','type'=>'text'),
    'creator_id'=>array(
        'type'=>'hidden',
        'value'=>$_SESSION['userId']
        )
);

include_once 'maintenanceForm.php';
$tablePC = new RCMaintenanceForm('temporada',$fields,NULL,'Create League', 'nombre','style="width:400px"');

    // See problem list 
    $tablesPC="temporada temporada";
    $columnsPC = array(
    array("temporada.id_temporada",  "",     -1, ""),
    array("temporada.nombre",  "League Name",     160, "",""),
    array("'delete'",  "Delete", 80, "", "replacement", 
        'value' => "<a href='/admin_addcontestproblem_selectproblem.php?i=#{0}'>Delete</a>"),
    array("'see_contests'",  "Contests", 80, "", "replacement", 
        'value' => "<a href='/admin_addcontestproblem_selectproblem.php?i=#{0}'>See Contests</a>"),
    );
    $conditionPC = "WHERE temporada.creator_id = '".$_SESSION['userId']."'".
    " ORDER BY 1 DESC";

    include_once 'table2.php';
    $problemList = new RCTable(conecDb(),$tablesPC,10,$columnsPC,$conditionPC);
    $content = $tablePC->getForm()."<br/>".$problemList->getTable();
    include_once 'container.php';
    showPage('My Leagues', false, $content, null,'370')

?>