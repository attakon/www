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
    'id_temporada' => 
        array('type'=>'list',
            'label'=>'Temporada',
            'list'=>array(
                'table'=>'temporada',
                'idField'=>'id_temporada',
                'labelField'=>'nombre',
                'condition'=>'')),
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
    // 'locacion'=>array("type"=>'text'),
    // 'inscripcion'=>array("type"=>'text'),
    // 'premio'=>array("type"=>'text'),
    'descripcion'=>array("type"=>'text'),
    // 'is_rated'=>array('type'=>'checkbox','label'=>'is_rated','checked'=>'1'),
    // 'url_forum'=>array("type"=>'text'),
    'total_time'=>
        array('type'=>'time',
            'label'=>'total_time',
            'format'=>'hh:mm:ss'),
    'left_time'=>array('type'=>'time',
            'label'=>'left_time',
            'format'=>'hh:mm:ss'),
    // 'id_usuario'=>
    //     array('type'=>'list',
    //         'label'=>'Writer',
    //         'list'=>array(
    //             'table'=>'usuario',
    //             'idField'=>'id_usuario',
    //             'labelField'=>'username',
    //             'condition'=>''
    //         )),
    'creator_id'=>array(
        'type'=>'hidden',
        'value'=>$_SESSION['userId']
        )
);
print_r($_SESSION['userId']);

include_once 'maintenanceForm.php';
$tablePC = new RCMaintenanceForm('concurso',$fields,NULL,'Create Contest', 'nombre','style="width:400px"');


include_once 'container.php';
showPage('Create New Contest', false, $tablePC->getForm(), null,'370')

?>