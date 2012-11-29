<?php
include_once ("utils/ValidateSignedIn.php");
include_once ("container.php");

showPage("Conectar a cuenta de HC",false,getForm());
?>

<?php
//en 3 2 1 
function getForm(){
	ob_start();
	?>
	<p style="text-align:center;">
		Por favor, ingrese las credenciales de su cuenta de HuaHCoding existente:
	</p>
	<form  action="link_account_process.php" method="POST"
		style="text-align:center;"  ">
		<input 
			style="width:150px" 
			placeholder="HuaHCoding Username" type="input" name="username"/>
		<input placeholder="password" type="password" name="password"/>
		<input type="submit" value="Conectar Cuenta">
	</form>
	<?php
	$res = ob_get_contents();
	ob_end_clean();
	return $res;
}
?>