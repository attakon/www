<?php
include_once('container.php');
include_once 'CustomTags.php';


    if(false) { // disable registrations
        
        $msg = parrafoOK("Lo sentimos, el registro de usuarios está deshabilitado por el momento");
        showPage("Registro de Nuevo Miembro",false, $msg, "");
    }else {

        include_once('register_form.php');

        $userName = '';
        $firstName = '';
        $lastName = '';
        $email = '';
        $school = '';
        $message = '';
        if(isset($_GET['user'])){
            $userName = $_GET['user'];
        }
        if(isset($_GET['first_name'])){
            $firstName = $_GET['first_name'];
        }
        if(isset($_GET['last_name'])){
            $lastName = $_GET['last_name'];
        }
        if(isset($_GET['email'])){
            $email = $_GET['email'];
        }
        if(isset($_GET['school'])){
            $school = $_GET['school'];
        }
        if(isset($_GET['message'])){
            $message = $_GET['message'];
        }
        $x = getForm($userName, $firstName, $lastName, $email, $school, $message);

        
        showPage("Registro de Nuevo Usuario",false, $x, "");

    }
?>