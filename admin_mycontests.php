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
// print_r($_SESSION);
function init(){
    if(isset($_GET['delcontestid'])){
        $contestId =$_GET['delcontestid'];

        include_once 'data_objects/DAOContest.php';
        if(!DAOContest_IsContestDeletable($contestId)){
            $_SESSION['message']='You cannot delete this contest.';
            $_SESSION['message_type']='error';
            redirectToLastVisitedPage();
        }
        
        $contestData = DAOContest_getContestData($contestId);
        $creatorId = $contestData['creator_id'];
        if($creatorId == $_SESSION['userId']){
            $contestName = $contestData['nombre'];
            if($contestName!=null){
                DAOContest_deleteContestCampaigns($contestId);
                DAOContest_deleteContestProblems($contestId);
                DAOContest_deleteContestInvites($contestId);
                DAOContest_deleteContest($contestId);
                $_SESSION['message']='Contest '.$contestName.' was completely deleted';
                $_SESSION['message_type']='ok';    
            }
        }else{
            // not owner of contest
            }
        }

        $isUpd=false;
        if(isset($_GET['updcontestid'])){
            $isUpd=true;
            $contestIdToUpdate = $_GET['updcontestid'];
            include_once 'data_objects/DAOContest.php';
            $contestData = DAOContest_getContestData($contestIdToUpdate);
        }


        $fields = array(
            'league_id' => 
                array('type'=>'list',
                    'label'=>'League',
                    'list'=>array(
                        'table'=>'co_league',
                        'idField'=>'league_id',
                        'labelField'=>'nombre',
                        'condition'=>"WHERE creator_id ='".$_SESSION['userId']."'")),
            'nombre'=> 
                array('label'=>'Contest Name',
                    'type'=>'text'
                    ),
            'fecha'=>
                array('label'=>'run date',
                    'type'=>'datetime',
                    'format'=>'yyyy-MM-dd hh:mm:ss',
                    'value'=>'2012-12-12 12:12:12'
                    ),
            'descripcion'=>array("type"=>'text',
                'label'=>'Description'),
            'total_time'=>
                array('type'=>'text',
                    'label'=>'Contest Duration:',
                    'format'=>'hh:mm:ss',
                    'value'=>'03:00:00'),

            // 'left_time'=>array('type'=>'time',
            //         'label'=>'left_time',
            //         'format'=>'hh:mm:ss'),
            'is_invitational'=>array(
                    'type'=>'checkbox',
                    'label'=>'is_invitational'
                    ),
            'is_published'=>array(
                    'type'=>'checkbox',
                    'label'=>'is_published'
                    ),
            'creator_id'=>array(
                'type'=>'hidden',
                'value'=>$_SESSION['userId']
                )
        );


        // $buttonName = 'Create Contest';


        include_once 'maintenanceForm.php';
        $insertContestForm = new RCMaintenanceForm('concurso',$fields,
            $__method_to_invoke=null, 
            $buttonName='Create Contest', 
            'nombre', 'style="text-align: left; width:450px"');
        $insertContestForm->setOnSuccessRedirectPage('admin_mycontests.php');
        $insertContestForm->setSuccessMessage('Contest created');
        if(isset($_GET['updcontestid'])){
            $contestIdToUpdate = $_GET['updcontestid'];
            $insertContestForm->setUpdIdField('contest_id');
            $insertContestForm->setUpdIdValue($contestIdToUpdate);
            $insertContestForm->setButtonName('Update Contest');
            $insertContestForm->setSuccessMessage('Contest updated');
        }




        $tablesPC="concurso co";
        $columnsPC = array(
            array("co.contest_id",  "",     10, "",
                "type"=>"",
                'td_atr'=>'style="font-size:10px; color:lightgray"'),
            array("co.creator_id",  "", -1, "",
                "type"=>""),
            array("co.nombre",  "", -1, "",
               "type"=>""),
            array("'Contests'",  "Contest", -2, "", 
                "type"=>"replacement", 
                'value' => "<a href='./admin_mycontestproblems.php?id=#{0}'>#{2}</a>"),
            array("'edit'",  "Edit", 80, "", 
                "type"=>"replacement", 
                'value' => "<a href='./admin_mycontests.php?updcontestid=#{0}'>Edit</a>"),
            array("'delete'",  "Delete", 80, "", 
                "type"=>"replacement", 
                'value' => "<a href='./admin_mycontests.php?delcontestid=#{0}'>Delete</a>")
        );

                // case 'con_res':return "<a href='$path/concurso_results.php?i=$id&tab=2'>$caption</a>";

        $conditionPC = "WHERE co.estado = 'REGISTRATION_OPEN' ".
            " AND co.creator_id = ".$_SESSION['userId'].
            " ORDER BY 1 DESC";

        include_once 'table2.php';
        $manageContestTable = new RCTable(conecDb(),$tablesPC,$columnsPC,$conditionPC);
        //$manageContestTable->setTitle("Concursos Pasados");

        $content = $insertContestForm->getForm().
        '<br/>'.$manageContestTable->getTable();

        include_once 'container.php';
        showPage('My Contests', false, $content , null,'600');
}
  
function insertContest($_PAR){
    // print_r($_PAR);
    include_once 'data_objects/DAOContest.php';
    DAOContest_registerContest(
        $_PAR['league_id'],
        $_PAR['nombre'],
        $_PAR['descripcion'],
        $_PAR['total_time'],
        $_PAR['is_invitational'],
        $_PAR['is_published'],
        $_PAR['creator_id']);
    include_once "container.php";
    $_SESSION['message']='Contest Added Successfully';
    redirectToPage('./admin_mycontests.php');
}  

?>