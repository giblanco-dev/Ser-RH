<?php
require('conn.php');
$usuario = mysqli_real_escape_string($mysqli,$_GET['usuario']);
$nombre = $_GET['nombre'];

if(!empty($_POST))
{
    $p1 = $_POST['pass2'];
    $p2 = $_POST['pass_confirm'];

    if($p1 != $p2){
      echo '<script type="text/javascript">alert("Las contraseñas no coinciden, intente nuevamente");window.location.href="#"</script>';
    }
    else{
    $nvo_pass = mysqli_real_escape_string($mysqli,$_POST['pass2']);
    //$id_user = $_POST['user'];
    $sql = "UPDATE user SET user.password = '$nvo_pass'  WHERE id = '$usuario'";

        if ($mysqli->query($sql) === TRUE) {            
            echo '<script type="text/javascript">alert("La contraseña se ha actualizado correctamente ingrese con sus nuevas credenciales");window.location.href="../../"</script>';
        } else {
            echo '<script type="text/javascript">alert("Ha ocurrido un error, intente nuevamente \n , de lo contrario contacte con el administrador");window.location.href="../../"</script>';
        }
      }
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../static/img/favicon.png" type="image/x-icon">
    <title>Password Reset</title>
    <link rel="stylesheet" href="../../static/css/materialize.css">
    <link href="../static/icons/iconfont/material-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../static/css/main.css">
    <script src="../../static/js/materialize.js"></script>
    <script type="text/javascript" src="../../static/js/jquery-3.3.1.min.js"></script>
</head>
<body style="background-image: url('../../static/img/background_login.png'); background-size: cover;">
    <div class="container">
        <div class="row" style="margin-top: 1%;">
        <div class="col s12 center-align">
        <img src="../../static/img/banner_login.png" class="responsive-img z-depth-5">
        </div>
        </div>
        <div class="row" style="margin-top: 1%;">
        <div class="col s4 offset-s4 grey lighten-3 center-align">
        <div class="divider"></div>
        <h5><?php echo $nombre?> actualiza tu contraseña</h5>
        <div class="row">
        <form action="<?php $_SERVER['PHP_SELF']; ?>" id="form1" name="form1" method="POST" class="col s12">
        <div class="row">
        <div class="input-field col s12">
            <i class="material-icons prefix">vpn_key</i>
            <input id="password" type="password" name="pass2" class="validate" required minlength="5" maxlength="8">
            <label for="password">Ingresa tu nueva contraseña</label>
            </div>
            <div class="input-field col s12">
            <i class="material-icons prefix">vpn_key</i>
            <input id="password" type="password" name="pass_confirm" class="validate" required minlength="5" maxlength="8">
            <label for="password">Confirma tu contraseña</label>
            <input type="hidden" value="<?php echo $usuario; ?>" name="user">
            </div>
            <div class="col s12">
                <button class="btn" style="background-color: #2d83a0;" type="submit" name="action">Actualizar
                    <i class="material-icons right">system_update_alt</i>
                </button>
            </div>
        </div>
        </form>
        </div>
        <br>
        <p style="margin-bottom: 18px;" >© Copyright 2020</p>
    </div>
        </div>
    </div>
</body>
</html>