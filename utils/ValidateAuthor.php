<?php
//session_start();
	include_once 'container.php';
	include_once 'CustomTags.php';
	include_once 'utils/ValidateSignedIn.php';

	include_once 'data_objects/DAOPermissions.php';
    if(!DAOPermissions_isUserGrantedWithPermission($_SESSION['userId'], 'author_feature', 'Y')){
        showPage('Author', false, parrafoError('Not authorized'), '');
        die;
    }

    function isOwner($creatorId){
    	return $_SESSION['userId']==$creatorId;
    }
?>
