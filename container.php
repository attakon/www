<?php
/**
 *
 * @param <type> $statusBar
 * @param <type> $including
 * @param <type> $bodyContent
 * @param <type> $bodyOptionalAtr attribute of the <td> tag
 */
function showPage($statusBar, $including, $bodyContent ,$bodyOptionalAtr, $contentWidth='100%'){
//    error_reporting(E_ALL ^ E_NOTICE);
//    session_start();
  //  session_end();
    include_once("session_routines.php");
    include_once("header.php");
    ?>
<html>
    <?php 
        include_once("head_includes.php");//mandatory 
    ?>
    <body style="margin-right:13; margin-left:13; margin-top:0;padding-top:0;" >
        <?php echo headerFunction($statusBar, '.')?>

        <div align="center">
        <table width="<?php echo $contentWidth?>">
                <tr>
                    <td colspan="2" class="body" height="300"  <?php echo $bodyOptionalAtr?> >
                        <?php
                        if($including==true){
                            include($bodyContent);
                        }else{
                            echo $bodyContent;
                        }
                        ?>
                    </td>
                </tr>
         </table>
        </div>
        <?php
        include("footer.php");
        ?>
    </body>
</html>
<?php
}
?>
