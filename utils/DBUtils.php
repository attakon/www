<?php
$cwd = str_replace('/forum', '', getcwd());
$incl = $cwd.'/conexion.php';
include_once $incl;

/*
  returns the first cell from the first row from the result
*/
function getRow($query){
    $rs = mysql_query($query, conecDb());    
    $data = mysql_fetch_row($rs);
    if(mysql_error()){
        echo mysql_error().' '.$query;
        die;
    }
    return $data[0];
}

function getWholeRow($query){
    $rs = mysql_query($query, conecDb());    
    $data = mysql_fetch_array($rs);
    if(mysql_error()){
        echo mysql_error().' '.$query;
        die;
    }
    return $data;
}

/*
  Execute INSERT or DELETE
*/
function runQuery($insertSt){
   mysql_query($insertSt, conecDb());
   if(mysql_error()){
        echo mysql_error().' '.$insertSt;
        die;
   }
   return true;
}
function runQueryOnHuaHVB($query){
   mysql_query($query, forumConexion());
   if(mysql_error()){
        echo mysql_error().' '.$query;
        die;
   }
}
function getRowsInArray($query){
    $result = mysql_query($query, conecDb());
    if(mysql_error()){
        echo mysql_error().' '.$query;
        die;
   }
    $arr=array();
    $arrIndex=0;
    while($r = mysql_fetch_array($result, MYSQL_ASSOC)){
        $arr[$arrIndex]=$r;
        $arrIndex++;
    }
    return $arr;
}
?>
