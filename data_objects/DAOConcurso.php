<?php
include_once ("utils/DBUtils.php");

function DAOConcurso_getContestData($concursoId){
	$query = "SELECT con.nombre, con.estado FROM concurso con WHERE con.id_concurso = '".$concursoId."'";
   	$contestName = getWholeRow($query);
   	return $contestName;
}

function DAOConcurso_getTemporadaId($concursoId){ 
   $query = "SELECT con.id_temporada FROM concurso con WHERE con.id_concurso = '".$concursoId."'";
   $temporadaId = getRow($query);
   return $temporadaId;
}

function DAOConcurso_getActiveContests(){
	$query = "select id_concurso, nombre from concurso 
    	WHERE estado in('REGISTRATION_OPEN')
        ORDER BY 1 ASC LIMIT 5";

    return getRowsInArray($query);
}
?>
