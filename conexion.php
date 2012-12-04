<?php

function conecDb(){
    try {
        $conexion=mysql_connect("localhost","root","root");
        mysql_select_db("huadb_2",$conexion);
        mysql_query("SET NAMES 'utf8'");
        return $conexion;
    }
    catch(Exception $e) {
        echo 'exception caugth: ', $e->getMessenger(), "\n";
    }
}
function firstRow($query){
    $rs = mysql_query($query, conecDb());    
    $data = mysql_fetch_row($rs);
    echo mysql_error();
    return $data;
}
function fetchResultSet($con, $query){
    $rs = mysql_query($query, $con);
    echo mysql_error();
    return $rs;
}
//adding a change
//adding a change
//adding other change
?>