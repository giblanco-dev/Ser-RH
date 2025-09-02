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
      <li><a href="../logic/logout.php"><i class="material-icons right">close</i>Cerrar</a></li>
      </ul>
    </div>
  </nav>
 </div>
 </header>
<div class="row">
    <div class="col s2" style="margin-top: 3%;">
    <ul class="collection with-header">
        <li class="collection-header"><h5>Opciones</h5></li>
        <li class="collection-item"><div style="font-size: 14px;"><a href="colaborador.php"><span class="secondary-content"><i class="material-icons">person</i></span>Colaboradores</a></div></li>
        <li class="collection-item"><div style="font-size: 14px;"><a href="calendario.php"><span class="secondary-content"><i class="material-icons">perm_contact_calendar</i></span>Calendario</b></a></div></li>
        <!--li class="collection-item"><div style="font-size: 14px;"><span class="secondary-content"><i class="material-icons">perm_contact_calendar</i></span>Fecha de Ingreso: <b><?php //echo $fecha_ing; ?></b></div></li>
        <li class="collection-item"><div style="font-size: 14px;"><span class="secondary-content"><i class="material-icons">access_time</i></span>Antigüedad: <b><?php //echo $dif_years; ?></b></div></li>
        <li class="collection-item"><div style="font-size: 14px;"><span class="secondary-content"><i class="material-icons">card_travel</i></span>Días de Vacaciones <br> Período 2025: <b><?php //echo $days_asign; ?></b></div></li>
        <li class="collection-item"><div style="font-size: 14px;"><span class="secondary-content"><i class="material-icons">local_hotel</i></span>Usted puede solicitar sus<br>vacaciones en <b><?php //echo $leyenda_per_vac; ?></b></div></li-->
      </ul>
    </div>
    <div class="col s10">
        <h4 style="color: #2d83a0; font-weight:bold;">Gestión de Vacaciones Período 2025</h4>
        <div class="row">
            <div class="col s3">
            <a class="waves-effect waves-light btn"><b>Solicitudes Realizadas <?php echo "$solicitudes"; ?></b></a>
            </div>
            <div class="col s3">
            <a class="amber darken-1 btn"><b>Solicitudes Pendientes <?php echo "$sol_pend"; ?></b></a>
            </div>
            <div class="col s3">
            <a class="green darken-3 btn"><b>Solicitudes Aprobadas <?php echo "$sol_aprobadas"; ?></b></a>
            </div>
            <div class="col s3">
            <a class="red darken-2 btn"><b>Solicitudes Rechazadas <?php echo "$sol_re"; ?></b></a>
            </div>
            
        </div>
        <div class="row">
          <div class="col s12">
            <div class="table-responsive-2">
          <table style="font-size: 13px;" id="mytable">
            <thead>
              <tr>
                <th colspan="5">Periodos Vacionales Solicitados</th>
              </tr>
              <tr>
                <th>Fecha de Solicitud</th>
                <th>Colaborador</th>
                <th>Fecha Inicial</th>
                <th>Fecha Final</th>
                <th>Días Asignados</th>
                <th>Días Solicitados</th>
                <th>Fecha Solicitud</th>
                <th>Estatus</th>
                <th>Fecha Aprobación</th>
                <th colspan="2">Acciones</th>
              </tr>
            </thead>
            <body>
              <?php
              if($flag_sol == 1){
                while($row_solicitud = mysqli_fetch_assoc($res_solicitudes)){

                  $id_solicitud = $row_solicitud['id_solicitud'];
                  $puesto = $row_solicitud['Puesto'];

                  if($row_solicitud['estatus'] == 1){
                    //$op1 = '<a href="" class="blue darken-4 btn-small"><i class="material-icons right"></i>Formato</a>';
                    $op1 = '';
                    $op2 = '<a href="process/rechaza_vac.php?s='.$id_solicitud.'&f=d&o=1" class="red darken-2 btn-small"><i class="material-icons right"></i>Eliminar</a>';
                  }elseif($row_solicitud['estatus'] == 0){
                    $op1 = '<a href="process/aprob_vac.php?s='.$id_solicitud.'" class="green darken-3 btn-small"><i class="material-icons right"></i>Aprobar</a>';
                    $op2 = '<a href="process/rechaza_vac.php?s='.$id_solicitud.'&f=r&o=1" class="amber darken-1 btn-small"><i class="material-icons right"></i>Rechazar Solicitud</a>';
                  }elseif($row_solicitud['estatus'] == 2){
                    $op1 = '';
                    $op2 = '';
                  }

                  echo "<tr>
                        <td>{$row_solicitud['fecha_solicitud']}</td>
                        <td>{$row_solicitud['NOMBRE']}</td>
                        <td>{$row_solicitud['fecha_inicial']}</td>
                        <td>{$row_solicitud['fecha_final']}</td>
                        <td>{$row_solicitud['dias_asign']}</td>
                        <td>{$row_solicitud['dias_sol']}</td>
                        <td>{$row_solicitud['fecha_solicitud']}</td>
                        <td>{$row_solicitud['descrip_estatus']}</td>
                        <td>{$row_solicitud['fecha_aprob']}</td>
                        <td>{$op1}</td>
                        <td>{$op2}</td>
                      </tr>";
                }
                
              }else{
                echo "<tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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