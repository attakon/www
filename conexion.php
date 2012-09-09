<?php
//    function __autoload($className){
//        echo $className;
//        require_once($className.'.php');
//    }

function conecDb(){
    try {
//          $conexion=mysql_connect("mysql3.000webhost.com","a9731928_huahdb","CAMAron23");
//        mysql_select_db("a9731928_huahdb",$conexion);
        $conexion=mysql_connect("localhost","root","root");
        mysql_select_db("huahdb",$conexion);
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
function forumConexion(){
    try {
//        $forumCon=mysql_connect("mysql3.000webhost.com","a9731928_huahvb","CAMAron23");
//        mysql_select_db("a9731928_huahvb",$forumCon);
        $forumCon=mysql_connect("localhost","root","root");
        mysql_select_db("huahvb",$forumCon);
        //mysql_query("SET NAMES 'utf8'");
        return $forumCon;
    }
    catch(Exception $e) {
        echo 'exception caugth: ', $e->getMessenger(), "\n";
    }
}
?>