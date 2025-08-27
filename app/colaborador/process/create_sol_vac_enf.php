<?php 
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}elseif($_SESSION['nivel'] != 'Supervisor'){
            $id_user = $_SESSION['id'];
            $usuario = $_SESSION['name_usuario'];
            $nivel = $_SESSION['nivel'];
}else{
    header('Location: ../index.php');
    exit();
}

if(!empty($_POST)){

require_once '../../logic/conn.php';

    $id_colab = $_POST['id_colab'];
    $days_asign = $_POST['days_asign'];
    $fecha_ini = $_POST['fecha_ini'];
    $fecha_fin = $_POST['fecha_fin'];
    $fecha_min = $_POST['fecha_min'];
    $fecha_max = $_POST['fecha_max'];
    $days_pend = $_POST['days_pend'];
    $per_vac = $_POST['per_vac'];
    $sol_pend = $_POST['sol_pend'];
    $fecha_ini_format = date("d/m/Y", strtotime($fecha_ini));
    $fecha_fin_format = date("d/m/Y", strtotime($fecha_fin));

    /*$date1 = date_create($fecha_ini);
    $date2 = date_create($fecha_fin);
    $interval = date_diff($date1, $date2);
    $dias_sol = $interval->format('%a') + 1;*/

    // Se obtienen días solicitados, descartando domingos y días festivos

    $sql_dias_sol = "SELECT SUM(Contador_Vac) Contador FROM calendario where Fecha BETWEEN '$fecha_ini' and '$fecha_fin' LIMIT 1;";
    $resul_dias_sol = $mysqli->query($sql_dias_sol);
    $row_dias_sol = mysqli_fetch_assoc($resul_dias_sol);
    $dias_sol = $row_dias_sol['Contador'];

    $sql_val_calendar = "SELECT DATE_FORMAT(fecha, '%d/%m/%Y') dias_ocupados  FROM calendario WHERE fecha BETWEEN '$fecha_ini' AND '$fecha_fin' AND id_colaborador IS NOT NULL;";
    $res_val_calendar = $mysqli->query($sql_val_calendar);
    $val_calendar = $res_val_calendar->num_rows;

    if($val_calendar == 0){
            
    $flag_dias_ocupados = 0;
    

// *************** Ingresa bloque de insercción de solicitudes cuando tienen derecho a un solo periodo de vacaciones

    if($sol_pend > 0 and $per_vac > 1){

        if($days_pend >= $dias_sol){

            $sql_update_calendar = "UPDATE calendario SET id_colaborador = $id_colab, fecha_solicitud = NOW() WHERE fecha BETWEEN '$fecha_ini' AND '$fecha_fin';";
            if($mysqli->query($sql_update_calendar) === True){
                
                $sql_load_sol = "INSERT INTO sol_vacaciones(id_empleado, dias_asign, fecha_ini, fecha_fin, 
                            fecha_min, fecha_max, dias_sol, estatus) 
                            VALUES ($id_colab, $days_pend, '$fecha_ini','$fecha_fin','$fecha_min','$fecha_max','$dias_sol',0);";
                if($mysqli->query($sql_load_sol) === True){

                    $title = "La solicitud de vacaciones fue registrada por {$dias_sol} días";
                    $text = "Periodo registrado del {$fecha_ini_format} al {$fecha_fin_format}";
                    $icon = "success";

                }else{  // Error carga de solicitud 
                    $title = "No se pudo guardar su solicitud";
                    $text = "Por favor notificar a Sistemas";
                    $icon = "error";

                }

            }else{  // Error ejecución actualización de calendario solicitud
                $title = "No se pudo actualizar el calendario";
                $text = "Por favor notificar a Sistemas";
                $icon = "error";

            }

        }else{ // Error días sobrepasados
            $title = "No se pudo guardar su solicitud";
            $text = "Usted cuenta con {$days_pend} días de vacaciones y ha solicitado {$dias_sol} días";
            $icon = "warning";
        }

    }elseif($sol_pend > 0 and $per_vac == 1){

        if($days_pend == $dias_sol){

            $sql_update_calendar = "UPDATE calendario SET id_colaborador = $id_colab, fecha_solicitud = NOW() WHERE fecha BETWEEN '$fecha_ini' AND '$fecha_fin';";
            if($mysqli->query($sql_update_calendar) === True){
                
                $sql_load_sol = "INSERT INTO sol_vacaciones(id_empleado, dias_asign, fecha_ini, fecha_fin, 
                            fecha_min, fecha_max, dias_sol, estatus) 
                            VALUES ($id_colab, $days_pend, '$fecha_ini','$fecha_fin','$fecha_min','$fecha_max','$dias_sol',0);";
                if($mysqli->query($sql_load_sol) === True){

                    $title = "La solicitud de vacaciones fue registrada por {$dias_sol} días";
                    $text = "Periodo registrado del {$fecha_ini_format} al {$fecha_fin_format}";
                    $icon = "success";

                }else{  // Error carga de solicitud 
                    $title = "No se pudo guardar su solicitud";
                    $text = "Por favor notificar a Sistemas";
                    $icon = "error";

                }

                }else{  // Error ejecución actualización de calendario solicitud
                    $title = "No se pudo actualizar el calendario";
                    $text = "Por favor notificar a Sistemas";
                    $icon = "error";

                }

            }else{ // Error días sobrepasados
                $title = "No se pudo guardar su solicitud";
                $text = "Usted cuenta con {$days_pend} días de vacaciones y ha solicitado {$dias_sol} días";
                $icon = "warning";
            }
        

    }else{
                $title = "No se pudo guardar su solicitud";
                $text = "Por favor notificar a Sistemas (Conflicto de periodos vacacionales y solicitudes pendientes";
                $icon = "error";
    }
    
}else{
    $flag_dias_ocupados = 1;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envío de Solicitud de Vacaciones</title>
    <link rel="shortcut icon" href="../../../static/img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../../static/css/materialize.css">
    <script type="text/javascript" src="../../../static/js/jquery-3.3.1.min.js"></script>
    <script src="../../../static/js/sweetalert.min.js"></script>
</head>
<body style="background-image: url('../../../static/img/background_login.png'); background-size: cover;">

    
    
<?php
if($flag_dias_ocupados == 1){
?>
<div class="container">
    <div class="row center-align" style="background-color: #FFF; margin-top: 5%;">
        <div class="col s12">
            <h5>Los siguientes días seleccionados ya se encuentran solicitados por tus compañer@s</h5>
            <div class="divider"></div>
            <br>
                <a href="../" class="waves-effect waves-light btn"><i class="material-icons right">keyboard_backspace</i>Regresar</a>
            <br>
                
            <table>
                <thead>
                <tr>
                    <th class="center-align">Día</th>
                </tr>
                </thead>
                <tbody>
                    <?php 
                while($row_day_oc = mysqli_fetch_assoc($res_val_calendar)){
                    echo "
                    <tr>
                        <td class='center-align'/>{$row_day_oc['dias_ocupados']}</td>
                    </tr>                    
                    ";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php  // Cierre despliegue de días ocupados 
     }else{

        echo '<script type="text/javascript">
                swal({
                    title: "'.$title.'",
                    text: "'.$text.'",
                    icon: "'.$icon.'",
                    button: "Regresar",
                }).then(function() {
                    window.location = "../";
                });
                </script>';
    
} 
?>
    
</body>
</html>

<?php
} // Cierra validación de datos por POST
?>