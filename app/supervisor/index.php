<?php 
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}elseif($_SESSION['nivel'] == 7){
            $id_user = $_SESSION['id'];
            $usuario = $_SESSION['name_usuario'];
            $nivel = $_SESSION['nivel'];
}else{
    header('Location: ../../index.php');
    exit();
}

require_once '../logic/conn.php';
require_once 'process/sol_vac.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="shortcut icon" href="../../static/img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../static/css/materialize.css">
    <link rel="stylesheet" href="../../static/icons/iconfont/material-icons.css">
    <script type="text/javascript" src="../../static/js/jquery-3.3.1.min.js"></script>
    <script src="../../static/js/materialize.js"></script>
    <style type="text/css"> 
        thead tr th { 
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #ffffff;
        }
    
        .table-responsive-2 { 
            height: 500px; /* Mover a 400 para demostrar el scroll*/
            overflow-y:scroll;
        }
    </style>
</head>
<body>
<header>
 <div class="navbar-fixed">
 <nav>
    <div class="nav-wrapper">
      <a href="#" class="responsive-img" class="brand-logo"><img src="../../static/img/logo.png" style="max-height: 80px; margin-left: 20px;"></a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
      <li><a href="../logic/logout.php"><i class="material-icons right">close</i>Cerrar Sistema</a></li>
      </ul>
    </div>
  </nav>
 </div>
 </header>
<div style="margin-bottom: 5%;">
<div class="row">
    <div class="col s4">    </div>
    <div class="col s8">
        <h4 style="color: #2d83a0; font-weight:bold;">Ser Recursos Humanos</h4>
    </div>
</div>
<div class="row">
  <div class="col s4">

        <ul class="collection with-header">
        <li class="collection-header"><h4>Configuraciones</h4></li>
        <li class="collection-item"><div style="font-size: 18px;">Colaboradores<a href="users.php" class="secondary-content"><i class="material-icons">person</i></a></div></li>
        <li class="collection-item"><div style="font-size: 18px;">Alta Colaborador<a href="users.php" class="secondary-content"><i class="material-icons">person_add</i></a></div></li>
      </ul>


  </div>

  <div class="col s8">
        <div class="row">

            <div class="col s6">
                <div class="card cyan darken-4 hoverable center-align">
                <div class="card-content white-text">
                <span class="card-title">Asistencia Colaboradores</span>
                <i class="medium material-icons">check_circle</i>
                </div>
                <div class="card-action">
                <a href="asistencia.php" class="grey-text text-lighten-2">Gestionar Asistencias</a>
                </div>
                </div>
            </div>

            <div class="col s6">
                <div class="card cyan darken-3 hoverable center-align">
                <div class="card-content white-text">
                <span class="card-title">Vacaciones</span>
                <i class="medium material-icons">local_airport</i>
                </div>
                <div class="card-action">
                <a href="vacaciones.php" class="grey-text text-lighten-2">Gestionar Vacaciones</a>
                </div>
                </div>
            </div>
        </div>

    </div>
</div>

</div>  <!-- Cierre del container -->
 <footer class="page-footer">
          <div class="container">
            <div class="row">
              <div class="col l6 s12 center-align">
                <h5 class="white-text">Usuario Activo <br><?php echo $usuario; ?></h5>
              </div>
              <div class="col l4 offset-l2 s12">
                <h5 class="white-text">Contacto</h5>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container">
            © 2025 Copyright
            </div>
          </div>
        </footer>
      
</body>
</html>