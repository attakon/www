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

    <script src="js/bs_dropdown.js"></script>
<div class="row show-grid">
	<div class="offset11 span3">
		<ul class="nav">
		  <li class="dropdown">
		    <a data-target="#" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" id="xxx">Account
		    	<b class="caret"></b>
		    </a>
		    <ul class="dropdown-menu" role="menu" aria-labelledby="xxx">
		      <li>
		      	problem
		      </li>
		    </ul>
		  </li>
		</ul>
	</div>
</div>
	<?php
	$res = ob_get_contents();
	ob_end_clean();
	return $res;
}
?>
