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

    
    if(isset($_GET['pid']) && isset($_GET['dellid'])){
        $problemId = $_GET['pid'];        
        $delLanguageId = $_GET['dellid'];
        // $problemData = $DAOProblem_getProblemData($problemId);
        include_once 'data_objects/DAOProblem.php';
        DAOProblem_deleteProblemStatementForLanguage($problemId, $delLanguageId);
        $_SESSION['message']='Statement deleted';
        
        include_once 'container.php';
        redirectToLastVisitedPage();
    }

    if(isset($_GET['pid'])){

    	$problemId = $_GET['pid'];
    	include_once 'data_objects/DAOProblem.php';
    	$problemData = DAOProblem_getProblemData($problemId);

    	$fields = array(
        	'language_id' => 
        	array('type'=>'list',
            	'label'=>'Language',
            	'list'=>array(
                	'table'=>'languages',
                	'idField'=>'language_id',
                	'labelField'=>'name'
             		)
            )
        );
	    include_once 'maintenanceForm.php';
	    $problemInsertForm = new RCMaintenanceForm('co_problem_statement',$fields,null,'Next', 'name',
	        'style="text-align: center; width:400px"');
 	    $content = getEditorHTML($problemData['name'],$problemInsertForm->getInputControls(),$problemId);

        include_once 'data_objects/DAOProblem.php';
        $problemStatementsData = DAOProblem_getProblemStatements($problemId);
        $statementsHTML = "";
        $statement='';
        // print_r($problemStatementsData);
        foreach ($problemStatementsData as $key => $value) {
            $style = '';
            if(isset($_GET['lid']) && $_GET['lid']==$value['language_id']){
                $statement.='<div style="width:700px; text-align:left">'.$value['statement'].'</div>';
                $style = 'style="font-size: 20px;"';
            }
            $statementsHTML.="<a ".$style." href='./admin_myproblem.php?pid=".$problemId."&lid=".$value['language_id']."'>".$value['name'].'</a>';
            $statementsHTML.='<a href="admin_myproblem.php?pid='.$problemId.'&dellid='.$value['language_id'].'">[x]</a> ';
        }
        $content.=$statementsHTML."<hr/>
            <br/>".$statement."<hr/>";

        // IO
        $tablesPC="co_problem_testcase ptc, co_problem pr , (SELECT @rownum:=0) r";
        $columnsPC = array(
        array("@rownum:=@rownum+1 'order'",  "N",     15, ""),
        array("ptc.testcase_id ",  "",     -1, ""),
        array("ptc.case_input",  "Input",     -2, "","",
            'td_atr'=>'style ="border-width:2px; border-style:ridge; font-family:courier;"'),
        array("ptc.case_output",  "Output",     -2, "","",
            'td_atr'=>'style ="border-width:2px; border-style:ridge; font-family:courier;"')
        );

        $conditionPC = "WHERE ptc.problem_id = pr.problem_id ".
            " AND pr.problem_id = '".$problemId."' ".
            " ORDER BY 2 ASC ";

        include_once 'table2.php';
        $manageContestTable = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionPC);
        $manageContestTable->showLineBreaks(true);
        $content.= "<br/>".$manageContestTable->getTable();
	    showPage('Problem Preview', false, $content, null);
    }
}


function getEditorHTML($problemName, $extraInputControls, $problemId){
    ob_start();
    ?>
    <script type="text/javascript" src="/js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript">
        tinyMCE.init({
               // General options
                mode : "textareas",
                theme : "advanced",
                plugins : "lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,inlinepopups,preview,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

                // Theme options
                theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
                theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,image,cleanup,code,|,preview,|,forecolor",
                theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr,|,print,|,fullscreen",
                theme_advanced_buttons4 : "absolute,|,styleprops,spellchecker",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing : true,

                // Skin options
                skin : "o2k7",
                skin_variant : "silver",

                // Example content CSS (should be your site CSS)
                content_css : "css/example.css",

                // Drop lists for link/image/media/template dialogs
                template_external_list_url : "js/template_list.js",
                external_link_list_url : "js/link_list.js",
                external_image_list_url : "js/image_list.js",
                media_external_list_url : "js/media_list.js",

                // Replace values for the template plugin
                // template_replace_values : {
                //         username : "Some User",
                //         staffid : "991234"
                // }
        });
    </script>

    <form method="post" action="./admin_myproblem_addstatement_process.php">
      <h2><?php echo $problemName?></h2>
      	<?php
        	echo $extraInputControls;
        ?>
        <br/>
	    <textarea id="content" name="content" style="width:700px">
	    </textarea>
     	<br/>
     	<input type="hidden" name="pid" value="<?php echo $problemId ?>" />
      	<input type="submit" name="submit" value="Save Problem Statement" />
    </form>
<?php 
$res = ob_get_contents();
ob_end_clean();
return $res;
}
?>