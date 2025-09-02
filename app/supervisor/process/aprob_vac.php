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
    header('Location: ../index.php');
    exit();
}

if(!empty($_GET)){

require_once '../../logic/conn.php';

$solicitud = $_GET['s'];

$sql_aprob_sol = "UPDATE sol_vacaciones SET estatus = 1, id_sup_aprueba = $id_user, aprobado = NOW() WHERE id_solicitud = $solicitud AND estatus = 0;";

if($mysqli->query($sql_aprob_sol)){
    $title = "Solicitud Aprobada";
    $text = "La solicitud de vacaciones ha sido aprobada.";
    $icon = "success"; 
}else{
    $title = "La solicitud con id {$solicitud} no se pudo actualizar";
    $text = "Contacte al administrador del sistema.";
    $icon = "error"; 
} 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprobación Solicitud de Vacaciones</title>
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
                    window.location = "../";
                });
                </script>';
    

?>
    
</body>
</html>

<?php
} // Cierra validación de datos por POST
?>