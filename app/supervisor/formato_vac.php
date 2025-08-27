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


if(!empty($_GET['i'])){
    
    $colaborador = $_GET['i'];

    require_once '../logic/conn.php';
    require_once 'process/calc_dias_col.php';
    require_once 'process/sol_vac_col.php';
    
    
    //date_default_timezone_set( 'America/Mexico_City' );
    $dateTimeObj = new DateTime('now', new DateTimeZone('America/Mexico_City'));
    $fecha_formato = IntlDateFormatter::formatObject(
        $dateTimeObj,
        "d 'de' MMMM 'de' y",
        'es_MX'
    );

    $dateTimeObj_ing = new DateTime($fecha_ing2, new DateTimeZone('America/Mexico_City'));
    $fecha_formato_ing = IntlDateFormatter::formatObject(
        $dateTimeObj_ing,
        "d 'de' MMMM 'de' y",
        'es_MX'
    );
    
    ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formato Vacaciones <?php echo $nombre_col; ?></title>
    <link rel="shortcut icon" href="../../static/img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../static/css/materialize.css">
    <link rel="stylesheet" href="../../static/icons/iconfont/material-icons.css">
    <script type="text/javascript" src="../../static/js/jquery-3.3.1.min.js"></script>
    <script src="../../static/js/materialize.js"></script>
</head>
<body background-color: #e8eaf6 ;>
    <div style="height: 27.94 cm; width: 100%; background-color: #fff; font-size: 12px;">
        <div class="container">
            <div class="row">
                <div class="col s12">
                    <p class="right-align">Ciudad de México, a <?php echo $fecha_formato; ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <p class="left-align"><strong>C. <?php echo $nombre_col; ?></strong></p>
                    <p class="left-align"><strong>Asunto: Asignación del Periodo Vacacional 2025.</strong></p>
                    <p style="text-align: justify">
                    Mediante la presente le notificamos que de acuerdo a la normativa vigente y en cumplimiento de la
                    legislación laboral, al haber usted cumplido un año completo de labores sin interrupción para esta 
                    empresa, se le otorgarán <?php echo $days_asign_letra; ?> (<?php echo $days_asign; ?>) días de vacaciones respectivas 
                    al año en curso, mismas que se organizarán de acuerdo a su elección de la siguiente manera:
                    </p>
                    <p>Fecha de ingreso: <?php echo $fecha_formato_ing; ?>.</p>
                    <div>
                    <ul>
                        <?php 
                        if($flag_sol_a == 1){
                            while($row_soli = mysqli_fetch_assoc($res_solicitudes_a)){
                                if($row_soli['dias_sol'] == 1){

                                    $dateTimeObj_fi = new DateTime($row_soli['fecha_ini'], new DateTimeZone('America/Mexico_City'));
                                    $fecha_formato_fi = IntlDateFormatter::formatObject(
                                        $dateTimeObj_fi,
                                        "d 'de' MMMM 'de' y",
                                        'es_MX'
                                    );

                                    $dateTimeObj_fr = new DateTime($row_soli['fecha_regreso'], new DateTimeZone('America/Mexico_City'));
                                    $fecha_formato_fr = IntlDateFormatter::formatObject(
                                        $dateTimeObj_fr,
                                        "d 'de' MMMM 'de' y",
                                        'es_MX'
                                    );

                                    $leyenda_vac = "Un ({$row_soli['dias_sol']}) día: el {$fecha_formato_fi}, la fecha de retorno a sus funciones
                                        será el día {$row_soli['Dia']} {$fecha_formato_fr} a excepción de que este día sea de descanso para la trabajadora.";

                                }elseif($row_soli['dias_sol'] > 1){

                                    $dateTimeObj_fi = new DateTime($row_soli['fecha_ini'], new DateTimeZone('America/Mexico_City'));
                                    $fecha_formato_fi = IntlDateFormatter::formatObject(
                                        $dateTimeObj_fi,
                                        "d 'de' MMMM 'de' y",
                                        'es_MX'
                                    );

                                    $dateTimeObj_ff = new DateTime($row_soli['fecha_fin'], new DateTimeZone('America/Mexico_City'));
                                    $fecha_formato_ff = IntlDateFormatter::formatObject(
                                        $dateTimeObj_ff,
                                        "d 'de' MMMM 'de' y",
                                        'es_MX'
                                    );

                                    $dateTimeObj_fr = new DateTime($row_soli['fecha_regreso'], new DateTimeZone('America/Mexico_City'));
                                    $fecha_formato_fr = IntlDateFormatter::formatObject(
                                        $dateTimeObj_fr,
                                        "d 'de' MMMM 'de' y",
                                        'es_MX'
                                    );

                                    $formatterES = new NumberFormatter("es-ES", NumberFormatter::SPELLOUT);
                                    $n = number_format(floatval($row_soli['dias_sol']),0);
                                    $izquierda = intval($n);
                                    $dias_letra = ucfirst($formatterES->format($izquierda));
                                    $leyenda_vac = "{$dias_letra} ({$row_soli['dias_sol']}) días: del {$fecha_formato_fi} al {$fecha_formato_ff}
                                            , la fecha de retorno a sus funciones
                                            será el día {$row_soli['Dia']} {$fecha_formato_fr} a excepción de que este día sea de descanso para la trabajadora.";
                                }

                        ?>
                        <li class="valign-wrapper"><span><i class="material-icons"></span>chevron_right</i><?php echo $leyenda_vac ?>
                        </li>
                        <?php 
                                } // Cierra while de recorrido de solicitudes
                            }// Cierra IF de validación si hay solicitudes de vacaciones 
                        ?>
                    </ul>
                    </div>
                    <p>
                        <?php 
                        $days_pend = $days_asign - $dias_sol;
                        
                        if($days_pend == 0){
                            echo "Esperamos que pueda disfrutar sus días de asueto.";
                        }elseif($days_pend == 1){
                            echo "A petición de la trabajadora, queda un día pendiente por tomar, el cúal
                                se programará con anticipación y sin afectar la dinámica de las compañeras. Esperamos 
                                que pueda disfrutar sus días de asueto.";
                        }elseif($days_pend > 1){
                            $formatterES = new NumberFormatter("es-ES", NumberFormatter::SPELLOUT);
                            $n = number_format(floatval($days_pend),0);
                            $izquierda = intval($n);
                            $dias_p_letra = $formatterES->format($izquierda);

                            echo "A petición de la trabajadora, quedan {$dias_p_letra} ({$days_pend}) días pendientes por tomar, los cuales
                                    se programarán con anticipación y sin afectar la dinámica de las compañeras. Esperamos 
                                    que pueda disfrutar sus días de asueto.";
                        }
                        ?>
                    </p>
                </div>
            </div>
            <br>
            <br>
            <br>
            <div class="row">
                <div class="col s1"></div>
                <div class="col s4">
                    <hr style="background-color: #000; border: 1px solid #000;">
                    <p class="center-align">Representante Legal
                    <br>
                    Dra. Mónica Martínez Manrique
                    </p>
                </div> 
                <div class="col s2"></div>
                <div class="col s4">
                    <hr style="background-color: #000; border: 1px solid #000;">
                    <p class="center-align">Trabajador(a)
                    <br>
                    <?php echo "Nombre del trabajador"; ?>
                    </p>
                </div> 
                <div class="col s1"></div>
            </div>

        </div> <!--CIERRE CONTAINER-->

    </div>
</body>
</html>
<?php 
    }
?>