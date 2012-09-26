<?php
session_start();
include_once 'utils/ValidateAdmin.php';

include_once 'maintenanceForm.php';

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
    'username'=>
        array('type'=>'list',
            'label'=>'Username',
            'list'=>array(
                'table'=>'usuario',
                'idField'=>'username',
                'labelField'=>'username',
                'condition'=>''
            )),
    'new_password'=>array('type'=>'text')
    );
    $tablePC = new RCMaintenanceForm('usuario',$fields,'updatePassword','Reset',null);
    include_once 'container.php';
    include_once 'CustomTags.php';
    showPage('Reset User Password', false, parrafoOK($tablePC->getForm()), null,'250');
}

function updatePassword($_PAR){
    $REQ = $_PAR;
    $pass = $REQ['new_password'];
    $username = $REQ['username'];

    $huahQry = "UPDATE usuario 
                SET PASS = MD5('".$pass."') 
                WHERE USERNAME ='".$username."'";
    runQuery($huahQry);

    $huahVbQry= "UPDATE user
    set password = MD5(concat(MD5('".$pass."'), user.salt))
    WHERE username = '".$username."'";
    runQueryOnHuaHVB($huahVbQry);
    showPage('Password Reset Done', false, parrafoOK('Password has been reset successfully'), null);

}

?>