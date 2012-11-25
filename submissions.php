<?php
session_start();
// include_once ("utils/ValidateSignedIn.php");
include_once ("container.php");

showPage("All Accepted Practice submissions",false,getHTML());
?>

<?php
function getHTML(){
	ob_start();
	?>
	<script type="text/javascript" src="js/mylibs/submissionsCtrl.js"></script>
	<div ng-app>
		<div style="width:500px" ng-controller="SubmissionsCtrl">
		 <table class="table table-condensed table-striped table-hover">
	        <thead>
	          <tr>
	            <th>Coder</th>
	            <th>Problem</th>
	            <th>Result</th>
	            <th>When</th>
	          </tr>
	        </thead>
	        <tbody>
	        <tr ng-repeat="submission in submissions">
		        <td>
		            <a class='userLink' href="user.php?uname={{submission['username']}}"
		                ng-bind-template="{{submission['username']}}";?>">
		            </a>
		        </td>
		        <td ng-bind-template="<?php echo "{{submission['problem_name']}}";?>">
		        </td>      
		        <td>
		        	<span class="label label-success">Accepted</span>
		        </td>
		        <td ng-bind-template="<?php echo "{{submission['solving_date']}}";?>">
		        </td>
	          </tr>
	        </tbody>
	      </table>
	  </div>
  </div>
	<?php
	$res = ob_get_contents();
	ob_end_clean();
	return $res;
}
?>