<?php
    include_once './container.php';
    include_once './concursoForm.php';
    include_once 'table.php';
    include_once 'data_objects/DAOConcurso.php';
    // ./concurso.php?idt=8&show=det
    $show = $_GET['show'];
    $contestId = $_GET['idt'];

    if($show=='det'){
        $details = getConcursoDetalle($contestId);

        $contestData = DAOConcurso_getContestData($contestId);

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
            con.id_concurso='".$contestId."' AND
            c.id_usuario = us.id_usuario
            ORDER BY cmp.id_campaign";
        $table = new RCTable(conecDb(),$tables,10,$columns,$condition);
        $body = "";
        print_r($contestData);
        if($contestData['estado']=="REGISTRATION_OPEN"){
            $body .= parrafoOK("Inscripciones Abiertas");
        }else if ($contestData['estado']=="REGISTRATION_CLOSED"){
            $body .= parrafoError("Inscripciones Cerradas<br>".
            "Se recomienda llegar de una a media hora antes de la hora para probar su PC<br>");
        }else if ($contestData['estado']=="FINALIZED"){
            $linkToResults = "<a href='concurso_results.php?i=".$contestId."&tab=2'>Ver Resultados</a>";
            $body .= parrafoError("Concurso Finalizado ".$linkToResults);
        }
        $body.=$table->getTable();

        $details.=$body;

        showPage($contestData['nombre']." - Detalles",false, $details, "");
    }else if($show=='results'){

    }
?>