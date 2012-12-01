<?php
/**
 *
 * @param <type> $statusBar
 * @param <type> $including
 * @param <type> $bodyContent
 * @param <type> $bodyOptionalAtr attribute of the <td> tag
 */
function showPage($statusBar, $including, $bodyContent ,$bodyOptionalAtr="", $contentWidth='100%'){
//    error_reporting(E_ALL ^ E_NOTICE);
//    session_start();
  //  session_end();
    include_once("session_routines.php");
    // include_once("header.php");
    include_once("header_div.php");
    ?>
    <!DOCTYPE html>
    <html xmlns:fb="http://ogp.me/ns/fb#" >
    <?php 
        include_once("head_includes.php");//mandatory 
        include_once "GLOBALS.php";
    ?>
    <body style="margin-right:13; margin-left:13; margin-top:0;padding-top:0;" <?php echo $bodyOptionalAtr?> >
           <!-- JS SDK -->
        <?php echo headerFunction($statusBar)?>

        <?php 
            // $_SESSION['message']='ok message';
            // $_SESSION['message_type']='ok';
            // $_SESSION['message_type']='error';
            if(isset($_SESSION['message'])){
                if($_SESSION['message_type']=='error'){
                    $divid='error-user-message-div';
                }else{
                    $divid='ok-user-message-div';
                }
                ?>
                <div id="<?php echo $divid;?>" >
                        <div id="<?php echo $divid."-inner" ?>">
                            <?php echo $_SESSION['message']; ?>
                        </div>
                </div>
                <br/>
                <?php
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
        ?>
        <!-- <div style="text-align"align="center"> -->
        <div align="center" style="margin-top: 20px;">
        <!-- <table width="<?php echo $contentWidth?>">
                <tr>
                    <td colspan="2" class="body" height="300"  <?php echo $bodyOptionalAtr?> > -->
                      <div style="min-height:500px">
                        <?php
                        if($including==true){
                            include($bodyContent);
                        }else{
                            echo $bodyContent;
                        }
                        ?>
                      </div>
                   <!--  </td>
                </tr>
         </table> -->
        </div>
        <hr />
        <footer>
            HuaHCoding &copy; 2009 - 2012 | <a href="./privacy_policy.php">Privacy Policy</a> | Cont&aacute;ctenos <a href="mailto:erreauele@gmail.com">Send email</a>
        </footer>
    </body>
</html>
<?php
}

function redirectToPage($page){
    // print_r($_SESSION);
    header("Location: ./".$page);
    die;
}

function redirectToLastVisitedPage(){
    // print_r($_SESSION);
    if(isset($_SESSION['lastvisitedurl'])){
        header("Location: ".$_SESSION['lastvisitedurl']);
    }else{
        header("Location: ./index.php");
    }
    die;
}
//prueba list2
?>
