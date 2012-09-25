<?php
session_start();
include_once 'utils/ValidateAdmin.php';

if(isset($_GET['delcontestid'])){
    $contestId =$_GET['delcontestid'];
    include_once 'data_objects/DAOConcurso.php';
    $leagueData = DAOConcurso_getContestData($contestId);
    $contestName = $leagueData['nombre'];
    if($contestName!=null){
        DAOContest_deleteContest($contestId);
        $_SESSION['message']='Contest '.$contestName.' was deleted';
        $_SESSION['message_type']='ok';    
    }
}

$fields = array(
    'id_temporada' => 
        array('type'=>'list',
            'label'=>'Temporada',
            'list'=>array(
                'table'=>'temporada',
                'idField'=>'id_temporada',
                'labelField'=>'nombre',
                'condition'=>"WHERE creator_id ='".$_SESSION['userId']."'")),
    'nombre'=> 
        array('label'=>'Nombre','type'=>'text'),
    'nombre_corto'=>
        array('label'=>'Nombre Corto',
            'type'=>'text'),
    'fecha'=>
        array('label'=>'Fecha de Realizacion',
            'type'=>'datetime',
            'format'=>'yyyy-MM-dd hh:mm:ss',
            'value'=>'2012-12-12 12:12:12'
            ),
    'descripcion'=>array("type"=>'text'),
    'total_time'=>
        array('type'=>'time',
            'label'=>'total_time',
            'format'=>'hh:mm:ss'),
    'left_time'=>array('type'=>'time',
            'label'=>'left_time',
            'format'=>'hh:mm:ss'),
    'creator_id'=>array(
        'type'=>'hidden',
        'value'=>$_SESSION['userId']
        )
);


include_once 'maintenanceForm.php';
$insertContestForm = new RCMaintenanceForm('concurso',$fields,NULL,'Create Contest', 'nombre','style="text-align: left; width:450px"');
$insertContestForm->setOnSuccessRedirectPage('admin_mycontests.php');

$tablesPC="concurso co";
$columnsPC = array(
    array("co.id_concurso",  "",     10, "",
        "type"=>"",
        'td_atr'=>'style="font-size:10px; color:lightgray"'),
    array("co.creator_id",  "", -1, "",
        "type"=>""),
    array("co.nombre",  "", -1, "",
       "type"=>""),
    array("'Contests'",  "Contest", -2, "", 
        "type"=>"replacement", 
        'value' => "<a href='/admin_mycontestproblems.php?id=#{0}'>#{2}</a>"),
    array("'delete'",  "Delete", 80, "", 
        "type"=>"replacement", 
        'value' => "<a href='/admin_mycontests.php?delcontestid=#{0}'>Delete</a>")
);

        // case 'con_res':return "<a href='$path/concurso_results.php?i=$id&tab=2'>$caption</a>";

$conditionPC = "WHERE co.estado = 'REGISTRATION_OPEN' ".
    " AND co.creator_id = ".$_SESSION['userId'].
    " ORDER BY 1 DESC";

include_once 'table2.php';
$manageContestTable = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionPC);
//$manageContestTable->setTitle("Concursos Pasados");

$content = $insertContestForm->getForm().
'<br/>'.$manageContestTable->getTable();

include_once 'container.php';
showPage('My Contests', false, $content , null,'600');

?>
