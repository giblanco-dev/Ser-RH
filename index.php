<?php
$message_error = '';
if(isset($_GET['error'])){
    $error = $_GET['error'];
    switch($error){
      case 1:
        $message_error = '<p style="color: red; font-weight:bold;">Datos incorrectos, verifique sus credenciales<br>
        Si no cuenta con sus datos de acceso pongase en contacto con el administrador del sistema</b></p>';
      break;
      case 2:
        $message_error = 'Usted no ha iniciado sesión';
      break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenidos</title>
    <link rel="shortcut icon" href="static/img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="static/css/materialize.css">
    <link rel="stylesheet" href="static/icons/iconfont/material-icons.css">
    <script src="static/js/materialize.js"></script>
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
</head>
<body style="background-image: url('static/img/background_login.png'); background-size: cover;">
    <div class="container">
        <div class="row" style="margin-top: 1%;">
        <div class="col s12 center-align">
        <img src="static/img/banner_login.png" class="responsive-img z-depth-5">
        </div>
        </div>
        <div class="row" style="margin-top: 1%; width:40%;">
        <div class="col s12 grey lighten-3 center-align">
        <div class="divider"></div>
        <h4>Gestión de Vacaciones</h4>
        <hr>
        <h5>Iniciar Sesión</h5>
        <div class="row">
        <form action="app/logic/session.php" class="col s12"  method="POST">
        <div class="row">
            <div class="input-field col s12">
            <i class="material-icons prefix">account_circle</i>
            <input id="icon_prefix" type="text" name="user" class="validate" required>
            <label for="icon_prefix">Usuario</label>
            </div>
            <div class="input-field col s12">
            <i class="material-icons prefix">vpn_key</i>
            <input id="password" type="password" name="password" class="validate" required>
            <label for="password">Password</label>
            </div>
            <div class="col s12">
                <button class="btn" style="background-color: #2d83a0;" type="submit" name="action">Acceder
                    <i class="material-icons right">send</i>
                </button>
                <?php echo $message_error; ?>
            </div>
        </div>
        </form>
        </div>
        <blockquote>
            ¿Olvidaste tu contraseña? Ponte en contacto con el Administrador del Sistema
        </blockquote>
        <br>
        <p style="margin-bottom: 18px;" >© Copyright 2025</p>
    </div>
        </div>
    </div>
</body>
</html>