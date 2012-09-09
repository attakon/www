<?php
include_once 'conexion.php';;
function getForm2($user, $first_name, $last_name, $email, $school, $message){
    if($message=='success'){
        return '<p class="successful">
         Cuenta creada Satisfactoriamente. Ahora es miembro de HuaHCoding!<br>
         y puede logearse con su usuario.
        </p>';
    }else{

        $rsSchools = fetchResultSet(conecDb(),
            "SELECT id_escuela, nombre from escuela ORDER BY 1 DESC");
        $options = "<option value=0>Seleccione..</option>";
        while($schoolRows = mysql_fetch_row($rsSchools)){            
            $options.="<option value=\"".$schoolRows[0]."\"";
            if($schoolRows[0]==$school){
                $options.=" selected";
            }
            $options.=">".$schoolRows[1]."</option>";
        }        
        return '
    <table align =center class="reg" style="border-collapse: collapse">
        <form method="GET" action="rC_register.php">
        <tr>
            <td class="left" >&nbsp;</td>
            <td class="middle" width="350" height="30" colspan="2">
                <label class="message">'.$message .'</label>
            </td>
            <td class="right">&nbsp;</td>
        </tr>
        <tr>
            <td class = "LeftFill"></td>
            <td class="regLabel">Usuario:</td>
            <td class="regField">
                <input type="text" name="user" value="'.$user.'" size="15" maxlength="15">
                <label class="comment">Entre 3 y 15 caracteres, sin teamtag</label>
            </td>
            <td class="RightFill"></td>
        </tr>
        <tr>
            <td class="LeftFill"></td>
            <td class="regLabel">Password:</td>
            <td class="regField">
                <input type="password" name="pass" size="15" maxlength="15">
                <label class="comment">Entre 5 y 15 caracteres</label>
            </td >
            <td class="RightFill" ></td>
        </tr>
        <tr>
            <td class="LeftFill"></td>
            <td class="regLabel">Confirmar Password:</td>
            <td class="regField">
                <input type="password" name="repass" size="15" maxlength="15">
            </td >
            <td class="RightFill"></td>
        </tr>
        <tr>
            <td class="LeftFill"></td>
            <td class="regLabel">Nombres:</td>
            <td class="regField">
                <input type="text" name="first_name"  value="'.$first_name.'"size="35">
            </td >
            <td class="RightFill"></td>
        </tr>
        <tr>
            <td class="LeftFill"></td>
            <td class="regLabel">Apellidos:</td>
            <td class="regField">
                <input type="text" name="last_name"  value="'.$last_name.'"size="35">
            </td ><td class="RightFill"></td>
        </tr>
        <tr>
            <td class="LeftFill"></td>
            <td class="regLabel">E-mail:</td>
            <td class="regField">
                <input type="text" name="email"  value="'.$email.'"size="35">
            </td >
            <td class="RightFill"></td>
        </tr>
        <tr>
            <td class="LeftFill"></td>
            <td class="regLabel">Escuela/Instituto:</td>
            <td class="regField">                
                <select name = "school">
                    '.$options.'
                </select>
            </td >
            <td class="RightFill"></td>
        </tr>
        <tr>
            <td class="LeftFill"></td>
            <td align="center" colspan="2" height="30">
                Ingrese esto:<br/>
                <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" />
                <br/>
                Aqui:
                <input type="text" name="captcha_code" size="10" maxlength="6" /><br />
                <a href="#" onclick="document.getElementById(\'captcha\').src = \'securimage/securimage_show.php?\' + Math.random(); return false">Otra</a>
            </td>
            <td class="RightFill"> </td>
        </tr>
        <tr>
            <td class="LeftFill"></td>
            <td  colspan="2" height="30">
            <p align = "center">
                <input type="submit" value="Registrar" name="registrar">
            </p>
            </td>
            <td class="RightFill"></td>
        </tr>
        </form>
    </table>
';
    }
}
?>