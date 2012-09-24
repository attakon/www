<?php
// $id=$_GET['id'];
//$id=2;
if(isset($_GET['id'])){
	$problemId = $_GET['id'];
    include_once 'data_objects/DAOProblem.php';
    $problemData = DAOProblem_getProblemData($problemId);
    $problemName = $problemData['name'];

	include_once "conexion.php";
    // if($_SESSION)

    session_start();
    include 'utils/ValidateSignedIn.php';

    if(!isset($_SESSION['inputContent_'.$problemId]) || !isset($_SESSION['outputContent_'.$problemId])){
        include_once 'data_objects/DAOProblem.php';
        $problemIO = DAOProblem_getProblemIO($problemId);
        // print_r($problemIO);

        $inputContent = '';
        $outputContent = '';
        foreach ($problemIO as $key => $value) {
            $inputContent .= $value['case_input'];
            $outputContent .= $value['case_output'];
        }
        $_SESSION['inputContent_'.$problemId]=$inputContent;
        $_SESSION['outputContent_'.$problemId]=$outputContent;
    }else{
        $inputContent = $_SESSION['inputContent_'.$problemId];
        $outputContent = $_SESSION['outputContent_'.$problemId];
    }
    // $result = @mysql_query($sql, conecDb());
    // $data = @mysql_result($result, 0, "input_file");
    // $name = str_replace(" ","",@mysql_result($result, 0, "nombre"));

    header("Content-type: text/in");
    header("Content-Disposition: attachment; filename=".$problemName."_in.txt");
    header("Content-Description: PHP Generated Data");
    echo $inputContent;
}
?>