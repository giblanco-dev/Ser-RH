<?php
// Instalar PhpSpreadsheet si no lo tienes:
// composer require phpoffice/phpspreadsheet

require '../../../lib/xlsx/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


// Conexión MySQL
require_once '../../logic/conn.php';

// Parámetros recibidos (ejemplo: por GET o POST)
$fecha_inicio = $_POST['fecha_ini'] ?? '2025-01-01';
$fecha_fin    = $_POST['fecha_fin'] ?? '2025-01-31';

// Consulta dinámica con rango de fechas

require_once 'query_repasis.php';

$res_asis = $mysqli->query($sql_rep_asis);


$res_fechas = $mysqli->query($sql_fechas);

// Crear nuevo Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Sub-encabezados por fecha
$subHeaders = ["Entrada", "S-Com", "R-Com", "Salida"];

// --- Encabezados dinámicos (fechas como columnas) ---
$col = 3; // Columna inicial (A=1, B=2). Dejo la col A para "Empleado", por ejemplo
while ($row = $res_fechas->fetch_assoc()) {
     $fecha = $row['Fecha'];
    $dia   = $row['Dia'];
    $no_dia = $row['NumDia'];

    // Rango de columnas que ocupará esta fecha (4 columnas)
    $startCol = $col;
    $endCol   = $col + count($subHeaders) - 1;

    // Fila 1: Fecha (merge en 4 columnas)
    $sheet->mergeCellsByColumnAndRow($startCol, 2, $endCol, 2);
    $sheet->setCellValueByColumnAndRow($startCol, 2, $dia);

    $sheet->getStyleByColumnAndRow($startCol, 2)
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
        ->setVertical(Alignment::VERTICAL_CENTER);

     $sheet->mergeCellsByColumnAndRow($startCol, 3, $endCol, 3);
    $sheet->setCellValueByColumnAndRow($startCol, 3, $no_dia);

    $sheet->getStyleByColumnAndRow($startCol, 3)
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
        ->setVertical(Alignment::VERTICAL_CENTER);

    // Fila 2: Sub-encabezados
    foreach ($subHeaders as $i => $sub) {
        $sheet->setCellValueByColumnAndRow($col + $i, 4, $sub);
        $sheet->getColumnDimensionByColumn($col + $i)->setAutoSize(true);

        $sheet->getStyleByColumnAndRow($col + $i, 4)
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
    }

    // Avanzar a las siguientes 4 columnas
    $col += count($subHeaders);
}




// Estilo: negrita en encabezados
$lastCol = $sheet->getHighestColumn();
$sheet->getStyle("A1:{$lastCol}2")->getFont()->setBold(true);


// Ajustar estilos básicos
$sheet->setTitle("Reporte Asistencias");

// Generar nombre dinámico
$nombreArchivo = "Reporte_Asistencias_" . $fecha_inicio . "_al_" . $fecha_fin . ".xlsx";

// Forzar descarga en navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$nombreArchivo\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

?>