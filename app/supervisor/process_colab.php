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

if(isset($_GET['flagmod'])){

    $sql_puesto = "SELECT id_puesto, descrip_puesto FROM puesto;";
    $res_puestos = $mysqli->query($sql_puesto);


    if($_GET['flagmod'] == 'ucol'){
        $titul_proceso = "Actualizar Colaborador";
        $ID_Empleado = $_GET['ie'];
        $sql_colab = "SELECT
                ID_Empleado
                , APELLIDO_PATERNO
                , APELLIDO_MATERNO
                , NOMBRES
                , FECHA_INGRESO 
                , Puesto
                , estatus
                FROM ser_rh.colaborador C
                INNER JOIN puesto P ON C.Puesto = P.id_puesto
                WHERE ID_Empleado = '$ID_Empleado';";

    $res_colab = $mysqli->query($sql_colab);
    $val_res_colab = $res_colab->num_rows;
    $row_colab = $res_colab->fetch_assoc();

    $apellido_p = $row_colab['APELLIDO_PATERNO'];
    $apellido_m = $row_colab['APELLIDO_MATERNO'];
    $nombres = $row_colab['NOMBRES'];
    $fecha_ingreso = $row_colab['FECHA_INGRESO'];
    $puesto = $row_colab['Puesto'];
    $estatus = $row_colab['estatus'];
        
    }elseif($_GET['flagmod'] == 'ncol'){

        $titul_proceso = "Nuevo Colaborador";
        $ID_Empleado = 'X';
        $apellido_p = '';
        $apellido_m = '';
        $nombres = '';
        $fecha_ingreso = '';
        $puesto = '';
        $estatus = 'X';

    }elseif($_GET['flagmod'] == 'modstatus'){
        $titul_proceso = "Inactivando colaborador";
        $ID_Empleado = $_GET['ie'];
        $estaus = $_GET['e'];
         
        if($estaus == 1){
            $new_estatus = 0;
        }else{
                $new_estatus = 1;
            }

        $sql_colab = "SELECT
                ID_Empleado
                , NOMBRE_COMPLETO
                , P.descrip_puesto
                , estatus
                FROM ser_rh.colaborador C
                INNER JOIN puesto P ON C.Puesto = P.id_puesto
                WHERE ID_Empleado = '$ID_Empleado';";

    $res_colab = $mysqli->query($sql_colab);
    $val_res_colab = $res_colab->num_rows;
    $row_colab = $res_colab->fetch_assoc();
    
        if($val_res_colab == 1){
            $nombre_col = $row_colab['NOMBRE_COMPLETO'];
            $puesto = $row_colab['descrip_puesto'];

            $sql_upd_estatus = "UPDATE colaborador
                                SET estatus = {$new_estatus}
                                WHERE ID_Empleado = {$ID_Empleado};";
            $res_upd_estatus = $mysqli->query($sql_upd_estatus);

            if($res_upd_estatus){
                $titul_proceso = "Estatus del colaborador <br> {$nombre_col}  <br>({$puesto}) <br><br>Actualizado correctamente";
                
            }else{
                $titul_proceso = "Error al actualizar el estatus del colaborador {$nombre_col} ({$puesto}) <br> Por favor de contacté al administrador del sistema";
                
            }    
        }



    }

    


}
    

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesando Colaborador</title>
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
      <li><a href="colaboradores.php"><i class="material-icons right">person</i>Colaboradores</a></li>
      <li><a href="../logic/logout.php"><i class="material-icons right">close</i>Cerrar Sistema</a></li>
      </ul>
    </div>
  </nav>
 </div>
 </header>
<div style="margin-bottom: 5%;">
<div class="row">
    
    <div class="col s12 center-align">
        <h4 style="color: #2d83a0; font-weight:bold;"><?php echo $titul_proceso; ?></h4>
    </div>
    
</div>
<div class="row">
  <div class="col s1">
  </div>
        
  <div class="col s10">
        <div class="row">
            <?php 
            if($_GET['flagmod'] == 'ucol' or $_GET['flagmod'] == 'ncol'){
                ?>
            <form action="process/colab_process.php" method="POST">
                      <div class="input-field col s4">
                            <input type="text" placeholder="" name="apaterno" id = "apaterno" value="<?php echo $apellido_p; ?>" required>
                              <label for="apaterno">Apellido Paterno</label>
                      </div>
                      <div class="input-field col s4">
                            <input type="text" placeholder="" name="amaterno" id = "amaterno" value="<?php echo $apellido_m; ?>" required>
                              <label for="amaterno">Apellido Materno</label>
                      </div>
                      <div class="input-field col s4">
                            <input type="text" placeholder="" name="nombres" id = "nombres" value="<?php echo $nombres; ?>" required>
                              <label for="fechaini">Nombre(s)</label>
                      </div>

                      <div class="input-field col s4" style="margin-top: 3%;">
                        <input type="date" placeholder="Seleccione la fecha" name="fecha_in" value="<?php echo $fecha_ingreso; ?>" required>
                              <label for="fechain">Fecha de ingreso</label>
                      </div>
                      <div class="input-field col s4" style="margin-top: 3%;">
                        <select name="puesto">
                            <?php 
                            while($row_puesto = $res_puestos->fetch_assoc()){
                                $id_puesto = $row_puesto['id_puesto'];
                                $descrip_puesto = $row_puesto['descrip_puesto'];
                                if($id_puesto == $puesto){
                                    echo "<option value=\"{$id_puesto}\" selected>{$descrip_puesto}</option>";
                                }else{
                                    echo "<option value=\"{$id_puesto}\">{$descrip_puesto}</option>";
                                }
                            }
                            ?>
                            </select>
                            <label>Seleccione el puesto</label>
                      </div>
                      <div class="input-field col s4" style="margin-top: 3%;">
                        <select name="estatus">
                            <?php 
                                if($estatus == 0){
                                    echo '<option value="0" selected>Activo</option>
                                          <option value="1">Bloquear</option>';
                                }elseif($estatus == 1){
                                    echo '<option value="0">Activo</option>
                                          <option value="1" selected>Bloquear</option>';
                                }else{
                                    echo '<option value="0" selected>Activo</option>
                                          <option value="1">Bloquear</option>';
                                }
                                ?>
                            </select>
                            <label>Seleccione el estatus</label>
                      </div>
                       <div class="input-field col s12 center-align" style="margin-top: 3%;">
                        <button class="btn waves-effect waves-light" type="submit" name="action">Guardar información
                          <i class="material-icons right">save</i>
                        </button>
                      </div>
                            <input type="hidden" name="id_emp" value="<?php echo $ID_Empleado; ?>">
                            <input type="hidden" name="flag_mod" value="<?php echo $_GET['flagmod']; ?>">

                      </form>

            <?php 
            } // cierre formulario colaborador
            ?>
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
        <script>
            $(document).ready(function(){
                $('select').formSelect();
            });
            </script>
</body>
</html>