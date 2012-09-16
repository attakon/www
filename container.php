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

    <!-- begin olark code --><script data-cfasync="false" type='text/javascript'>/*{literal}<![CDATA[*/
    window.olark||(function(c){var f=window,d=document,l=f.location.protocol=="https:"?"https:":"http:",z=c.name,r="load";var nt=function(){f[z]=function(){(a.s=a.s||[]).push(arguments)};var a=f[z]._={},q=c.methods.length;while(q--){(function(n){f[z][n]=function(){f[z]("call",n,arguments)}})(c.methods[q])}a.l=c.loader;a.i=nt;a.p={0:+new Date};a.P=function(u){a.p[u]=new Date-a.p[0]};function s(){a.P(r);f[z](r)}f.addEventListener?f.addEventListener(r,s,false):f.attachEvent("on"+r,s);var ld=function(){function p(hd){hd="head";return["<",hd,"></",hd,"><",i,' onl' + 'oad="var d=',g,";d.getElementsByTagName('head')[0].",j,"(d.",h,"('script')).",k,"='",l,"//",a.l,"'",'"',"></",i,">"].join("")}var i="body",m=d[i];if(!m){return setTimeout(ld,100)}a.P(1);var j="appendChild",h="createElement",k="src",n=d[h]("div"),v=n[j](d[h](z)),b=d[h]("iframe"),g="document",e="domain",o;n.style.display="none";m.insertBefore(n,m.firstChild).id=z;b.frameBorder="0";b.id=z+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){b.src="javascript:false"}b.allowTransparency="true";v[j](b);try{b.contentWindow[g].open()}catch(w){c[e]=d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{var t=b.contentWindow[g];t.write(p());t.close()}catch(x){b[k]=o+'d.write("'+p().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}a.P(2)};ld()};nt()})({loader: "static.olark.com/jsclient/loader0.js",name:"olark",methods:["configure","extend","declare","identify"]});
    /* custom configuration goes here (www.olark.com/documentation) */
    olark.identify('2853-775-10-5782');/*]]>{/literal}*/</script><noscript><a href="https://www.olark.com/site/2853-775-10-5782/contact" title="Contact us" target="_blank">Questions? Feedback?</a> powered by <a href="http://www.olark.com?welcome" title="Olark live chat software">Olark live chat software</a></noscript>
    <!-- end olark code -->
    </body>
</html>
<?php
}
?>
