<?php
include ('container.php');
include ('conexion.php');
include_once ("CustomTags.php");
include_once 'table.php';
// ./concurso_registeredUsers.php?id=8
$concursoId = $_GET['id'];
//$concursoId = 1;
$concursoName = firstRow("SELECT nombre, estado FROM concurso WHERE id_concurso='".$concursoId."'");

$columns = array(
    array("@rownum:=@rownum+1 'rank'",  "N",     15, ""),
    array("us.id_usuario",  "username",     -1, ""),
    array("c.id_ranking",   "",             0,  "","img images/ranking gif"),
    array("us.username",    "Inscrito",  150,   "","linked 1 user"),
    array("cmp.checked_in",  "Confirmado",  30, "class='checked_in'","img images png")
);

$tables = "campaign cmp, usuario us, concurso con, competidor c, (SELECT @rownum:=0) r";
$condition = "WHERE us.id_usuario = cmp.id_usuario AND
    cmp.id_concurso = con.id_concurso AND
    c.id_temporada=con.id_temporada AND
    con.id_concurso='".$concursoId."' AND
    c.id_usuario = us.id_usuario
    ORDER BY cmp.id_campaign";
$table = new RCTable(conecDb(),$tables,10,$columns,$condition);
$body = "";
if($concursoName[1]=="REGISTRATION_OPEN"){
    $body .= parrafoOK("Inscripciones Abiertas");
}else if ($concursoName[1]=="REGISTRATION_CLOSED"){
    $body .= parrafoError("Inscripciones Cerradas<br>".
    "Se recomienda llegar de una a media hora antes de la hora para probar su PC<br>");
}else if ($concursoName[1]=="FINALIZED"){
    $body .= parrafoError("Concurso Finalizado");
}
$body.=$table->getTable();
showPage("Registrados ", false, $body, "");

//showPage($concursoName[0]." - Inscritos", false, $teamsRegistrados, "");

function userRegistrados($concursoId){
    $registeredUsersQry = "SELECT con.id_usuario, con.username, cpg.checked_in
    FROM campaign cpg, usuario con, concurso cso
    WHERE con.id_usuario = cpg.id_usuario AND
    cpg.id_concurso = cso.id_concurso AND
    cso.id_concurso='".$concursoId."'
    ORDER BY cpg.id_campaign";
    $userRS = mysql_query($registeredUsersQry,conecDb()) or die ($registeredUsersQry);

    $cl = 'class= "tr_Par"';
    $cl2 = 'class= "tr_Impar"';

    $clLC ='class = "tr_LeftCorner"';
    $clRC ='class = "tr_RightCorner"';
    $clHeaderTN = 'class = "tr_headerTN"';
    $clHeaderTS = 'class = "tr_headerTS"';

    $clLeft ='class ="tr_Left"';
    $clRight ='class ="tr_Right"';
    $clTN='class = "tr_TeamName"';
    $clTS='class = "tr_TeamStatus"';

    $table = '<table align ="center" border ="0" style="border-collapse: collapse" '.$cl.'>
            <tr>
            <td '.$clLC.'></td>
            <td '.$clHeaderTN.' width ="50">N</td>
            <td '.$clHeaderTN.' width ="200">Inscrito</td>
            <td '.$clHeaderTN.' width ="100">Estado</td>
            <td class = "right"></td>
            </tr>';
    $i=1;
    while($userData = mysql_fetch_row($userRS)){
        $table .='<tr '.(($i%2==0)?$cl:$cl2).'>
            <td '.$clLeft.'></td>
            <td >'.$i++.'</td>
            <td '.$clTN.'>'.userLink('.',$userData[0], $userData[1]).'</td>
            <td '.$clTS.'>'.(($userData[2]==false)?"Pendiente":"Confirmado").'</td>
            <td '.$clRight.'></td>
            </tr>';
    }
    $table.='<tr style="border-bottom-width: 1px"></tr></table>';
    return $table;
}
?>
