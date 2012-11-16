<?php
function sendWelcomeEmailSP($recipient, $username){
	$message = "Hello ".$username."\r\n\r\n
		\n
		\n
		\nAhora podrás:
		\n  - Participar en los próximos concursos.
		\n  - Practicar con los concursos anteriores.
		\n  - Ver las soluciones de los demás participantes.
		\n
		\nWelcome and have fun
		\n-HuaHCoding Stuff";

// In case any of our lines are larger than 70 characters, we should use wordwrap()
	$message = wordwrap($message, 70);

	$headers = "From: HuaHCoding <no-reply@huahcoding.com>\n";
	$subject = 'Welcome to HuaHCoding!';

	// Send

	$accepted = mail($recipient, $subject, $message, $headers);
	// echo $accepted;
}
function sendWelcomeEmail($recipient, $username){
	$message = "Hello ".$username."\r\n\r\nSo, do you think you can code?
		\nWe'll see
		\n
		\n
		\nWelcome and have fun
		\n-HuaHCoding Stuff";

// In case any of our lines are larger than 70 characters, we should use wordwrap()
	$message = wordwrap($message, 70);

	$headers = "From: HuaHCoding <no-reply@huahcoding.com>\n";
	$subject = 'Welcome to HuaHCoding!';

	// Send

	$accepted = mail($recipient, $subject, $message, $headers);
	// echo $accepted;
}
function sendNotificationEmail($email, $username, $name){
	$message = $name."(".$username.") has just registered with the email ".$email;

// In case any of our lines are larger than 70 characters, we should use wordwrap()
	$message = wordwrap($message, 70);

	$headers = "From: HuaHCoding <no-reply@huahcoding.com>\n";
	$subject = $username.' / '.$email.' has just registered';

	// Send

	$accepted = mail('huahcoding@gmail.com', $subject, $message, $headers);
	// echo $accepted;
}
	
?>
