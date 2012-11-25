<?php
session_start();
include_once ("utils/ValidateSignedIn.php");
include_once ("container.php");

showPage("Reset Password",false,getForm());
?>

<?php
function getForm(){
	ob_start();
	?>
	<p style="text-align:center;">
		Please enter the current password, and the new password twice.
	</p>
	<form  class="form-inline" action="user_password_reset_process.php" method="POST"
		style="text-align:center;">
		<input type="password" placeholder="current password" name="current_password"/>
		<input type="password" placeholder="new password" name="new_password"/>
		<input type="password" placeholder="new password" name="new_password_2"/>
		<input class="btn" type="submit" value="Change password">
	</form>
	<?php
	$res = ob_get_contents();
	ob_end_clean();
	return $res;
}
?>