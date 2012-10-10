<?php
include_once ("container.php");
include_once ("conexion.php");
include_once 'CustomTags.php';
include_once 'data_objects/DAOUser.php';

$searchedUserId = "";
if(isset($_GET['u'])){
    $searchedUserId = $_GET['u'];
}else if(isset($_GET['uname'])){
    $userName = $_GET['uname'];
    echo $userName;
    $row = DAOUser_getUserByName($userName);
    $searchedUserId = $row['id_usuario'];
}
//$idUser = 7;

$hasCompetitorProfile = firstRow("SELECT puntos, id_ranking FROM competidor WHERE id_usuario = '$searchedUserId'");
$bodyContent = getUserProfile($searchedUserId,$hasCompetitorProfile[0],$hasCompetitorProfile[1] );
if($hasCompetitorProfile){
    $bodyContent .= "<br>".getCompetitionProfile($searchedUserId).'<br/>';
    //$bodyContent .= "<br>".getCompetitionProfile($searchedUserId).'<br/>'.getUserContestsHistory($searchedUserId);
}

session_start();
if(isset($_SESSION['userId']) && $searchedUserId==$_SESSION['userId']
    && isset($_SESSION['fbUserName'])){
    $bodyContent .="<p align=center>Si tienes una cuenta existente en HuaHCoding, <a href='link_account.php'>conectala ahora</a></p>";    
}

// begin raul add - enabling contest history for users that haven't competed
$bodyContent.=getUserContestsHistory($searchedUserId);
// end raul add
showPage("Perfil de Miembro", false, $bodyContent, "");

function getUserProfile($idUser, $puntos, $id_ranking){

    $q = "SELECT us.username,
            CONCAT(YEAR(us.member_since),'-', MONTH(us.member_since),
            '-', DAY(us.member_since))
            FROM  usuario us
            WHERE
            us.id_usuario= '".$idUser."'";    
    $rs=mysql_query($q,conecDb()) or die ($q);
    $data = mysql_fetch_row($rs);
    $username = $data[0];
    $member_since = $data[1];
    // $school = $data[2];

    $returnedValue =
    '<table align = "center" class="user" border="1" >
    <tr>
        <td class="userName"  colspan="3">'.$username.'</td>
    </tr>
    <tr>
        <td class="userLabel" >Rank:</td>';
    if($id_ranking){
        $returnedValue .=
        "<td class='imgRank'><img src=./images/ranking/$id_ranking.gif> </td>
        <td class='ptsRank'>$puntos pts </td>";
    }else{
        $returnedValue.="<td class='userField' colspan=2>not ranked</td>";
    }
    $returnedValue.='
    </tr>
    <tr>
        <td class="userLabel" >Miembro desde:</td>
        <td class="userField" colspan=2>'.$member_since.'</td>
    </tr>
</table>';
    
    // removing school for now. appereance on profile to be determined.
    // <tr>
    //     <td class="userLabel" >Escuela:</td>
    //     <td class="userField" colspan=2>'.$school.'</td>
    // </tr>

    return $returnedValue;
}

function getCompetitionProfile($idUser){
    $query = "SELECT puntos, penalty_time, position,
                position_school, default_lang, competitions_count
            FROM competidor where id_usuario = '$idUser'";
    $data = firstRow($query);

    $nContestants = firstRow("SELECT COUNT(*) FROM competidor;");
    $id_escuela = firstRow("SELECT id_escuela FROM usuario WHERE id_usuario = '$idUser'");
    $nContestantsSchool = firstRow("SELECT COUNT(*) FROM competidor c, usuario us
        WHERE us.id_escuela = '$id_escuela[0]' AND ".
        "c.id_usuario = us.id_usuario"
    );

    $puntos = $data[0];
    $penalty_time = $data[1];
    $position = $data[2];
    $position_school = $data[3];
    $default_lang = $data[4];
    $comp_count = $data[5];
    $lastCompetition = firstRow("SELECT con.nombre, DATE(con.fecha) ".
        "FROM campaign cmp, concurso con, usuario us ".
        "WHERE cmp.id_usuario = us.id_usuario AND ".
        "cmp.id_concurso = con.id_concurso AND ".
        "us.id_usuario = '$idUser'".
        "ORDER BY con.fecha DESC");
    if($position<0){
        $position = $position_school = "no posicionado";
    }else{
        $position = "$position de $nContestants[0]";
        $position_school = "$position_school de $nContestantsSchool[0]";
    }
    $returnedValue =
    "<table class='user' align = 'center' border='1' width='395' height='120'>
    <tr>
        <td class='userLabel'  >Penalizaci&oacute;n total:</td>
        <td class='userField'>$penalty_time</td>
    </tr>
    <tr>
        <td class='userLabel'  >Posici&oacute;n:</td>
        <td class='userField'>$position</td>
    </tr>
    <tr>
        <td class='userLabel'>Posici&oacute;n en Escuela:</td>
        <td class='userField'>$position_school </td>

    </tr>

    <!-- <tr>
        <td class='userLabel'>Lenguaje por defecto:</td>
        <td class='userField'>$default_lang</td>
    </tr>!-->

    <tr>
        <td class='userLabel'  >Participaciones:</td>
        <td class='userField'>$comp_count</td>
    </tr>
    <tr>
        <td class='userLabel'  >Ultima Competici&oacute;n:</td>
        <td class='userField' >$lastCompetition[0] $lastCompetition[1]</td>
    </tr>
    </table>";
    return $returnedValue;
}
function getUserContestsHistory($userId){
    include_once 'data_objects/DAOUser.php';
    $arr = DAOUser_getUserCampaignHistory2($userId);
    // print_r($arr);
    $map = array();
    $contestData = array();
    foreach($arr as $key => $val){
        $map[$val['contest_name']][] = $val;
        $contestData[$val['contest_name']]=$val['contest_id'];
    }
    $returnedValue = parrafoOK('Problemas en Azul fueron resueltos en modo Pr&aacute;ctica, pero sin haber visto ningun c&oacute;digo para ese problema');
    $returnedValue .= "<table class='user' align = 'center' border='1' width='395' height='120'>";
    $n = sizeof($map);
    foreach($map as $key => $problems){
        $returnedValue.="<tr>
            <td><a href='./contest_arena_scoreboard.php?id=".$contestData[$key]."'>".$key."
                </a>
            </td>";
        foreach($problems as $key => $val){
            $cssClass = '';
            
            $problemName = $val['name'];
            
            switch($val['status']){
                case 1:
                    $cssClass = 'seen';
                    break;
                case 2:
                    $cssClass = 'solved_in_practice_cheated';
                    break;
                case 3:
                    $cssClass = 'solved_in_practice';
                    break;
                case 4:
                    $cssClass = 'solved';
                    break;
            }
            if($val['status']=='4'){
                $defaultOutput = '<td class="'.$cssClass.'">
                        <a class="det" href="./viewcode.php?cpg='.$val['cpg_id'].'&p='.$val['problem_id'].'">
                         '.$problemName.'</a>
                         </td>';
            }else{
                $defaultOutput = '<td class="'.$cssClass.'">'.$problemName.'</td>';
            }
            $returnedValue.=$defaultOutput;
                    
                    
        }
        $returnedValue.="</tr>";
            
    }
    $returnedValue.="</table>";
//    print_r($arr);
//    print_r(arr)
//    print_r($map);
//    echo '<BR/>';
//    print_r($arr);
    return $returnedValue;
}
?>
<!--height="23" width="162"-->