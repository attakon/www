<?php
session_start();
include_once 'utils/ValidateAdmin.php';

if(isset($_POST)){
    if(sizeof($_POST)==0){
        init();
    }else{
        call_user_func($_POST['__method_to_invoke'], $_POST);
    }
}else{
    init();
}


function init(){
    $fields = array(
        'name'=> 
            array('label'=>'Problem Name','type'=>'text'),
        'difficulty' => 
            array('label'=>'Difficulty','type'=>'text'),
        // 'parse_type'=> 
        //     array('label'=>'Parse Type','type'=>'hard-list',
        //         'values'=>
        //             array(
        //                 'STATIC-LINE-separated'=>'STATIC-LINE-separated',
        //                 '#CASEMARK-separated'=>'#CASEMARK-separated'
        //                 )
        //             ),
        'input-parse-type'=> 
            array('label'=>'parseTypes','type'=>'select',
                'options'=>
                    array(
                            'STATIC-LINE-separated-input'=>
                                array(
                                    'label'=>'STATIC-LINE-separated',
                                    'attributes'=>'checked="1" onclick="selectProblemParseTypeForCaseInput(\'STATIC-LINE-separated\')"'
                                    ),
                            '#CASEMARK-separated-input'=>
                                array(
                                    'label'=>'#CASEMARK-separated',
                                    'attributes'=>'onclick="selectProblemParseTypeForCaseInput(\'#CASEMARK-separated\')"'
                                    )

                        )
                    ),
        'lines-per-input-case'=> 
            array('label'=>'Lines per Input Case','type'=>'text', 'value'=>'1'),
        'input-casemark'=> 
            array('label'=>'Input Case Mark',
                'type'=>'text', 'value'=>'#CASEMARK',
                'div-atr'=>'style="display:none"'),
        'output-parse-type'=> 
            array('label'=>'parseTypes','type'=>'select',
                'options'=>
                    array(
                            'STATIC-LINE-separated-output'=>
                                array(
                                    'label'=>'STATIC-LINE-separated',
                                    'attributes'=>'checked="1" onclick="selectProblemParseTypeForCaseOutput(\'STATIC-LINE-separated\')"'
                                    ),
                            '#CASEMARK-separated-output'=>
                                array(
                                    'label'=>'#CASEMARK-separated',
                                    'attributes'=>'onclick="selectProblemParseTypeForCaseOutput(\'#CASEMARK-separated\')"'
                                    )
                        )
                    ),
        'lines-per-output-case'=> 
            array('label'=>'Lines per Output Case','type'=>'text','value'=>'1'),
        'output-casemark'=> 
            array('label'=>'Ouput Case Mark','type'=>'text', 'value'=>'#CASEMARK',
                'div-atr'=>'style="display:none"'),
        'case-mark'=> 
            array('label'=>'Case mark','type'=>'text','value'=>'#CASEMARK'),
        'first-line-counter'=> 
            array('label'=>'Is First Line Counter?','type'=>'checkbox', 'checked'=>'true'),    
        'input'=> 
            array('label'=>'Input','type'=>'file'),
        'output'=> 
            array('label'=>'Output','type'=>'file'),

        );
    include_once 'maintenanceForm.php';
    $problemInsertForm = new RCMaintenanceForm('problem',$fields,'previewProblem','Next', 'name',
        'style="text-align: left; width:400px"', 'enctype="multipart/form-data"');


    // See problem list 
    $tablesPC="co_problem problem, usuario us";
    $columnsPC = array(
    array("problem.problem_id",  "",     -1, ""),
    array("problem.creator_id",  "",     -1, ""),
    array("problem.name",  "Problem Name",     160, "",""),
    array("'delete'",  "Delete", 80, "", "replacement", 
        'value' => "<a href='/admin_addcontestproblem_selectproblem.php?i=#{0}'>Delete</a>"),
    array("'view'",  "View I/O", 80, "", "replacement", 
        'value' => "<a href='/admin_myproblems_io.php?pid=#{0}'>View I/O</a>")

    );
    $conditionPC = "WHERE problem.creator_id = us.id_usuario ".
    "ORDER BY 1 DESC";

    include_once 'table2.php';
    $problemList = new RCTable(conecDb(),$tablesPC,10,$columnsPC,$conditionPC);

    $content = $problemInsertForm->getForm().'<br/>'.$problemList->getTable();

    include_once 'container.php';
    showPage('Create New Contest', false, $content, null,'370');    
}

