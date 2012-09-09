<?php
function parrafoOK($msg) {
    return '<p class="ok"> '.$msg.'</p>';
}

function parrafoError($msg) {
    return '<p class="error"> '.$msg.'</p>';
}

/**
 *
 * @param <type> $path put "." for root
 * @param <type> $id
 * @param <type> $username
 * @return <type>
 */
function userLink($path,$id,$username) {
    return '<a class="userLink" href="'.$path.'/user.php?u='.$id.'">'.$username.'</a>';
}
/**
 *
 * @param <type> $path only "." (Quotes for clarity) to specify the root
 * @param <type> $id
 * @param <type> $caption
 * @param <type> $type
 * @return <type>
 */
function rCLink($path, $id=null,$caption, $type=null) {
    switch($type) {
        case 'user':return "<a class='userLink' href=$path/$type.php?u=$id>$caption</a>";
        case 'concurso':return "<a href='$path/$type.php?idt=$id&show=det'>$caption</a>";
        case 'date':return "<a href='$path/$type.php?idt=$id&show=det'>$caption</a>";
        case 'con_res':return "<a href='$path/concurso_results.php?i=$id&tab=2'>$caption</a>";
        case 'con_pra':return "<a href='$path/concurso_results.php?i=$id&tab=1'>$caption</a>";
        case 'thread':return "<a href='$path/forum/showthread.php?t=$id'>$caption</a>";
        case 'lastpost':return "<a href='$path/forum/showthread.php?goto=newpost&t=$id'>$caption</a>";
        case 'enunciado':return "<a target='_blank' href='$path/files/$id.pdf'><img src='$path/images/PDF_icon.gif'></img></a>";
        case 'forum':return "<a href='$id'>discutir</a>";
        default:
                return "[<a href='$path'>".$caption."</a>]";
            
    }
//    if($type=='user') {
//        return "<a class='userLink' href=$path/$type.php?u=$id>$caption</a>";
//    }else if($type=='concurso') {
//            return "<a class='userLink' href='$path/$type.php?idt=$id&show=det'>$caption</a>";
//        }else if($type=='date') {
//                return "<a class='userLink' href='$path/$type.php?idt=$id&show=det'>$caption</a>";
//            }
}
function getSpanishDate($d, $month, $year) {
    $day = date("D", mktime(0, 0, 0, $month, $d, $year));
    $dia = "";
    switch($day) {
        case 'Sun':$dia = 'Domingo';break;
        case 'Mon':$dia = 'Lunes';break;
        case 'Tue':$dia = 'Martes';break;
        case 'Wed':$dia='Miercoles';break;
        case 'Thu':$dia = 'Jueves';break;
        case 'Fri':$dia = 'Viernes';break;
        case 'Sat':$dia = 'S&aacute;bado';break;
    };
    $mes = '';
    $mes = array('Enero','Febrero','Marzo','Abril','Mayo','Junio',
        'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
    return $dia." ".$d." de ".$mes[$month-1]." del ".$year;
}
function getSpanishDateShort($d, $month, $year) {
    $day = date("D", mktime(0, 0, 0, $month, $d, $year));
    $dia = "";
    switch($day) {
        case 'Sun':$dia = 'Dom';break;
        case 'Mon':$dia = 'Lun';break;
        case 'Tue':$dia = 'Mar';break;
        case 'Wed':$dia = 'Mie';break;
        case 'Thu':$dia = 'Jue';break;
        case 'Fri':$dia = 'Vie';break;
        case 'Sat':$dia = 'S&aacute;b';break;
    };
    $mes = '';
    $mes = array('Ene','Feb','Mar','Ab','May','Jun',
        'Jul','Ago','Sep','Oct','Nov','Dic');
    return $mes[$month-1].", ".$dia." ".$d;
}


                            ?>
