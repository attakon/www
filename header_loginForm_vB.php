<form action="<?php echo $path?>/forum/login.php?do=login" method="post" onsubmit="md5hash(vb_login_password, vb_login_md5password, vb_login_md5password_utf, 0)">
    <script type="text/javascript" src="clientscript/vbulletin_md5.js?v=381"></script>
    <table cellpadding="0" cellspacing="3" border="0">
        <tr>
            <td class="smallfont" style="white-space: nowrap;"><label for="navbar_username">User Name</label></td>

            <td><input type="text" class="bginput" style="font-size: 11px" name="vb_login_username" id="navbar_username" size="10" accesskey="u" tabindex="101" value="User Name" onfocus="if (this.value == 'User Name') this.value = '';" /></td>
            <td class="smallfont" nowrap="nowrap"><label for="cb_cookieuser_navbar"><input type="checkbox" name="cookieuser" value="1" tabindex="103" id="cb_cookieuser_navbar" accesskey="c" />Remember Me?</label></td>
        </tr>
        <tr>
            <td class="smallfont"><label for="navbar_password">Password</label></td>
            <td><input type="password" class="bginput" style="font-size: 11px" name="vb_login_password" id="navbar_password" size="10" tabindex="102" /></td>
            <td><input type="submit" class="button" value="Log in" tabindex="104" title="Enter your username and password in the boxes provided to login, or click the 'register' button to create a profile for yourself." accesskey="s" /></td>
        </tr>

    </table>
    <input type="hidden" name="s" value="" />
    <input type="hidden" name="securitytoken" value="guest" />
    <input type="hidden" name="do" value="login" />
    <input type="hidden" name="vb_login_md5password" />
    <input type="hidden" name="vb_login_md5password_utf" />
</form>
