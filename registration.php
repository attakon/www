<?php


    include_once('container.php');

    if(false) { // disable registrations
        include_once 'CustomTags.php';
        $msg = parrafoOK("Lo sentimos, el registro de usuarios está deshabilitado por el momento");
        showPage("Registro de Nuevo Miembro",false, $msg, "");
    }else {

        include_once('registrationForm.php');

        $user = '';
        $firstName = '';
        if(isset($_GET['user'])){
            $user = $_GET['user'];
        }
        if(isset($_GET['first_name'])){
            $user = $_GET['first_name'];
        }
        $x = getForm2($user, $firstName,
            $_GET['last_name'], $_GET['email'], $_GET['school'], $_GET['message']);
        showPage("Registro de Nuevo Usuario",false, $x, "");

    }
    //
?>
