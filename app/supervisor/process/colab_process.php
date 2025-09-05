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

if(!empty($_POST)){

require_once '../../logic/conn.php';


$title = '';
$text = '';
$icon = '';

$ID_Empleado = $_POST['id_emp'];
$flag_mod = $_POST['flag_mod'];
$apellido_p = mb_strtoupper($_POST['amaterno'], "UTF-8");
$apellido_m = mb_strtoupper($_POST['apaterno'], "UTF-8");
$nombres = mb_strtoupper($_POST['nombres'], "UTF-8");
$fecha_ingreso = $_POST['fecha_in'];
$puesto = $_POST['puesto'];
$estatus = $_POST['estatus'];

$nombre_completo = trim(trim($apellido_p).' '.trim($apellido_m).' '.trim($nombres));


if($flag_mod == 'ncol'){

                    $sql_insert = "INSERT INTO ser_rh.colaborador
                    (NOMBRE_COMPLETO,
                    NOMBRES,
                    APELLIDO_PATERNO,
                    APELLIDO_MATERNO,
                    FECHA_INGRESO,
                    Puesto,
                    User,
                    ini_pass,
                    Anio_Ini,
                    pass,
                    biometric,
                    estatus)
                    VALUES
                    ('$nombre_completo',
                    '$nombres',
                    '$apellido_p',
                    '$apellido_m',
                    '$fecha_ingreso',
                    '$puesto',
                    'Pendiente',
                    'ABC',
                    YEAR('$fecha_ingreso'),
                    '12345',
                    NULL,
                    '$estatus');";

    // Inserta nuevo colaborador
    if($mysqli->query($sql_insert) === True){
        $title = 'Registro Exitoso';
        $text = 'El colaborador ha sido registrado correctamente.';
        $icon = 'success';
    }else{
        $title = 'Error en el Registro';
        $text = 'Ha ocurrido un error al registrar al colaborador, por favor intente nuevamente.';
        $icon = 'error';
    }
}elseif($flag_mod == 'ucol'){
    $sql_update = "UPDATE ser_rh.colaborador
                    SET
                    NOMBRE_COMPLETO = '$nombre_completo',
                    NOMBRES = '$nombres',
                    APELLIDO_PATERNO = '$apellido_p',
                    APELLIDO_MATERNO = '$apellido_m',
                    FECHA_INGRESO = '$fecha_ingreso',
                    Puesto = '$puesto',
                    User = 'Pendiente',
                    ini_pass = 'ABC',
                    Anio_Ini = YEAR('$fecha_ingreso'),
                    pass = '12345',
                    estatus = '$estatus'
                    WHERE ID_Empleado = '$ID_Empleado';";

    // Modifica colaborador
    if($mysqli->query($sql_update) === True){
        $title = 'Modificación Exitosa';
        $text = 'El colaborador ha sido modificado correctamente.';
        $icon = 'success';
    }else{
        $title = 'Error en la Modificación';
        $text = 'Ha ocurrido un error al modificar al colaborador, por favor intente nuevamente.';
        $icon = 'error';
    }
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



<?php  // Cierre despliegue de días ocupados 
  
  
        echo '<script type="text/javascript">
                swal({
                    title: "'.$title.'",
                    text: "'.$text.'",
                    icon: "'.$icon.'",
                    button: "Regresar",
                }).then(function() {
                    window.location = "../colaboradores.php";
                });
                </script>';

                
  ?>
    
</body>
</html>

<?php
} // Cierra validación de datos por POST
?>