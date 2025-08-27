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

if(!empty($_GET)){

$colaborador = $_GET['i'];

require_once '../logic/conn.php';
require_once 'process/calc_dias_col.php';
require_once 'process/sol_vac_col.php';

if($puesto_col == 'Caja' or $puesto_col == 'Recepción'){
  $url = 'process/screate_sol_vac_cjrc.php';
}else{
  $url = 'process/screate_sol_vac_enf.php';
}

$sol_pend = $per_vac - $solicitudes;

if($sol_pend <= 0){
  $sol_pend = 0;
}else{
  $sol_pend = $sol_pend;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Colaborador</title>
    <link rel="shortcut icon" href="../../static/img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../static/css/materialize.css">
    <link rel="stylesheet" href="../../static/icons/iconfont/material-icons.css">
    <script type="text/javascript" src="../../static/js/jquery-3.3.1.min.js"></script>
    <script src="../../static/js/materialize.js"></script>
</head>
<body>
<header>
 <div class="navbar-fixed">
 <nav>
    <div class="nav-wrapper">
      <a href="#" class="responsive-img" class="brand-logo"><img src="../../static/img/logo.png" style="max-height: 80px; margin-left: 20px;"></a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
      <li><a href="colaborador.php"><i class="material-icons right">arrow_back</i>Regresar</a></li>
      <li><a href="../logic/logout.php"><i class="material-icons right">close</i>Cerrar</a></li>
      </ul>
    </div>
  </nav>
 </div>
 </header>
<div class="row">
    <div class="col s3" style="margin-top: 3%;">
    <ul class="collection with-header">
        <li class="collection-header"><h6><b>Datos del Colaborador</b></h6></li>
        <li class="collection-item"><div style="font-size: 14px;"><span class="secondary-content"><i class="material-icons">person</i></span><?php echo $nombre_col; ?></div></li>
        <li class="collection-item"><div style="font-size: 14px;"><span class="secondary-content"><i class="material-icons">change_history</i></span>Puesto: <b><?php echo $puesto_col; ?></b></div></li>
        <li class="collection-item"><div style="font-size: 14px;"><span class="secondary-content"><i class="material-icons">perm_contact_calendar</i></span>Fecha de Ingreso: <b><?php echo $fecha_ing; ?></b></div></li>
        <li class="collection-item"><div style="font-size: 14px;"><span class="secondary-content"><i class="material-icons">access_time</i></span>Antigüedad: <b><?php echo $dif_years; ?></b></div></li>
        <li class="collection-item"><div style="font-size: 14px;"><span class="secondary-content"><i class="material-icons">card_travel</i></span>Días de Vacaciones <br> Período 2025: <b><?php echo $days_asign; ?></b></div></li>
        <li class="collection-item"><div style="font-size: 14px;"><span class="secondary-content"><i class="material-icons">local_hotel</i></span>Periodos de vacaciones<br>permitidos <b><?php echo $leyenda_per_vac; ?></b></div></li>
        <li class="collection-item"><div style="font-size: 14px;"><span class="secondary-content"><i class="material-icons">vpn_key</i></span>Datos de acceso<br>Usuario: <b><?php echo $usuario_col; ?></b><br>Contraseña: <b><?php echo $pass_user_col; ?></b></div></li>
      </ul>
    </div>
    <div class="col s9">
        <div class="row">
            <div class="col s12">
                <h6 style="color: #2d83a0; font-weight:bold;">Gestión de Vacaciones Período 2025 Colaborador: <?php echo $nombre_col; ?></h6>
            </div>
        </div>
        <div class="row center-align">
            <div class="col s12">
                <a href="formato_vac.php?i=<?php echo $colaborador;?>" target="_blank" class="green darken-3 btn"><i class="material-icons right">picture_as_pdf</i>Generar Formato</a>
            </div>
        </div>

        <div class="divider"></div>
        <br>
        <div class="row center-align">
            <div class="col s4">
            <a class="waves-effect waves-light btn"><b>Días Otorgados <?php echo $days_asign; ?></b></a>
            </div>
            <div class="col s4">
            <a class="waves-effect waves-light btn"><b>Días Solicitados <?php echo $dias_sol; ?></b></a>
            </div>
            <div class="col s4">
            <a class="waves-effect waves-light btn"><b>Días Aprobados <?php echo $dias_aprobados; ?></b></a>
            </div>
        </div>
        <div class="row center-align">
            <div class="col s4">
            <a class="waves-effect waves-light btn"><b>Días Pendientes <?php echo $days_asign - $dias_sol; ?></b></a>
            </div>
            <div class="col s4">
            <a class="waves-effect waves-light btn"><b>Solicitudes Realizadas <?php echo $solicitudes; ?></b></a>
            </div>
            <div class="col s4">
            <a class="waves-effect waves-light btn"><b>Solicitudes Pendientes <?php echo $sol_pend; ?></b></a>
            </div>
        </div>
        <div class="row">
          <div class="col s12">
          <table>
            <thead>
              <tr>
                <th colspan="5">Periodos Vacionales Solicitados</th>
              </tr>
              <tr>
                <th>Fecha de Solicitud</th>
                <th>Fecha Inicial</th>
                <th>Fecha Final</th>
                <th>Días Solicitados</th>
                <th>Fecha Solicitud</th>
                <th>Estatus</th>
                <th>Fecha Aprobación</th>
                <th></th>
              </tr>
            </thead>
            <body>
              <?php 
              if($flag_sol == 1){
                while($row_solicitud = mysqli_fetch_assoc($res_solicitudes)){

                  if($row_solicitud['estatus'] == 1){
                    $fondo_tr = "green lighten-3";
                    $cancela = '<a href="process/rechaza_vac.php?s='.$row_solicitud['id_solicitud'].'&f=d&o=2" class="red darken-2 btn-small"><i class="material-icons right">cancel</i>Eliminar</a>';
                  }elseif($row_solicitud['estatus'] == 2){
                    $fondo_tr = "red lighten-3";
                    $cancela = '';
                  }else{
                    $fondo_tr = "";
                  }

                  echo "<tr class=\"{$fondo_tr}\">
                        <td>{$row_solicitud['fecha_solicitud']}</td>
                        <td>{$row_solicitud['fecha_inicial']}</td>
                        <td>{$row_solicitud['fecha_final']}</td>
                        <td>{$row_solicitud['dias_sol']}</td>
                        <td>{$row_solicitud['fecha_solicitud']}</td>
                        <td>{$row_solicitud['descrip_estatus']}</td>
                        <td>{$row_solicitud['fecha_aprob']}</td>
                        <td>{$cancela}</td>
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
        <div class="row">
            <?php 
            if($sol_pend == 0 AND $days_asign > $dias_sol){
              $sol_pend = $per_vac - $solicitudes;
              $days_pend = $days_asign - $dias_sol;
              
              $ini_cal = $min_calendar;
              $fin_cal = $max_calendar;
            

            ?>
            <div class="col s12">
              <blockquote>Asignar días de vacaciones restantes</blockquote>
              <form action="<?php echo $url; ?>" method="post">
                  <div class="row">
                        <div class="input-field col s4">
                        <input type="date" placeholder="Seleccione la fecha" name="fecha_ini" id = "fechaini" min="<?php echo $ini_cal ?>" max="<?php echo $fin_cal ?>" required>
                              <label for="fechaini">Fecha Inicial</label>
                      </div>
                      <div class="input-field col s4">
                      <input type="date" placeholder="Seleccione la fecha" name="fecha_fin" id = "fechafin" min="<?php echo $ini_cal ?>" max="<?php echo $fin_cal ?>" required>
                        <label for="fechafin">Fecha Final</label>
                      </div>
                      <div class="input-field col s4">
                        <button class="btn waves-effect waves-light" type="submit" name="action">Enviar
                          <i class="material-icons right">send</i>
                        </button>
                      </div>
                </div>
                  <!-- *** Se mandan parametros implicitos -->
                   <input type="hidden" name="id_colab" value="<?php echo $colaborador; ?>">
                   <input type="hidden" name="days_asign" value="<?php echo $days_asign; ?>">
                   <input type="hidden" name="days_pend" value="<?php echo $days_pend; ?>">
                   <input type="hidden" name="fecha_min" value="<?php echo $min_calendar; ?>">
                   <input type="hidden" name="fecha_max" value="<?php echo $max_calendar; ?>">
                   <input type="hidden" name="days_pend" value="<?php echo $days_pend; ?>">
                   <input type="hidden" name="per_vac" value="<?php echo $per_vac; ?>">
                   <input type="hidden" name="sol_pend" value="<?php echo $sol_pend; ?>">
                   

              </form>
            </div>
            <?php 
              }     // Cierra validación de solicitudes de vacaciones completas
            ?>
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
</body>
</html>

<?php   } ?>