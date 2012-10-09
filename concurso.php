<?php
    include_once './container.php';
    include_once './concursoForm.php';
    include_once 'table2.php';
    include_once 'data_objects/DAOConcurso.php';
    // ./concurso.php?idt=8&show=det
    $show = $_GET['show'];
    $contestId = $_GET['idt'];

    if($show=='det'){
        $details = getConcursoDetalleHTML($contestId);

        $contestData = DAOConcurso_getContestData($contestId);
        $leagueId = DAOConcurso_getLeagueId($contestId);
        $columns = array(
            // array("@rownum:=@rownum+1 'rank'",  "N",     15, ""),
            array("us.id_usuario",  "username",     -1, ""),
            // array("us.username",  "username",     -1, ""),
            // array("c.id_ranking",   "",             0,  "","img images/ranking gif"),
            array("us.username",    "Registered Users",  150,   "",
                "type"=>"replacement",
                'value'=>'<a class="userLink" href="./user.php?u=#{0}" >#{1}</a>'),
            // array("cmp.checked_in",  "Confirmado",  30, "class='checked_in'","img images png")
        );

        $tables = "campaign cmp, concurso con, usuario us , competidor c ";

        $userTableCondition = "WHERE us.id_usuario = cmp.id_usuario AND
            cmp.id_concurso = con.id_concurso 
            AND c.id_usuario = us.id_usuario
            AND c.id_temporada='".$leagueId."'
            AND con.id_concurso = '".$contestId."' 
            ORDER BY cmp.id_campaign";
            // AND c.id_usuario = us.id_usuario

        //END Changing from temporada to league
        $userTable = new RCTable(conecDb(),$tables,$columns,$userTableCondition);
        
        // print_r($contestData);
        include_once 'data_objects/DAOConcurso.php';
        $contestPhase = DAOConcurso_getContestPhase($contestId);
        // $body = $contestPhase;
            
        // if($contestData['estado']=="REGISTRATION_OPEN"){
        //     $body .= parrafoOK("Registration is open");
        // }else if ($contestData['estado']=="REGISTRATION_CLOSED"){
        //     $body .= parrafoError("Inscripciones Cerradas<br>");
        // }else if ($contestData['estado']=="FINALIZED"){
        //     $linkToResults = "<a href='concurso_results.php?i=".$contestId."&tab=2'>Ver Resultados</a>";
        //     $body .= parrafoError("Concurso Finalizado ".$linkToResults);
        // }
        
        $body=$userTable->getTable();

        $details.=$body;

        showPage($contestData['nombre']." - Details",false, $details, "");
    }else if($show=='results'){

    }
?>