<?php
include_once 'conexion.php';
function getForm($user, $first_name, $last_name, $email, $school, $message){
    if($message=='success'){
        return '<p class="successful">
         Cuenta creada Satisfactoriamente. Ahora es miembro de HuaHCoding!<br>
         y puede logearse con su usuario.
        </p>';
    }
    // else{

        // $rsSchools = fetchResultSet(conecDb(),
        //     "SELECT id_escuela, nombre from escuela ORDER BY 1 DESC");
        // $options = "<option value=0>Seleccione..</option>";
        // while($schoolRows = mysql_fetch_row($rsSchools)){            
        //     $options.="<option value=\"".$schoolRows[0]."\"";
        //     if($schoolRows[0]==$school){
        //         $options.=" selected";
        //     }
        //     $options.=">".$schoolRows[1]."</option>";
        // }        
    return '
    <form method="POST" action="register_submit_registration.php" class="form-horizontal"
        style="width:500px">
        <div class="control-group">
            <label class="control-label" for="username" >Username</label>
            <div class="controls">
                <input id="username" type="text" name="user" value="'.$user.'" maxlength="15" placeholder="Username">
                <span class="help-inline">Entre 3 y 15 caracteres</span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="pass">Password</label>
            <div class="controls">
                <input type="password" id="pass" name="pass" size="15" maxlength="15">
                <span class="help-inline">Entre 5 y 15 caracteres</span>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="repass">Confirm Password</label>
            <div class="controls">
                <input type="password" id="repass" name="repass" size="15" maxlength="15">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="first_name">Nombres</label>
            <div class="controls">
                <input type="text" id="first_name" name="first_name" value="'.$first_name.'" size="15" maxlength="15">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="last_name">Apellidos</label>
            <div class="controls">
                <input type="text" id="last_name" name="last_name" value="'.$last_name.'" size="15" maxlength="15">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="email">Email</label>
            <div class="controls">
                <input type="email" value="'.$email.'" id="email" name="email" size="15" maxlength="15">
            </div>
        </div>

        <hr/>
        <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" />
        <input type="text" name="captcha_code" size="10" maxlength="6" />
        <a href="#" onclick="document.getElementById(\'captcha\').src = \'securimage/securimage_show.php?\' + Math.random(); return false">Refresh</a>
        <hr/>
        <input class="btn btn-warning" type="submit" value="Registrarme" name="registrar">

    </form>';
}
?>