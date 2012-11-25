<?php
// $cwd = str_replace('/forum', '', getcwd());
$incl = $_SERVER['DOCUMENT_ROOT'].'/conexion.php';
include_once $incl;

function logSQLError($text, $extraData){
  
  ob_start();
  array_walk(debug_backtrace(),create_function('$a,$b','print "{$a[\'function\']}()(".basename($a[\'file\']).":{$a[\'line\']}); ";'));
  $stackTrace = ob_get_contents();
  ob_end_clean();

  $escapedText = mysql_escape_string($text);
  $escapedExtraData = mysql_escape_string(" stackTrace :".$stackTrace." ".$extraData);

  $insert = "INSERT INTO log (text, extra_text, log_type_id)
  VALUES ('".$escapedText."','".$escapedExtraData."',1)";
  mysql_query($insert, conecDb());
  // echo $insert;
  // var_dump((debug_backtrace();
  
  echo "Shame on us, we crashed X_X. Por favor comuniquese con el Administrador (huahcoding@gmail.com). Te lo agradeceremos enormemente!";
}

function checkForError($query=null){
  if(mysql_error()){
    // echo mysql_error();
    // error_reporting(0);
    // warning_reporting(0);
    logSQLError($query , mysql_error());
    // echo mysql_error();
    die;
  }
}
/*
  returns the first cell from the first row from the result
*/
function getRow($query){
    $rs = mysql_query($query, conecDb());
    
    $data = mysql_fetch_row($rs);
    checkForError($query);
    return $data[0];
}

function getWholeRow($query){
    $rs = mysql_query($query, conecDb());    
    $data = mysql_fetch_array($rs);
    checkForError($query);
    return $data;
}

/*
  Execute INSERT or DELETE
*/
function runQuery($insertSt){
   mysql_query($insertSt, conecDb());
   checkForError($insertSt);
   return true;
}

function runQueryOnHuaHVB($query){
  mysql_query($query, forumConexion());
  checkForError();
}

function getRowsInArray($query){
  $result = mysql_query($query, conecDb());
  checkForError();
    $arr=array();
    $arrIndex=0;
    while($r = mysql_fetch_array($result, MYSQL_ASSOC)){
        $arr[$arrIndex]=$r;
        $arrIndex++;
    }
    return $arr;
}

function getfirstRow($query){
    $rs = mysql_query($query, conecDb());
    checkForError();
    $data = mysql_fetch_row($rs);
    return $data;
}
?>