<?php
//$incl = $_SERVER['DOCUMENT_ROOT'].'/utils/DBUtils.php';

$cwd = str_replace('/forum', '', getcwd());
$incl = $cwd.'/utils/DBUtils.php';
include_once $incl;
//include_once $_SERVER['DOCUMENT_ROOT'].'/huahcodixng.com/'.'utils/DBUtils.php';

//echo $_SERVER['DOCUMENT_ROOT'];
function DAOPermissions_isUserGrantedWithPermission($userId, $featureName, $grantingValue){
   $roles = DAOPermissions_getUserRoleId($userId);
   $userRoleName = DAOPermissions_getUserRoleName($userId);
   foreach ($roles as $key => $value) {
      $userRoleId = $value['role_id'];
      if(!is_null($userRoleId)){
         $permissions = DAOPermissions_getRolePermissions($userRoleId);
         foreach ($permissions as $key => $value) {
             if($value['feature_name']==$featureName && $value['permission_value']==$grantingValue){
                 return true;
             }
         }
      }
   }
   
   return false;
}

function DAOPermissions_getUserRoleId($userId){
   $query = "SELECT role_id FROM user_roles 
                       WHERE id_usuario = '".$userId."'";
//   echo print_r($_SERVER);
   return getRowsInArray($query);
   // return $n;
}

function DAOPermissions_getUserRoleName($userId){
   $query = "SELECT r.role_name FROM user_roles ur join roles r using(role_id)
                       WHERE ur.id_usuario = '".$userId."'";
   $n = getRow($query);
   return $n;
}

function DAOPermissions_getRolePermissions($roleId){
    $query = "SELECT f.feature_name, fp.permission_value FROM features f join feature_permissions fp using(feature_id) 
                       WHERE fp.role_id = '".$roleId."'";
   $n = getRowsInArray($query);
   return $n;
}
?>
