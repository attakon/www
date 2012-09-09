<?php
$userId = $_SESSION['userId'];
if($userId==null) {
    showPage("Contenido para miembros", false, parrafoOK("Debe iniciar sesi&oacute;n para acceder"), "");
    die;
}
?>
