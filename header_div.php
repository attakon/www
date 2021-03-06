<?php


function headerFunction($statusBar){
    //print_r($_SESSION);

    ob_start();
    $path;

    include_once ("data_objects/DAOContest.php");
   	$arr=DAOContest_getActiveContests();
   	// print_r($arr);
	$contestMenuItems='';
	$count = 0;
    foreach($arr as $key => $val){
        $contestName = $val['nombre'];
        $contestId = $val['contest_id'];
        if($count>0){
			$contestMenuItems.=",";
		}
        $contestMenuItems .= '
				{                                    
					text: "'.$contestName.'",
					url: "./concurso.php?idt='.$contestId.'&show=det"
				}';
		$count++;

    }
    if($count==0){
    	$contestMenuItems .= '
				{                                    
					text: "Coming soon..."
				}';
    }

    ?>
<div id="header" >
	<div id="h_left">
		<a href="./index.php">
<!-- upd - Jonathan - 2012-11-22 -->
            <img  align="left" src="./images/hclogo_strike.png" border="0" class="mainLogo"/>
        </a>
	</div>
	<div id="h_right">
		<div id="h_r_top">
			<!-- add - Jonathan - 2012-06-10 -->
		<ul id="menu">
		</ul>
		<script>
		function abrir(){
			$("#aviso").css('display','block');
			$("#aviso").data("kendoWindow").center();
			$("#aviso").data("kendoWindow").open();
		}
		$(document).ready(function() {
			$("#aviso tr td").css('color','white');
			$("#menu").kendoMenu({
				dataSource:
				[	{
						text: "Home",                              
						url: "./index.php"                                 
					},
					{
						text: "Running Contests",
						items: [
						<?php echo $contestMenuItems;?>
						]
					},
					{
						text: "All Contests",                              
						url: "./allcontests.php"                                 
					},
					{
						text: "Submissions",
						url: "./submissions.php"
					},
					{
						text: "Ayuda",
						items: [
							{
								 text: "Tutoriales",
								 url: "./tutorials.php",
							},{                                    
								text: "Competici&oacuten General",
								url: "./reglas.php",
								encoded: false
							},{
								 text: "Sistema de Ranking",
								 url: "./reglas_ranking.php"
							}]
					}
				]
		 })
			//comentado jonathan 2012-07-16
			/*$("#aviso").kendoWindow({
					actions: ["Close"],
					height: "300px",
					modal: true,
					resizable: false,
					title: "Concurso vigente",
					width: "500px",
					visible: false
			});
			var t=setTimeout("abrir();",1000)*/
		});
		</script>
		</div>
		<div id="h_r_bottom">
			<div id="h_r_b_top">

			</div>
			<div id="h_r_b_bottom">
				<?php
					// print_r($_SESSION);
		    		include ('header_user_navbar.php');
		    	?>
			</div>
			
		</div>
	</div>
</div>
<!-- STATUS BAR -->
<div id="bar" >
	<!-- <p class="lead"> -->
	<?php
    	echo $statusBar;
    ?>
	<!-- </p> -->
</div>

<?php
$re = ob_get_contents();
ob_end_clean();
return $re;
}
?>
