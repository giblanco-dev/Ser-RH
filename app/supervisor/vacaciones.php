<?php 
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}elseif($_SESSION['nivel'] == 'Supervisor'){
            $id_user = $_SESSION['id'];
            $usuario = $_SESSION['name_usuario'];
            $nivel = $_SESSION['nivel'];
}else{
    header('Location: ../../index.php');
    exit();
}

require_once '../logic/conn.php';
require_once 'process/resum_colab.php';

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
      <li><a href="calendario_vac.php"><i class="material-icons right">perm_contact_calendar</i>Calendario Vacaciones</a></li>
      <li><a href="#"><i class="material-icons right">person</i>Colaboradores</a></li>  
      <li><a href="../logic/logout.php"><i class="material-icons right">close</i>Cerrar Sistema</a></li>
      </ul>
    </div>
  </nav>
 </div>
 </header>
<div class="row">
    <div class="col s12">
      <div class="row" style="margin-top: 15px;">
      <div class="col s8">
      <h4 style="color: #2d83a0; font-weight:bold;">Vacaciones Colaboradores</h4>
      </div>
      <div class="input-field col s3">
                            <i class="material-icons prefix">search</i>
                            <input id="search" type="text" class="validate" autocomplete="off" >
                            <label for="search">Buscar colaborador</label>
                            </div>
      </div>
      <div class="col s1"></div>
        
        
        <div class="row">
          <div class="col s12">
            <div class="table-responsive-2">
          <table style="font-size: 12px;" id="mytable">
            <thead>
              <tr>
                <th class="center-align">Colaborador</th>
                <th class="center-align">Posición</th>
                <th class="center-align">Fecha<br>Ingreso</th>
                <th class="center-align">Antigüedad</th>
                <th class="center-align">Días de Vacaciones<br>Asignados</th>
                <th class="center-align">Solicitudes<br>Aprobadas</th>
                <th class="center-align">Días<br>Aprobados</th>
                <th class="center-align">Días<br>Pendientes</th>
                <th colspan="2">Accciones</th>
              </tr>
            </thead>
            <body>
              <?php
              
                while($row_colab = mysqli_fetch_assoc($res_colab)){

                    $ID_Empleado = $row_colab['ID_Empleado'];

                    $days_asign = $row_colab['DIAS_ASIGN'];
                    switch (true) {
                      case ($days_asign <= 12):
                      $per_vac = 1;
                      break;
                      case ($days_asign > 12 && $days_asign <= 20):
                      $per_vac = 2;
                      break;
                      case ($days_asign > 20):
                      $per_vac = 3;
                      break;
                    }

                    $days_pend = $days_asign - $row_colab['Dias_Soli'];
                    
                  echo "<tr>
                        <td class=\"center-align\">{$row_colab['NOMBRE']}</td>
                        <td class=\"center-align\">{$row_colab['Puesto']}</td>
                        <td class=\"center-align\">{$row_colab['FECHA_INGRESO2']}</td>
                        <td class=\"center-align\">{$row_colab['DIF_ANIOS']}</td>
                        <td class=\"center-align\">{$row_colab['DIAS_ASIGN']}</td>
                        <td class=\"center-align\">{$row_colab['Solicitudes_Aprob']}</td>
                        <td class=\"center-align\">{$row_colab['Dias_aprob']}</td>
                        <td class=\"center-align\">{$days_pend}</td>
                        <td><a href=\"detalle_vaccolab.php?i={$ID_Empleado}\" class=\"waves-effect waves-light btn-small\">Gestionar</a></td>
                        <td><a href=\"formato_vac.php?i={$ID_Empleado}\" class=\"green darken-3 btn-small\" target=\"_blank\">Formato</a></td>
                      </tr>";
                }
              
              ?>
            </body>
          </table>
          </div>
          </div>
        </div>
      
    </div>
</div>
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

        
              <script>
 // Write on keyup event of keyword input element
 $(document).ready(function(){
 $("#search").keyup(function(){
 _this = this;
 // Show only matching TR, hide rest of them
 $.each($("#mytable tbody tr"), function() {
 if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
 $(this).hide();
 else
 $(this).show();
 });
 });
});
</script>
</body>
</html>