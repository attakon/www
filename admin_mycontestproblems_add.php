<?php
session_start();
include_once 'utils/ValidateAuthor.php';

if(isset($_GET['pid']) && isset($_GET['cid'])){
    $problemId = $_GET['pid'];
    $contestId = $_GET['cid'];

    include_once 'data_objects/DAOProblem.php';
    $problemData = DAOProblem_getProblemData($problemId);

    $fields = array(
        'points'=>array(
            'type'=>'number',
            'value'=>'1'),
        'problem_language_id' => 
            array('type'=>'list',
                'label'=>'Language',
                'list'=>array(
                    'table'=>'co_problem_statement join languages using(language_id)',
                    'idField'=>'language_id',
                    'labelField'=>'name',
                    'condition'=>"where co_problem_statement.problem_id='".$problemId."'"
                    ),
            ),
        'problem_id' => array('type'=>'hidden','value'=>$problemId),
        'contest_id' => array('type'=>'hidden','value'=>$contestId)
    );

    include_once 'maintenanceForm.php';
    
    $problemInsertForm = new RCMaintenanceForm('co_contest_problems', 
        $fields,null,
        'Add Problem', 
        'problem '.$problemData['name'].' was added',
        'style="text-align: center; width:400px"');

    $problemInsertForm->setOnSuccessRedirectPage('admin_mycontestproblems.php?id='.$contestId);

    showPage('Adding '.$problemData['name'], false, $problemInsertForm->getForm(), null);
}
?>