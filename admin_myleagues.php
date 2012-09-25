<?php
session_start();
include_once 'utils/ValidateAdmin.php';

if(isset($_GET['delleagueid'])){
    $leagueId =$_GET['delleagueid'];
    include_once 'data_objects/DAOLeague.php';
    $leagueData = DAOLeague_getLeague($leagueId);
    $leagueName = $leagueData['nombre'];
    if($leagueName!=null){
        DAOLeague_delLeague($leagueId);
        $_SESSION['message']='League '.$leagueName.' was deleted';
        $_SESSION['message_type']='ok';    
    }
}


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
    $tablePC->setOnSuccessRedirectPage('admin_myleagues.php');
    // See problem list 
    $tablesPC="temporada temporada";
    $columnsPC = array(
    array("temporada.id_temporada",  "",     -1, ""),
    array("temporada.nombre",  "League Name",     160, "",""),
    array("'delete'",  "Delete", 80, "", "replacement", 
        'value' => "<a href='/admin_myleagues.php?delleagueid=#{0}'>Delete</a>"),
    array("'see_contests'",  "Contests", 80, "", "replacement", 
        'value' => "<a href='/admin_myleagues.php?i=#{0}'>See Contests</a>"),
    );
    $conditionPC = "WHERE temporada.creator_id = '".$_SESSION['userId']."'".
    " ORDER BY 1 DESC";

    include_once 'table2.php';
    $problemList = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionPC);
    $content = $tablePC->getForm()."<br/>".$problemList->getTable();
    include_once 'container.php';
    showPage('My Leagues', false, $content, null,'370')

?>