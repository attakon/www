<?php
//session_start();
if(!isset($_SESSION['userId']) || $_SESSION['userId']==null) {
    include_once 'container.php';
    include_once 'CustomTags.php';
    showPage("Contenido para miembros", false, parrafoError("Please sign in to continue"), "");
    die;
}
?>
