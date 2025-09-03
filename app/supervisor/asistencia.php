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
        <li><a href="index.php"><i class="material-icons right">home</i>Inicio</a></li>
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
        <h4 style="color: #2d83a0; font-weight:bold;">Asistencia Colaboradores</h4>
    </div>
</div>
<div class="row">
  <div class="col s3">

        <ul class="collection with-header">
        <li class="collection-header"><h4>Configuraciones</h4></li>
        <li class="collection-item"><div style="font-size: 18px;">Colaboradores<a href="users.php" class="secondary-content"><i class="material-icons">person</i></a></div></li>
        <li class="collection-item"><div style="font-size: 18px;">Alta Colaborador<a href="users.php" class="secondary-content"><i class="material-icons">person_add</i></a></div></li>
      </ul>


  </div>
  <div class="col s2"></div>      
  <div class="col s7">
        <div class="row">

            <div class="col s6">
                <blockquote><b>Generar reporte de asistencias</b></blockquote>
              <form action="process/reporte_asis.php" method="post">
                  <div class="row">
                        <div class="input-field col s12">
                        <input type="date" placeholder="Seleccione la fecha" name="fecha_ini" id = "fechaini" min="<?php echo $ini_cal ?>" max="<?php echo $fin_cal ?>" required>
                              <label for="fechaini">Fecha Inicial</label>
                      </div>
                      <div class="input-field col s12">
                      <input type="date" placeholder="Seleccione la fecha" name="fecha_fin" id = "fechafin" min="<?php echo $ini_cal ?>" max="<?php echo $fin_cal ?>" required>
                        <label for="fechafin">Fecha Final</label>
                      </div>
                      <div class="input-field col s12">
                        <button class="btn waves-effect waves-light" type="submit" name="action">Enviar
                          <i class="material-icons right">send</i>
                        </button>
                      </div>
                </div>
              </form>
            </div>

            <div class="col s6">
                
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