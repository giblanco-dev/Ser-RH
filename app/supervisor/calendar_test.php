<?php
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

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario <?php echo "$nombreMes $anio"; ?></title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        table { width: 100%; margin: auto; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #f4f4f4; }
        .navigation { margin: 20px; }
        a { text-decoration: none; padding: 10px; background: #007bff; color: white; border-radius: 5px; }
    </style>
</head>
<body>

<h1>Calendario <?php echo "$nombreMes $anio"; ?></h1>

<div class="navigation">
    <a href="?mes=<?php echo $mesAnterior; ?>&anio=<?php echo $anioAnterior; ?>">⏪ Anterior</a>
    <a href="?mes=<?php echo $mesSiguiente; ?>&anio=<?php echo $anioSiguiente; ?>">Siguiente ⏩</a>
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

</body>
</html>
