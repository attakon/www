<form action="<?php echo $path?>/hclogin.php?do=login" method="post" onsubmit="md5hash(vb_login_password, vb_login_md5password, vb_login_md5password_utf, 0)"
      STYLE="margin: 0px; padding: 0px;
      font-size: 12px;
      font-family: Arial,Helvetica,sans-serif;">
    


    <!-- Get an arbitrary name
    <fb:login-button 
      width="200" 
      max-rows="1" 
      next="http://huahcoding.com/">
    </fb:login-button>

    <div class="fb-login-button"
      data-width="200" data-max-rows="1">
      Login
    </div>
    -->

  
    <!-- Get an arbitrary name
    <fb:name uid="100001796235893" capitalize="true" /> 
     -->


    <div>
        <!-- Disabling FB Login -->
        <!-- <a class="fblogin" onclick="fblogin()"/></a> &oacute; -->
        <input class="loginfield"
          placeholder="Username" 
          type="text" 
          style="font-size: 11px; font-family:verdana;" 
          name="vb_login_username" 
          id="navbar_username" size="12" accesskey="u" tabindex="101" />
        <!-- <label for="txtClave">Password </label> -->
        <input class="loginfield"
          placeholder="Password" 
          type="password" 
          style="font-size: 11px; font-family:verdana;" 
          name="vb_login_password" id="navbar_password" size="12" tabindex="102" />

        <input class="loginfield"
         type="submit" size="50px" 
         style="font-size: 11px" 
         value="Entrar" 
         tabindex="104" 
         accesskey="s" 
        />

        <a
        href="<?php echo $path?>/register.php">
            Registrate
        </a>
        <input type="hidden" name="s" value="" />
        <input type="hidden" name="securitytoken" value="guest" />
        <input type="hidden" name="do" value="login" />
        <input type="hidden" name="vb_login_md5password" />
        <input type="hidden" name="vb_login_md5password_utf" />
    </div>
    


</form>