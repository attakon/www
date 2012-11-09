<?php
session_start();
include_once 'utils/ValidateAuthor.php';

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
        'io_url' => 
            array('label'=>'io_url','type'=>'text'),
        'example_cases'=> 
            array('label'=>'Example Cases','type'=>'number'),
        'replace_target1'=> 
            array('label'=>'replace','type'=>'text', 'value'=>'/{|}|,/'),
        'replace_by1'=> 
            array('label'=>'replace by','type'=>'text'),
        'replace_target2'=> 
            array('label'=>'replace','type'=>'text', 'value'=>''),
        'replace_by2'=> 
            array('label'=>'replace by','type'=>'text'),
        'replace_target3'=> 
            array('label'=>'replace','type'=>'text', 'value'=>''),
        'replace_by3'=> 
            array('label'=>'replace by','type'=>'text')
        );
    include_once 'maintenanceForm.php';
    $problemInsertForm = new RCMaintenanceForm('problem',$fields,'previewTCProblem','Next', 'name',
        'style="text-align: left; width:400px"', 'enctype="multipart/form-data"');


    // See problem table 
    $tablesPC="co_problem problem";
    $columnsPC = array(
    array("problem.problem_id",  "",     -1, ""),
    array("problem.creator_id",  "",     -1, ""),
    array("problem.name",  "Problem Name",     -1, "",""),
    array("'view'",  "Problems", 80, "", 
        "type"=>"replacement", 
        'value' => "<a href='/admin_myproblem.php?pid=#{0}'>#{2}</a>"),
    array("'delete'",  "Delete", 80, "", 
        "type"=>"replacement", 
        'value' => "<a href='/admin_myproblems.php?remprobleid=#{0}'>Delete</a>"),
    array("'test'",  "Test", 80, "", 
        "type"=>"replacement", 
        'value' => "<a href='/author_problem.php?id=#{0}'>Test Problem</a>")
    // array("'add statement'",  "Add Statement", -2, "", 
    //     "type"=>"replacement", 
    //     'value' => "<a href='/admin_myproblem_addstatement.php?pid=#{0}'>Add Statement</a>")

    );
    $conditionPC = "WHERE problem.creator_id = '".$_SESSION['userId']."'
     ORDER BY 1 DESC";

    include_once 'table2.php';
    $problemList = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionPC);

    $content = $problemInsertForm->getForm().'<br/>'.$problemList->getTable();
    // $content = getEditorHTML();
    include_once 'container.php';
    showPage('My Problems', false, $content, null,'370');
}

function previewTCProblem($_PAR){


    // $table = getIOTabularView($inputTempName,$outputTempName,$linesPerInputCase, $linesPerOutputCase,$isFirstLineCounter);
    $problemName = $_PAR['name'];
    $exampleCasesCount = $_PAR['example_cases'];
    $problemIOURL = $_PAR['io_url'];
    include_once 'io_scrap/ct.php';

    // http://community.topcoder.com/stat?c=problem_solution&cr=22691410&rd=15181&pm=12200
    $scrappedIO = new TCIOScrap($problemIOURL);

    $inputList = $scrappedIO->inputList;
    $outputList = $scrappedIO->outputList;
    
    // $io = array ('i'=>$inputList,'o'=> $outputList);
    
    // print_r($outputList);
    // echo $exampleCasesCount;


    $replaceTarget1 = stripslashes($_PAR['replace_target1']);
    $replaceBy1 = stripslashes($_PAR['replace_by1']);
    $replaceTarget2 = stripslashes($_PAR['replace_target2']);
    $replaceBy2 = stripslashes($_PAR['replace_by2']);
    $replaceTarget3 = stripslashes($_PAR['replace_target3']);
    $replaceBy3 = stripslashes($_PAR['replace_by3']);
    // echo $replaceBy;
    
    for ($i=0 ; $i<sizeof($inputList);$i++) {
        if($replaceTarget1)
            $inputList[$i]= preg_replace($replaceTarget1, $replaceBy1, $inputList[$i]);
        if($replaceTarget2)
            $inputList[$i]= preg_replace($replaceTarget2, $replaceBy2, $inputList[$i]);
        if($replaceTarget3)
        $inputList[$i]= preg_replace($replaceTarget3, $replaceBy3, $inputList[$i]);
    }
    
    // print_r($inputList);
    $table = getIOTabularView($inputList,$outputList,$exampleCasesCount);


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
        $_SESSION['exampleCasesCount']=$exampleCasesCount;

        showPage('Problem Preview', false, $result, null);
    }else{
        $result .= '<label style="color:red">input size = '.sizeof($inputList).' != output size = '.sizeof($outputList).'</label>';
        $result.= $table;
        showPage('Problem Preview', false, $result, null);
    }
}

function getIOTabularView($inputList, $outputList, $exampleCasesCount){
    
    ob_start();
    $totalRead=sizeof($inputList);
    // echo $exampleCasesCount;
    $exampleCaseStyle='background-color:yellow;';
    for($i=0; $i<$totalRead ; $i++) {
        ?>
            <tr>
                <td style ="border-width:2px; border-style:ridge; font-family:courier; <?php echo $i<$exampleCasesCount?$exampleCaseStyle:''; ?>" >
                    <?php echo preg_replace("/\n/", "</br>", $inputList[$i]) ?></td>
                <td style ="border-width:2px; border-style:ridge; font-family:courier; <?php echo $i<$exampleCasesCount?$exampleCaseStyle:''; ?>">
                    <?php echo preg_replace("/\n/", "</br>", $outputList[$i]) ?></td>
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