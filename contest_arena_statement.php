<?php
function getHTMLStatement($problemId, $statement, $example_cases){
    
    // $exampleCases = $selectedProblemData['example_cases'];

    $tablesPC="co_problem_testcase ptc, co_problem pr, (SELECT @rownum:=0) r";

    $columnsPC = array(
    array("ptc.testcase_id ",  "",     -1, ""),
    array("ptc.case_input",  "Input",     -2, "","",
        'td_atr'=>'style ="border-width:2px; border-style:ridge; font-family:courier;"'),
    array("ptc.case_output",  "Output",     -2, "","",
        'td_atr'=>'style ="border-width:2px; border-style:ridge; font-family:courier;"'),
    array("ptc.explanation",  "Explanation",     -2, "","",
        'td_atr'=>'style ="border-width:2px; border-style:ridge; font-family:courier;"')
    );
    $conditionPC = "WHERE ptc.problem_id = pr.problem_id ".
        " AND pr.problem_id = '".$problemId."' ".
        " ORDER BY 1 ASC 
            LIMIT ".$example_cases;

    include_once 'table2.php';
    $exampleCasesTable = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionPC);
    $exampleCasesTable->showLineBreaks(true);
    $table = $exampleCasesTable->getTable();
    // print_r($exampleCasesTable->getData());
    //
    $inputToShow = $example_cases."\n";
    $outputToShow = "";
    $counter = 0;
    $n = sizeof($exampleCasesTable->getData());
    $iCols=100;
    $oCols=100;
    foreach ($exampleCasesTable->getData() as $key => $value) {
        $inputToShow.=rtrim($value[1]);
        $outputToShow.=rtrim($value[2]);
        $iCols = max($iCols,strlen($inputToShow)*5);
        $oCols = max($oCols,strlen($outputToShow)*5);
        if(++$counter<$n){
            $inputToShow.="\n";
            $outputToShow.="\n";
        }
    }
    $iRows = substr_count($inputToShow, "\n");
    $oRows = substr_count($outputToShow, "\n");
    
    return $statement.'<br/>
    <script>
        function toggle(me){
            // console.log(me);
            if(me.innerHTML=="Formatted"){
                me.innerHTML="Normal";
                jQuery("#problem_examples_normal").css("display","block");
                jQuery("#problem_examples_formatted").css("display","none");

            }else{
                me.innerHTML="Formatted";
                jQuery("#problem_examples_normal").css("display","none");
                jQuery("#problem_examples_formatted").css("display","block");
            }
        }
    </script>
    <div class="well" style="padding-top:0px;padding-left:10px">

        <div><h4>Example Cases <button id="toggle-io" onclick="toggle(this)" class="btn btn-mini" data-toggle="button">Formatted</button></h4>

    
        </div>
        <div id="problem_examples_normal" style="text-align:left;display:none">
            <div style="display:inline-block">
            Input<br/>
                <textarea id="comment_body" name="comment[body]" style="width:'.$iCols.'px" rows='.($iRows+1).' >'.$inputToShow.'</textarea>
            </div>
            <div style="display:inline-block">
            Output<br/>
                <textarea cols="100" id="comment_body" name="comment[body]" style="width:'.$oCols.'px" rows='.($oRows+1).' >'.$outputToShow.'</textarea>
            </div>
        </div>
        <div id="problem_examples_formatted">'.$table.'</div>
    </div>';
}


?>