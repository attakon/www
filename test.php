<?php

include_once 'data_objects/DAOPermissions.php';
$userId =$_SESSION['userId'];
echo $userId;
DAOPermissions_isUserGrantedWithPermission($userId, 'admin_button', 'Y');
?>