function previewProblem($_PAR){
    print_r($_PAR);
    $inputTempName = $_FILES['input']['tmp_name'];
    $outputTempName = $_FILES['output']['tmp_name'];
    
    if(empty($inputTempName) || empty($outputTempName)){
        showPage("", false, parrafoError("file cannot be empty"),"");
        die;
    }
    // $inputTable = getTabularView($inputTempName);
    // $outputTable = getTabularView($outputTempName);

    $linesPerInputCase = $_PAR['lines-per-input-case'];
    $linesPerOutputCase = $_PAR['lines-per-output-case'];
    $isFirstLineCounter = isset($_PAR['first-line-counter'])?$_PAR['first-line-counter']:'';
    // $table = getIOTabularView($inputTempName,$outputTempName,$linesPerInputCase, $linesPerOutputCase,$isFirstLineCounter);
    $problemName = $_PAR['name'];
    $io = getIOArrayFromUploadedFile($inputTempName,$outputTempName,$linesPerInputCase, $linesPerOutputCase,$isFirstLineCounter);
    $table = getIOTabularView($io);
    // print_r($io);
    
    $result = '<label>Problem Name: </label>'.$problemName.'<br/>';
    $result.='<form method="POST" action="admin_createproblem_process.php">
    <input type="submit" value="Save Problem"/>';
    $result.= $table;

    $_SESSION['io']=$io;
    $_SESSION['problemName']=$problemName;

    showPage('Problem Preview', false, $result, null);
}

// function sonIguales($tmpName, $idProblem) {
//     $correctOut = firstRow("select output_file from problema where id_problema ='".$idProblem."'");
//     $correct = explode("\n", $correctOut[0]);
//     $file_handle = fopen($tmpName, "r");
//     $i = 0;
//     foreach($correct as $correctLine) {
//         $correctLine = trim($correctLine);
//         if(strcmp($correctLine,"")!=0) {
//             $i++;
//             if(!feof($file_handle)) {
//                 $userLine = trim(str_replace("\n", "",fgets($file_handle)));
//                 if(strcmp($correctLine, $userLine)!=0) {
//                     fclose($file_handle);
//                     return array(false,"Error en Linea ".$i." tu salida[".$userLine."] esperado[".$correctLine."]");
//                 }
//             }else {
//                 fclose($file_handle);
//                 return array(false,"Respuesta incorrecta tu salida[] esperado[".$userLine."]");
//             }
//         }
//     }
//     fclose($file_handle);
//     return array(true,true);
// }

function getIOArrayFromUploadedFile($inputName, $outputName, $linesPerInputCase, $linesPerOutputCase, $isFirstLineCounter){
    
    $inputFile_handle = fopen($inputName, "r");
    $outputFile_handle = fopen($outputName, "r");
    $expectedTotal='';
    if($isFirstLineCounter=='on'){
        $expectedTotal = fgets($inputFile_handle);
    }
    $inputCounter=0;
    $singleInputCase="";
    $totalRead=0;
    $io = array();
    while($inputLine = fgets($inputFile_handle)){
        $inputLine = trim($inputLine);
        $singleInputCase.=$inputLine."\n";
        if(++$inputCounter==$linesPerInputCase){
            $outputCounter=0;
            $singleOutputCase="";
            while($outputLine=fgets($outputFile_handle)){
                $outputLine = trim($outputLine);
                $singleOutputCase.=$outputLine;
                if($outputCounter++<$linesPerOutputCase){
                    
                    $outputCounter=0;

                    $io[$totalRead]=array('i'=>$singleInputCase,'o'=>$singleOutputCase);

                    $totalRead++;
                    $inputCounter=0;
                    $singleInputCase="";
                    break;
                }
                
            }
        }
    }
    return $io;
}

function getIOTabularView($io){
    
    ob_start();
    $totalRead=sizeof($io);
    foreach ($io as $key => $value) {
        ?>
            <tr>
                <td style ="border-width:2px; border-style:ridge;">
                    <?php echo str_replace("\n", "</br>", $value['i']) ?></td>
                <td style ="border-width:2px; border-style:ridge;">
                    <?php echo str_replace("\n", "</br>", $value['o']) ?></td>
            </tr>
        <?php
    }
    $result = '<table border="1" style ="border-width:2px; border-style:ridge;">';
    $result.= '<tr colspan="1">
                <td>read cases:'.$totalRead.'</td>
            </tr>';

    $result = $result.ob_get_contents().'
    </table>';
    ob_end_clean();
    return $result;
}

function getTabularView($tmpName){
    echo $tmpName;
    ob_start();
    $file_handle = fopen($tmpName, "r");
    $lines = explode("\n",$file_handle);
    ?>
    <table>
    <?php
    while($line = fgets($file_handle)){
        $line = trim($line);
        ?>
            <tr>
                <td><?php echo $line ?></td>
            </tr>
        <?php
    }
    ?>
    </table>
    <?php
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}
?>