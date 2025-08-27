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
    header('Location: ../index.php');
    exit();
}

if(!empty($_GET)){

require_once '../../logic/conn.php';

    $solicitud = $_GET['s'];
    $flag = $_GET['f'];
    $or = $_GET['o'];

    

    // Se recupera información a eliminar y a actualizar

    $sql_sol_del = "SELECT id_solicitud, fecha_ini, fecha_fin, SO.id_empleado, CO.Puesto 
                        FROM sol_vacaciones SO
                        INNER JOIN colaborador CO ON SO.id_empleado = CO.ID_Empleado
                        where id_solicitud = $solicitud;";
    $res_sol_del = $mysqli->query($sql_sol_del);
    $val_sol_del = $res_sol_del->num_rows;

    if($val_sol_del == 1){

        $row_solicitud = mysqli_fetch_assoc($res_sol_del);

        $id_empleado = $row_solicitud['id_empleado'];
        $fecha_ini = $row_solicitud['fecha_ini'];
        $fecha_fin = $row_solicitud['fecha_fin'];
        $puesto = $row_solicitud['Puesto'];

        if($puesto == 'Caja' or $nivel == 'Recepción'){
            $empleado = $row_solicitud['id_empleado'].',';
            $sql_upd_calendar = "UPDATE calendario SET id_colaboradorB = REPLACE(id_colaboradorB, '$empleado', '') where Fecha BETWEEN '$fecha_ini' AND '$fecha_fin';";
        }else{
            $empleado = $row_solicitud['id_empleado'];
            $sql_upd_calendar = "UPDATE calendario SET id_colaborador = NULL where id_colaborador = $empleado AND (Fecha BETWEEN '$fecha_ini' AND '$fecha_fin');";
        }
        
    if($mysqli->query($sql_upd_calendar) === True){

            if($flag == 'r'){
                $sql_del_sol = "UPDATE sol_vacaciones SET estatus = 2, id_sup_aprueba = $id_user, aprobado = NOW()  where id_solicitud = $solicitud and estatus = 0;";

                if($mysqli->query($sql_del_sol) === True){
                        $title = "La solicitud {$solicitud} fue rechazada";
                        $text = "Validar días pendientes del colaborador";
                        $icon = "success";
                }
            }elseif($flag == 'd'){
                $sql_del_sol = "DELETE FROM sol_vacaciones where id_solicitud = $solicitud and estatus = 1;";

                if($mysqli->query($sql_del_sol) === True){
                        $title = "La solicitud {$solicitud} fue eliminada";
                        $text = "Validar días pendientes del colaborador";
                        $icon = "success";
                }
            }

        }else{
            $title = "Error al actualizar calendario de la solicitud {$solicitud}";
            $text = "Periodo que no se actualizo {$fecha_ini} al {$fecha_fin}";
            $icon = "warning";
        }

    }elseif($val_sol_del > 1){
                $title = "Hay más de una solicitud con el identificador {$solicitud}";
                $text = "Por favor pongase en contacto con Sistemas";
                $icon = "error";
    }else{
        $title = "No se encontraron solicitudes con el identificador {$solicitud}";
                $text = "Por favor pongase en contacto con Sistemas";
                $icon = "error";
    }

    if($or == 1){
        $url = "../";
    }elseif($or == 2){
        $url = "../detalle_colab.php?i=".$id_empleado;
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechazo Solicitud de Vacaciones</title>
    <link rel="shortcut icon" href="../../../static/img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../../static/css/materialize.css">
    <script type="text/javascript" src="../../../static/js/jquery-3.3.1.min.js"></script>
    <script src="../../../static/js/sweetalert.min.js"></script>
</head>
<body style="background-image: url('../../../static/img/background_login.png'); background-size: cover;">

<?php  // Cierre despliegue de días ocupados 
   

        echo '<script type="text/javascript">
                swal({
                    title: "'.$title.'",
                    text: "'.$text.'",
                    icon: "'.$icon.'",
                    button: "Regresar",
                }).then(function() {
                    window.location = "'.$url.'";
                });
                </script>';
    
?>
    
</body>
</html>

<?php
} // Cierra validación de datos por GET
?>