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

// Obtener el mes y año actuales o los que se pasen por GET
$mes = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : date('Y');

// Asegurar que el año está en el rango 2025-2026
if ($anio < 2025) {
    $anio = 2025;
} elseif ($anio > 2026) {
    $anio = 2026;
}

require '../logic/conn.php';
// Obtener el primer día del mes y cuántos días tiene
$primerDia = mktime(0, 0, 0, $mes, 1, $anio);
$numeroDias = date('t', $primerDia);
$nombreMes = date('F', $primerDia);
$diaInicioSemana = date('N', $primerDia);

// Generar mes anterior y siguiente para la navegación
$mesAnterior = $mes - 1;
$anioAnterior = $anio;
if ($mesAnterior < 1) {
    $mesAnterior = 12;
    $anioAnterior--;
}

$mesSiguiente = $mes + 1;
$anioSiguiente = $anio;
if ($mesSiguiente > 12) {
    $mesSiguiente = 1;
    $anioSiguiente++;
}

// Evitar que se salga del rango 2025-2026
if ($anioAnterior < 2025) {
    $mesAnterior = 1;
    $anioAnterior = 2025;
}
if ($anioSiguiente > 2026) {
    $mesSiguiente = 12;
    $anioSiguiente = 2026;
}

// Array de nombres de los días
$diasSemana = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
$mesesEspanol = [
    'January' => 'Enero',
    'February' => 'Febrero',
    'March' => 'Marzo',
    'April' => 'Abril',
    'May' => 'Mayo',
    'June' => 'Junio',
    'July' => 'Julio',
    'August' => 'Agosto',
    'September' => 'Septiembre',
    'October' => 'Octubre',
    'November' => 'Noviembre',
    'December' => 'Diciembre'
];

$nombreMes = $mesesEspanol[date('F', $primerDia)];

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
    <style>
        /*body { font-family: Arial, sans-serif; text-align: center; }*/
        table { width: 100%; margin: auto; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; width: 14%; }
        th { background-color: #f4f4f4; }
        .navigation { margin: 20px; }
        /*a { text-decoration: none; padding: 10px; background: #007bff; color: white; border-radius: 5px; }*/
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
      <li><a href="vacaciones.php"><i class="material-icons right">local_airport</i>Vacaciones</a></li>
      <li><a href="#"><i class="material-icons right">person</i>Colaboradores</a></li> 
      <li><a href="../logic/logout.php"><i class="material-icons right">close</i>Cerrar Sistema</a></li>
      </ul>
    </div>
  </nav>
 </div>
 </header>
<div class="row">
    <div class="col s2" style="margin-top: 3%;">
    <ul class="collection with-header">
        <li class="collection-header"><h5>Opciones</h5></li>
        <li class="collection-item"><div style="font-size: 14px;"><a href="../supervisor"><span class="secondary-content"><i class="material-icons">home</i></span>Inicio</a></div></li>
        <li class="collection-item"><div style="font-size: 14px;"><a href="colaborador.php"><span class="secondary-content"><i class="material-icons">person</i></span>Colaboradores</a></div></li>
      </ul>
    </div>
    <div class="col s10">
      <div class="col s1"></div>
        <div class="row">
          <div class="col s12">
          <h4>Calendario <?php echo ucfirst($nombreMes).' '.$anio; ?></h4>
                <div class="navigation">
                    <a ></a>
                    <a href="?mes=<?php echo $mesAnterior; ?>&anio=<?php echo $anioAnterior; ?>" class="waves-effect waves-light btn"><i class="material-icons left">navigate_before</i>Anterior</a>
                    <a href="?mes=<?php echo $mesSiguiente; ?>&anio=<?php echo $anioSiguiente; ?>" class="waves-effect waves-light btn"><i class="material-icons right">navigate_next</i>Siguiente</a>
                </div>
                    <table>
                        <tr>
                            <?php foreach ($diasSemana as $dia) { echo "<th>$dia</th>"; } ?>
                        </tr>
                        <tr>
                            <?php
                            // Imprimir celdas vacías hasta el primer día del mes
                            for ($i = 1; $i < $diaInicioSemana; $i++) {
                                echo "<td></td>";
                            }

                            // Imprimir los días del mes
                            for ($dia = 1; $dia <= $numeroDias; $dia++) {
                                // Se construye fecha para consulta de vacaciones
                                $date = $anio.'-'.$mes.'-'.$dia;
                                $sql = "SELECT
                                        c.Fecha
                                        , concat(e.NOMBRES,' ',e.APELLIDO_PATERNO) Nom_enf
                                        , concat(e2.NOMBRES,' ',e2.APELLIDO_PATERNO) Nom_re_caj
                                        FROM calendario c
                                        LEFT OUTER JOIN colaborador e ON c.id_colaborador = e.ID_Empleado
                                        LEFT OUTER JOIN colaborador e2 ON c.id_colaboradorB = e.ID_Empleado
                                        WHERE fecha = '$date' limit 1;";
                                $res = $mysqli->query($sql);
                                $row = mysqli_fetch_assoc($res);
                                $nom1 = $row['Nom_enf'];
                                $nom2 = $row['Nom_re_caj'];

                                echo "<td>{$dia}<br><br>{$nom1}<br>{$nom2}</td>";
                                if (($dia + $diaInicioSemana - 1) % 7 == 0) {
                                    echo "</tr><tr>"; // Nueva fila cada domingo
                                }
                            }
                            ?>
                        </tr>
                    </table>
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
</script>
</body>
</html>