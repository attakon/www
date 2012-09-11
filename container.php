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
<html xmlns:fb="http://ogp.me/ns/fb#">
    <?php 
        include_once("head_includes.php");//mandatory 
    ?>
    <body style="margin-right:13; margin-left:13; margin-top:0;padding-top:0;" >
           <!-- JS SDK -->
        <div id="fb-root"></div>
        <script>
          window.fbAsyncInit = function() {
            FB.init({
              appId      : '285185548248441',
              channelUrl : '/fb_plugin/channel.html', // Channel File
              status     : true, // check login status
              cookie     : true, // enable cookies to allow the server to access the session
              xfbml      : true  // parse XFBML
            });
            // Additional initialization code here
          };

          // Load the SDK Asynchronously
          (function(d){
             var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
             if (d.getElementById(id)) {return;}
             js = d.createElement('script'); js.id = id; js.async = true;
             js.src = "//connect.facebook.net/en_US/all.js";
             ref.parentNode.insertBefore(js, ref);
           }(document));
        </script>
    
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
