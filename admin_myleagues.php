<?php
session_start();
include_once 'utils/ValidateAuthor.php';

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
        'description'=> 
            array('label'=>'Description','type'=>'text'),
        'creator_id'=>array(
            'type'=>'hidden',
            'value'=>$_SESSION['userId']
            )
    );

    include_once 'maintenanceForm.php';
    $tablePC = new RCMaintenanceForm('co_league',$fields,NULL,'Create League', 'League Created','style="width:400px"');
    $tablePC->setOnSuccessRedirectPage('admin_myleagues.php');
    // See problem list 
    $tablesPC="co_league co_league";
    $columnsPC = array(
    array("co_league.league_id",  "",     -1, ""),
    array("co_league.nombre",  "League Name",     160, "",""),
    array("'delete'",  "Delete", 80, "", 
        "type"=>"replacement", 
        'value' => "<a href='/admin_myleagues.php?delleagueid=#{0}'>Delete</a>"),
    array("'see_contests'",  "Contests", 80, "", 
        "type"=>"replacement", 
        'value' => "<a href='/admin_myleagues.php?i=#{0}'>See Contests</a>"),
    );
    $conditionPC = "WHERE co_league.creator_id = '".$_SESSION['userId']."'".
    " ORDER BY 1 DESC";

    include_once 'table2.php';
    $problemList = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionPC);
    $content = $tablePC->getForm()."<br/>".$problemList->getTable();
    include_once 'container.php';
    showPage('My Leagues', false, $content, null,'370')

?>