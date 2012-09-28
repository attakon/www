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
    if(isset($_GET['remprobleid'])){
        $problemId =$_GET['remprobleid'];
        include_once 'data_objects/DAOProblem.php';
        DAOProblem_deleteProblem($problemId);
    }
    $fields = array(
        'name'=> 
            array('label'=>'Problem Name','type'=>'text'),
        'difficulty' => 
            array('label'=>'Difficulty','type'=>'text'),
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
        'input-lines-per-case'=> 
            array('label'=>'Lines per Input Case','type'=>'text', 'value'=>'1'),
        'input-casemark'=> 
            array('label'=>'Input Case Mark',
                'type'=>'text', 'value'=>'/^\d+ \d+$/',
                'div-atr'=>'style="display:none"'),
        'input-include-match'=>
            array('label'=>'Include Match',
                'type'=>'checkbox',
                'checked' =>'true',
                'div-atr'=>'style="display:none"'
                ),
        'input-initial-skip-lines'=> 
            array('label'=>'Initial Lines to Skip',
                'type'=>'text', 'value'=>'1',),
        'input-file'=> 
            array('label'=>'Input File','type'=>'file'),
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
        'output-lines-per-case'=> 
            array('label'=>'Lines per Output Case','type'=>'text','value'=>'1'),
        'output-casemark'=> 
            array('label'=>'Ouput Case Mark','type'=>'text', 'value'=>'#CASEMARK',
                'div-atr'=>'style="display:none"'),
        'output-file'=> 
            array('label'=>'Output File','type'=>'file')
        );
    include_once 'maintenanceForm.php';
    $problemInsertForm = new RCMaintenanceForm('problem',$fields,'previewProblem','Next', 'name',
        'style="text-align: left; width:400px"', 'enctype="multipart/form-data"');


    // See problem table 
    $tablesPC="co_problem problem";
    $columnsPC = array(
    array("problem.problem_id",  "",     -1, ""),
    array("problem.creator_id",  "",     -1, ""),
    array("problem.name",  "Problem Name",     160, "",""),
    array("'delete'",  "Delete", 80, "", 
        "type"=>"replacement", 
        'value' => "<a href='/admin_myproblems.php?remprobleid=#{0}'>Delete</a>"),
    array("'view'",  "View I/O", 80, "", 
        "type"=>"replacement", 
        'value' => "<a href='/admin_myproblems_io.php?pid=#{0}'>View I/O</a>")
    );
    $conditionPC = "WHERE problem.creator_id = '".$_SESSION['userId']."'
     ORDER BY 1 DESC";

    include_once 'table2.php';
    $problemList = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionPC);

    $content = $problemInsertForm->getForm().'<br/>'.$problemList->getTable();

    include_once 'container.php';
    showPage('My Problems', false, $content, null,'370');
}

