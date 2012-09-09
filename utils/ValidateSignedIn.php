<?php
session_start();
$userId = $_SESSION['userId'];
if($userId==null) {
    include_once 'container.php';
    include_once 'CustomTags.php';
    showPage("Contenido para miembros", false, parrafoError("Please log in to continue"), "");
    die;
}
?>
