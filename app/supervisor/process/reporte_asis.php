<?php
// Instalar PhpSpreadsheet si no lo tienes:
// composer require phpoffice/phpspreadsheet

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Conexión MySQL
require_once '../../logic/conn.php';

// Parámetros recibidos (ejemplo: por GET o POST)
$fecha_inicio = $_POST['fecha_inicio'] ?? '2025-01-01';
$fecha_fin    = $_POST['fecha_fin'] ?? '2025-01-31';

// Consulta dinámica con rango de fechas
$sql = "SELECT empleado, fecha, hora_entrada, hora_salida, observaciones
        FROM asistencias
        WHERE fecha BETWEEN ? AND ?
        ORDER BY fecha ASC";

/* Avance consulta reporte

SELECT DISTINCT CAL.Fecha , CAL.Dia , CAL.Festivo , AC.id_empleado , COL.NOMBRE_COMPLETO , PU.descrip_puesto , IF(PU.id_puesto = 5 OR PU.id_puesto = 3, (SELECT id_colaboradorB FROM calendario CAL1 WHERE CAL1.id_colaboradorB = AC.id_empleado), (SELECT id_colaborador FROM calendario CAL1 WHERE CAL1.id_colaborador = AC.id_empleado)) VACACIONES FROM calendario CAL LEFT OUTER JOIN asistencia_colaborador AC ON CAL.Fecha = AC.fecha_registro LEFT OUTER JOIN colaborador COL ON AC.id_empleado = COL.ID_Empleado LEFT OUTER JOIN puesto PU ON COL.Puesto = PU.id_puesto WHERE CAL.Fecha BETWEEN '2025-09-05' AND '2025-09-17';

*/

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
$stmt->execute();
$result = $stmt->get_result();

// Cargar plantilla
$spreadsheet = IOFactory::load("FormatoReporteAsistencia.xlsx");
$sheet = $spreadsheet->getActiveSheet();

// Suponiendo que los encabezados ya están en la plantilla
// empezamos a llenar datos desde la fila 6 (ajusta según tu formato)
$fila = 6;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue("A$fila", $row['empleado']);
    $sheet->setCellValue("B$fila", $row['fecha']);
    $sheet->setCellValue("C$fila", $row['hora_entrada']);
    $sheet->setCellValue("D$fila", $row['hora_salida']);
    $sheet->setCellValue("E$fila", $row['observaciones']);
    $fila++;
}

// Nombre dinámico
$nombreArchivo = "Reporte_Asistencia_" . $fecha_inicio . "_al_" . $fecha_fin . ".xlsx";

// Forzar descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$nombreArchivo\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