function previewProblem($_PAR){

    $inputTempName = $_FILES['input-file']['tmp_name'];
    $outputTempName = $_FILES['output-file']['tmp_name'];
    
    if(empty($inputTempName) || empty($outputTempName)){
        $_SESSION['message']='file cannot be empty';
        $_SESSION['message_type']='error';
        // header("location",'');
        header('Location: admin_myproblems.php');
        // showPage("", false, parrafoError("file cannot be empty"),"");
        die;
    }

    // $table = getIOTabularView($inputTempName,$outputTempName,$linesPerInputCase, $linesPerOutputCase,$isFirstLineCounter);
    $problemName = $_PAR['name'];
    $inputList;
    switch($_PAR['input-parse-type']){
        case 'STATIC-LINE-separated-input':
            $linesPerCaseInput = $_PAR['input-lines-per-case'];
            $initialSkipLines=$_PAR['input-initial-skip-lines'];
            $inputList = getListSeparatedByLines($inputTempName, $linesPerCaseInput,$initialSkipLines);
            break;
        case '#CASEMARK-separated-input':
            $caseMarkForInput = $_PAR['input-casemark'];
            $initialSkipLines=$_PAR['input-initial-skip-lines'];
            $inputList = getListSeparetedByMark($inputTempName, $caseMarkForInput,$initialSkipLines);
            break;
    }
    $outputList;
    switch($_PAR['output-parse-type']){
        case 'STATIC-LINE-separated-output':
            $linesPerCaseOutput = $_PAR['output-lines-per-case'];
            $outputList = getListSeparatedByLines($outputTempName, $linesPerCaseOutput);
            break;
        case '#CASEMARK-separated-output':
            $caseMarkForOutput = $_PAR['output-casemark'];
            $outputList = getListSeparetedByMark($outputTempName, $caseMarkForOutput);
            break;
    }
    // $io = array ('i'=>$inputList,'o'=> $outputList);
    
    // print_r($outputList);
    $table = getIOTabularView($inputList,$outputList);
    // print_r($io);
    
    $result = '<label>Problem Name: </label>'.$problemName.'<br/>';
    $equalSize=sizeof($inputList)==sizeof($outputList);
    if(sizeof($inputList)==sizeof($outputList)){
        $result .= '<label style="color:green">input size = '.sizeof($inputList).' == output size = '.sizeof($outputList).'</label>';
        $result .=
        '<form method="POST" action="admin_createproblem_process.php">
            <input type="submit" value="Save Problem"/>
        </form>';
        $result.= $table;

        $_SESSION['io']=array('inputList'=>$inputList,'outputList'=>$outputList);
        $_SESSION['problemName']=$problemName;

        showPage('Problem Preview', false, $result, null);
    }else{
        $result .= '<label style="color:red">input size = '.sizeof($inputList).' != output size = '.sizeof($outputList).'</label>';
        $result.= $table;
        showPage('Problem Preview', false, $result, null);
    }
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

function getListSeparatedByLines($tempName, $linesPerCase, $nroFirstLinesToSkip=0){
    
    $file_handle = fopen($tempName, "r");
    $counter=0;
    $singleCase="";
    $totalRead=0;
    $res = array();
    while($line = fgets($file_handle)){
        if($nroFirstLinesToSkip-->0)continue;
        $line = trim($line);
        $singleCase.=$line."\n";
        if(++$counter==$linesPerCase){
            $counter=0;
            $res[$totalRead]=$singleCase;
            $singleCase='';
            $totalRead++;
        }
    }
    return $res;
}

function getListSeparetedByMark($tempName, $caseMark, $nroFirstLinesToSkip=0, $includeMatch=true){
    $caseMark = str_replace("\\\\", "\\", $caseMark);
    $file_handle = fopen($tempName, "r");
    $singleCase="";
    $totalRead=0;
    $res = array();
    $singleCase='';
    $isRegex=$caseMark[0]=='/' && $caseMark[strlen($caseMark)-1]=='/';
    // print_r($caseMark);
    // print_r($isRegex);
    while($line = fgets($file_handle)){
        if($nroFirstLinesToSkip-->0)continue;
        $line = trim($line);
        $matches = false;
        if($isRegex){
            $matches = preg_match($caseMark, $line, $m, PREG_OFFSET_CAPTURE);
        }else{
            $matches = $line==$caseMark;
        }
        if($matches){
            if($singleCase!=''){
                $res[$totalRead]=$singleCase;
                $totalRead++;
            }
            if($includeMatch){
                $singleCase=$line."\n";
            }else{
                $singleCase='';    
            }
        }else{
            $singleCase.=$line."\n";    
        }
    }
    if($singleCase!=''){
        $res[$totalRead]=$singleCase;
        $totalRead++;
    }
    return $res;
}

// function getIOArrayFromUploadedFile($inputName, $outputName, $linesPerInputCase, $linesPerOutputCase, $isFirstLineCounter){
    
//     $inputFile_handle = fopen($inputName, "r");
//     $outputFile_handle = fopen($outputName, "r");
//     $expectedTotal='';
//     if($isFirstLineCounter=='on'){
//         $expectedTotal = fgets($inputFile_handle);
//     }
//     $inputCounter=0;
//     $singleInputCase="";
//     $totalRead=0;
//     $io = array();
//     while($inputLine = fgets($inputFile_handle)){
//         $inputLine = trim($inputLine);
//         $singleInputCase.=$inputLine."\n";
//         if(++$inputCounter==$linesPerInputCase){
//             $outputCounter=0;
//             $singleOutputCase="";
//             while($outputLine=fgets($outputFile_handle)){
//                 $outputLine = trim($outputLine);
//                 $singleOutputCase.=$outputLine;
//                 if($outputCounter++<$linesPerOutputCase){
                    
//                     $outputCounter=0;

//                     $io[$totalRead]=array('i'=>$singleInputCase,'o'=>$singleOutputCase);

//                     $totalRead++;
//                     $inputCounter=0;
//                     $singleInputCase="";
//                     break;
//                 }
                
//             }
//         }
//     }
//     return $io;
// }

function getIOTabularView($inputList, $outputList){
    
    ob_start();
    $totalRead=sizeof($inputList);
    for($i=0; $i<$totalRead ; $i++) {
        ?>
            <tr>
                <td style ="border-width:2px; border-style:ridge; font-family:courier;" >
                    <?php echo str_replace("\n", "</br>", $inputList[$i]) ?></td>
                <td style ="border-width:2px; border-style:ridge; font-family:courier;">
                    <?php echo str_replace("\n", "</br>", $outputList[$i]) ?></td>
            </tr>
        <?php
    }
    $result = '<table border="1" style ="border-width:2px; border-style:ridge;">';
    $result = $result.ob_get_contents().'
    </table>';
    ob_end_clean();
    return $result;
}
?>