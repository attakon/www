<?php
session_start();
include_once 'utils/ValidateAdmin.php';


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


$tablesPC="concurso co";
$columnsPC = array(
    array("co.id_concurso",  "",     -1, ""),
    array("co.creator_id",  "",     -1, ""),
    array("co.nombre",  "Concurso",     -1, "",""),    
    array("'Contests'",  "Contest", 100, "", "replacement", 
        'value' => "<a href='/admin_mycontestproblems.php?id=#{0}'>#{2}</a>"),
    array("'delete'",  "Delete", 80, "", "replacement", 
        'value' => "<a href='/m.php?i=#{0}'>Delete</a>")
);

        // case 'con_res':return "<a href='$path/concurso_results.php?i=$id&tab=2'>$caption</a>";

$conditionPC = "WHERE co.estado = 'REGISTRATION_OPEN' ".
    " AND co.creator_id = ".$_SESSION['userId'].
    " ORDER BY 1 DESC";

include_once 'table2.php';
$manageContestTable = new RCTable(conecDb(),$tablesPC,10,$columnsPC,$conditionPC);
//$manageContestTable->setTitle("Concursos Pasados");

$content = $insertContestForm->getForm().
'<br/>'.$manageContestTable->getTable();

include_once 'container.php';
showPage('Create New Contest', false, $content , null,'600');

?>
