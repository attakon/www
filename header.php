<?php
include_once ("data_objects/DAOContest.php");

function headerFunction($statusBar){
    //print_r($_SESSION);

    ob_start();

   	$arr=DAOContest_getActiveContests();
	$contestMenuItems='';
	$count = 0;
    foreach($arr as $key => $val){
    	$count++;
        $contestName = $val['nombre'];
        $contestId = $val['contest_id'];
        $contestMenuItems .= '
				{                                    
					text: "'.$contestName.'",
					url: "./concurso.php?idt='.$contestId.'&show=det"
				}';
    }
    if($count==0){
    	$contestMenuItems .= '
				{                                    
					text: "Coming soon..."
				}';
    }

    ?>
<table align ="center" height="150" width="100%" style="margin-top:0;padding-top:0;" >
    <tr>
        <td rowspan="2" width="400">

            <a href="<?php echo $path;?>/index.php">
                <img  width="400" height="150" align="left" src="/images/rc_HuaHCoding2.png" border="0"/>
            </a>
        </td>
        <td height="100" ><!--width="523"> !-->
            <center>
                <!-- <script type="text/javascript" src="menu/resources/menu.js"></script> -->
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
								text: "Concursos Activos",
								items: [
								<?php echo $contestMenuItems;?>
								]
							},
							{
								text: "Concursos Pasados",                              
								url: "./concurso_list.php"                                 
							},
							{
								text: "Ranking",
								items: [{                                    
									text: "General",
									url: "./ranking.php"
								},
								{
									text: "Escuelas",
									items: [{                                    
										text: "UNJFSC - Ing. Inform&aacutetica",
										url: "./ranking.php?ids=2"
									},
									{
										 text: "UNJFSC - Ing. Sistemas",
										 url: "./ranking.php?ids=3"
									},
									{
										 text: "Universidad Nacional de Ingener&iacutea",
										 url: "./ranking.php?ids=4"
									}]
								}]
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
            </center>
        </td>
    </tr>
    <tr>
        <td class = "userBar" height="27">
    <?php
    include ('header_userBar.php');
    ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="title" height="26" > <!-- width="898"> !-->
    <?php
    echo $statusBar;
    ?>
        </td>
    </tr>
</table>

<?php
$re = ob_get_contents();
ob_end_clean();
return $re;
}
?>
