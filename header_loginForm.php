<form action="<?php echo $path?>/hclogin.php?do=login" method="post" onsubmit="md5hash(vb_login_password, vb_login_md5password, vb_login_md5password_utf, 0)"
      STYLE="margin: 0px; padding: 0px;
      font-size: 12px;
      font-family: Arial,Helvetica,sans-serif;">
    
    <label for="username">Usuario </label>
    <input type="text" style="font-size: 11px; font-family:verdana" name="vb_login_username" id="navbar_username" size="12" accesskey="u" tabindex="101" />

    <label for="txtClave">Password </label>
    <input type="password" style="font-size: 11px" name="vb_login_password" id="navbar_password" size="12" tabindex="102" />

    <input type="submit" size="50px" style="font-size: 11px" value="Entrar" tabindex="104" accesskey="s" />

    <a href="<?php echo $path?>/registration.php?user=&first_name=&last_name=&email=&school=&message=">
        Registrate
    </a>
    <input type="hidden" name="s" value="" />
    <input type="hidden" name="securitytoken" value="guest" />
    <input type="hidden" name="do" value="login" />
    <input type="hidden" name="vb_login_md5password" />
    <input type="hidden" name="vb_login_md5password_utf" />

</form>