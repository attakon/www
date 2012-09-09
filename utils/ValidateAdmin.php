<?php
//session_start();
include_once 'container.php';
include_once 'CustomTags.php';

if(!isset($_SESSION['userId'])){
    showPage('Admin Panel', false, parrafoError('Not logged in'), null);
    die;
}else{
    include_once 'data_objects/DAOPermissions.php';
    if(!DAOPermissions_isUserGrantedWithPermission($_SESSION['userId'], 'admin_button', 'Y')){
        showPage('Admin Panel', false, parrafoError('Not authorized'), '');
        die;
    }
}
?>
