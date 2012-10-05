<?php
session_start();
include_once 'utils/ValidateAdmin.php';

if(isset($_POST['language_id']) || isset($_POST['pid'])|| isset($_POST['content'])){
    $languageId=$_POST['language_id'];
    $problemId=$_POST['pid'];
    $content=stripslashes($_POST['content']);
    include_once 'data_objects/DAOProblem.php';
    $problemStatement = DAOProblem_getProblemStatementForLanguage($problemId,$languageId);
    if(!$problemStatement){
        DAOProblem_insertProblemStatement($problemId,$languageId,$content);
        $_SESSION['message']='statement created';
    }else{
        $_SESSION['message']='there is already a statement in that language';
        $_SESSION['message_type']='error';
    }
    include_once 'container.php';
    redirectToLastVisitedPage();
    // print_r($_POST);
}   

?>