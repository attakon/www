<?php
include_once 'conexion.php';
//echo proc("1");
function getForm($id){
    //    ob_start();
    ?>
    <form method="POST" action="practice_process.php" enctype="multipart/form-data">
        <table border="0" width="600" height="50" >
            <tr>
                <td colspan="2" width="100%">
                    <a href="practice_download.php?id=<?php echo$id?>">Descargar Input</a>                
                </td>
            </tr>
            <tr>
                <td height="26" width="86">

                <p>Tu output:</p></td>
                <td height="26" width="386">
                    <input type="file" name="userout" size="39">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <label class="comment">
                        Para practicar no se requiere presentar el c&oacute;digo fuente.
                    </label>
                </td>
            </tr>
            <tr>
                <td height="30" width="478" colspan="2">
                    <p align="center">
                <input type="submit" value="Presentar" name="B1">
                <input type="hidden" value=<?php echo$id?> name="p">
                </td>            
            </tr>
        </table>
    </form>
<?php
//$r = ob_get_contents();
//ob_clean();
//return $r;
}
?>