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
$sql_colab_all = "SELECT
                ID_Empleado
                , APELLIDO_PATERNO
                , APELLIDO_PATERNO
                , NOMBRES
                , DATE_FORMAT(FECHA_INGRESO, '%d/%m/%Y') FECHA_INGRESO
                , (YEAR(NOW()) - YEAR(FECHA_INGRESO)) DIF_ANIOS
                , P.descrip_puesto
                , IF(estatus = 0, 'Activo', 'Baja') Des_estatus
                , IF(estatus = 0, 'Bloquear', 'Activar') Action_estatus
                , estatus
                FROM ser_rh.colaborador C
                INNER JOIN puesto P ON C.Puesto = P.id_puesto;";

$res_colab = $mysqli->query($sql_colab_all);


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colaboradores</title>
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
<div class="row">
    <div class="col s12">
      <div class="row" style="margin-top: 15px;">
      <div class="col s8">
      <h4 style="color: #2d83a0; font-weight:bold;">Colaboradores</h4>
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
                <th class="center-align">Apellido Paterno</th>
                <th class="center-align">Apellido Materno</th>
                <th class="center-align">Nombres</th>
                <th class="center-align">Fecha de Ingreso</th>
                <th class="center-align">Antigüedad</th>
                <th class="center-align">Puesto</th>
                <th class="center-align">Estatus</th>
                <th colspan="2" class="center-align">Accciones</th>
              </tr>
            </thead>
            <body>
              <?php
              
                while($row_colab = mysqli_fetch_assoc($res_colab)){

                    $ID_Empleado = $row_colab['ID_Empleado'];
                    $estatus = $row_colab['estatus'];
                    if($estatus == 0){
                        $color_row = "white";
                        $color_button = "deep-orange darken-4";
                    }else{
                        $color_row = "yellow accent-1";
                        $color_button = "light-blue darken-2";
                    }

                  echo "<tr class=\"{$color_row}\">
                        <td class=\"center-align\">{$row_colab['APELLIDO_PATERNO']}</td>
                        <td class=\"center-align\">{$row_colab['APELLIDO_PATERNO']}</td>
                        <td class=\"center-align\">{$row_colab['NOMBRES']}</td>
                        <td class=\"center-align\">{$row_colab['FECHA_INGRESO']}</td>
                        <td class=\"center-align\">{$row_colab['DIF_ANIOS']}</td>
                        <td class=\"center-align\">{$row_colab['descrip_puesto']}</td>
                        <td class=\"center-align\">{$row_colab['Des_estatus']}</td>
                        <td class=\"center-align\"><a href=\"process_colab.php?ie={$ID_Empleado}&e={$estatus}&flagmod=modstatus\" class=\"waves-effect waves-light btn-small {$color_button}\">{$row_colab['Action_estatus']}</a></td>
                        <td class=\"center-align\"><a href=\"process_colab.php?ie={$ID_Empleado}&e=999&flagmod=ucol\" class=\"green darken-3 btn-small cyan darken-4\" target=\"_blank\">Actualizar</a></td>
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