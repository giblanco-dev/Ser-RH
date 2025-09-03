<?php
// Instalar PhpSpreadsheet si no lo tienes:
// composer require phpoffice/phpspreadsheet

require '../../../lib/xlsx/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Fill;

setlocale(LC_TIME, "es_ES.UTF-8");

// Conexión MySQL
require_once '../../logic/conn.php';

// Parámetros recibidos (ejemplo: por GET o POST)
$fecha_inicio = $_POST['fecha_ini'] ?? '2025-01-01';
$fecha_fin    = $_POST['fecha_fin'] ?? '2025-01-31';


$sql_fechaini = "SELECT Concat(Dia,' ',NumDia,' de ',Mes,' de ',Anio) fecha_ini
FROM `calendario` where Fecha = '$fecha_inicio' LIMIT 1;";

$res_fechaini = $mysqli->query($sql_fechaini);
$row_fechaini = mysqli_fetch_assoc($res_fechaini);
$dateini_format = $row_fechaini['fecha_ini'];

$sql_fechafin = "SELECT Concat(Dia,' ',NumDia,' de ',Mes,' de ',Anio) fecha_fin
FROM `calendario` where Fecha = '$fecha_fin' LIMIT 1;";

$res_fechafin = $mysqli->query($sql_fechafin);
$row_fechafin = mysqli_fetch_assoc($res_fechafin);
$datefin_format = $row_fechafin['fecha_fin'];

// Consulta dinámica con rango de fechas

require_once 'query_repasis.php';


$res_fechas = $mysqli->query($sql_fechas);

// Crear nuevo Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Estilos del reporte
require_once 'arrays_style_repasis.php';


$sheet->setCellValue("A1", "Incidencias del {$dateini_format} al {$datefin_format}");
$sheet->mergeCells("A1:F1");
$sheet->getStyle("A1:F1")->applyFromArray($styleArray_titul);

$sheet->setCellValue("A2", "Día");
$sheet->mergeCells("A2:B2");
$sheet->getStyle("A2:B2")->applyFromArray($styleArray_he1);

$sheet->setCellValue("A3", "No. Día");
$sheet->mergeCells("A3:B3");
$sheet->getStyle("A3:B3")->applyFromArray($styleArray_he1);

$sheet->setCellValue("A4", "Puesto");
$sheet->getStyle("A4")->applyFromArray($styleArray_he3);

$sheet->setCellValue("B4", "Colaborador");
$sheet->getStyle("B4")->applyFromArray($styleArray_he3);

$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);

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


    // Fila 2: Fecha (merge en 4 columnas)
    
    $sheet->mergeCellsByColumnAndRow($startCol, 2, $endCol, 2);
    $sheet->setCellValueByColumnAndRow($startCol, 2, $dia);
    $sheet->getStyleByColumnAndRow($startCol, 2, $endCol, 2)->applyFromArray($styleArray_he2);
    $sheet->getStyleByColumnAndRow($startCol, 2)->applyFromArray($styleRightBorder);
    $sheet->getStyleByColumnAndRow($startCol, 2)->applyFromArray($styleLeftBorder);

    $sheet->mergeCellsByColumnAndRow($startCol, 3, $endCol, 3);
    $sheet->setCellValueByColumnAndRow($startCol, 3, $no_dia);
    $sheet->getStyleByColumnAndRow($startCol, 3, $endCol, 3)->applyFromArray($styleArray_he2);
    $sheet->getStyleByColumnAndRow($startCol, 3)->applyFromArray($styleRightBorder);
    $sheet->getStyleByColumnAndRow($startCol, 3)->applyFromArray($styleLeftBorder);

    // Fila 2: Sub-encabezados
    foreach ($subHeaders as $i => $sub) {
        
        $sheet->setCellValueByColumnAndRow($col + $i, 4, $sub);
        $sheet->getColumnDimensionByColumn($col + $i)->setAutoSize(true);
        $sheet->getStyleByColumnAndRow($col + $i, 4)->applyFromArray($styleArray_he3);
        if($sub == 'Entrada'){
            $sheet->getStyleByColumnAndRow($col + $i, 4)->applyFromArray($styleLeftBorder);
        }elseif($sub == 'Salida'){
            $sheet->getStyleByColumnAndRow($col + $i, 4)->applyFromArray($styleRightBorder);
        }
        
    }

    // Avanzar a las siguientes 4 columnas
    $col += count($subHeaders);
}


//  ************************************************************
// Inicia despliegue de datos de colaboradores
$rowNum = 5; // Fila inicial para datos

$res_colab = $mysqli->query($sql_emple);

while($row_colab = mysqli_fetch_assoc($res_colab)) {
    $id_empleado = $row_colab['ID_Empleado'];
    $nombre = $row_colab['NOMBRE_COMPLETO'];
    $puesto = $row_colab['descrip_puesto'];

    // Escribir datos del colaborador
    $sheet->setCellValueByColumnAndRow(1, $rowNum, $puesto);
    $sheet->getStyleByColumnAndRow(1, $rowNum)->applyFromArray($styleArray_colab);
    $sheet->setCellValueByColumnAndRow(2, $rowNum, $nombre);
    $sheet->getStyleByColumnAndRow(2, $rowNum)->applyFromArray($styleArray_colab);

    // Reiniciar columna para fechas
    $col = 3;

    // Recorre las fechas nuevamente para llenar los datos
    $res_fechas->data_seek(0); // Reiniciar puntero de resultados
    while ($row_fecha = $res_fechas->fetch_assoc()) {
        $fecha = $row_fecha['Fecha'];
        $festivo = $row_fecha['Festivo'];

        // Filtrar registros de asistencia para este empleado y fecha
        $entrada = "Sin checada";
        $salida = "Sin checada";
        $s_comida = "";
        $r_comida = "";
        $vacaciones = "";

        if($festivo == 0) {
        
           $sql_rep_asis = "SELECT DISTINCT 
    CAL.Fecha,
    AC.fecha_registro,
    AC.id_empleado,
	
    IF(id_colaborador = $id_empleado or id_colaboradorB = $id_empleado, COALESCE(id_colaborador, id_colaboradorB),0)
    AS VACACIONES,

    -- Primer horario (entrada)
    IF(
        AC.fecha_registro IS NOT NULL, 
        (SELECT horario 
         FROM asistencia_colaborador AC1 
         WHERE AC1.id_empleado = AC.id_empleado 
           AND AC1.fecha_registro = CAL.Fecha 
         ORDER BY horario ASC 
         LIMIT 1), 
        'Sin checada'
    ) AS Entrada,

    -- Último horario (salida)
    IF(
        AC.fecha_registro IS NOT NULL, 
        (SELECT horario 
         FROM asistencia_colaborador AC1 
         WHERE AC1.id_empleado = AC.id_empleado 
           AND AC1.fecha_registro = CAL.Fecha 
         ORDER BY horario DESC 
         LIMIT 1), 
        'Sin checada'
    ) AS Salida,

    -- Número de registros del día
    IF(
        AC.fecha_registro IS NOT NULL, 
        (SELECT COUNT(horario) 
         FROM asistencia_colaborador AC1 
         WHERE AC1.id_empleado = AC.id_empleado 
           AND AC1.fecha_registro = CAL.Fecha 
         GROUP BY AC1.id_empleado, AC1.fecha_registro), 
        0
    ) AS Registros_DIA,

    -- Salida a comer (2do registro)
    IF(
        (SELECT COUNT(horario) 
         FROM asistencia_colaborador AC1 
         WHERE AC1.id_empleado = AC.id_empleado 
           AND AC1.fecha_registro = CAL.Fecha 
         GROUP BY AC1.id_empleado, AC1.fecha_registro) = 4, 
        (SELECT horario 
         FROM asistencia_colaborador AC1 
         WHERE AC1.id_empleado = AC.id_empleado 
           AND AC1.fecha_registro = CAL.Fecha 
         ORDER BY horario ASC 
         LIMIT 1 OFFSET 1), 
        NULL
    ) AS Salida_Comida,

    -- Regreso de comer (3er registro)
    IF(
        (SELECT COUNT(horario) 
         FROM asistencia_colaborador AC1 
         WHERE AC1.id_empleado = AC.id_empleado 
           AND AC1.fecha_registro = CAL.Fecha 
         GROUP BY AC1.id_empleado, AC1.fecha_registro) = 4, 
        (SELECT horario 
         FROM asistencia_colaborador AC1 
         WHERE AC1.id_empleado = AC.id_empleado 
           AND AC1.fecha_registro = CAL.Fecha 
         ORDER BY horario ASC 
         LIMIT 1 OFFSET 2), 
        NULL
    ) AS Regreso_Comida

FROM calendario CAL
LEFT OUTER JOIN asistencia_colaborador AC 
    ON CAL.Fecha = AC.fecha_registro 
   AND AC.id_empleado = $id_empleado
WHERE CAL.Fecha = '$fecha'";

        $res_asis = $mysqli->query($sql_rep_asis);
        
        while ($row_asis = mysqli_fetch_assoc($res_asis)) {
            
                if ($row_asis['VACACIONES'] != 0) {
                    $vacaciones = "Vacaciones";
                    $entrada = "";
                    $salida = "";
                    $s_comida = "";
                    $r_comida = "";
                } else {
                    if ($row_asis['Entrada'] != 'Sin checada') {
                        $entrada = date("h:i A", strtotime($row_asis['Entrada']));
                    }
                    if ($row_asis['Salida'] != 'Sin checada' AND $row_asis['Registros_DIA'] > 1) {
                        $salida = date("h:i A", strtotime($row_asis['Salida']));
                    }
                    if ($row_asis['Salida_Comida']) {
                        $s_comida = date("h:i A", strtotime($row_asis['Salida_Comida']));
                    }
                    if ($row_asis['Regreso_Comida']) {
                        $r_comida = date("h:i A", strtotime($row_asis['Regreso_Comida']));
                    }
                }
                break; //

            }
        }// Fin búsqueda registros de asistencia 
        
        

        // Escribir datos en las columnas correspondientes
        if ($vacaciones) {
            // Combinar 4 columnas en la fila actual
            $sheet->mergeCellsByColumnAndRow($col, $rowNum, $col + 3, $rowNum);

            // Escribir el texto en la primera celda del rango
            $sheet->setCellValueByColumnAndRow($col, $rowNum, $vacaciones);

            // Centrar el texto horizontal y verticalmente
            $sheet->getStyleByColumnAndRow($col, $rowNum)->applyFromArray($styleArray_noLab);
            $sheet->getStyleByColumnAndRow($col, $rowNum)->applyFromArray($styleLeftBorder);
            $sheet->getStyleByColumnAndRow($col, $rowNum)->applyFromArray($styleRightBorder);

        }elseif ($festivo == 1) {
            // Combinar 4 columnas en la fila actual
            $sheet->mergeCellsByColumnAndRow($col, $rowNum, $col + 3, $rowNum);

            // Escribir el texto en la primera celda del rango
            $sheet->setCellValueByColumnAndRow($col, $rowNum, "Día Festivo");

            // Centrar el texto horizontal y verticalmente
            $sheet->getStyleByColumnAndRow($col, $rowNum, $col + 3, $rowNum)->applyFromArray($styleArray_noLab);
            $sheet->getStyleByColumnAndRow($col, $rowNum, $col + 3, $rowNum)->applyFromArray($styleLeftBorder);
            $sheet->getStyleByColumnAndRow($col, $rowNum, $col + 3, $rowNum)->applyFromArray($styleRightBorder);
    
    
    }else {
            $sheet->setCellValueByColumnAndRow($col, $rowNum, $entrada);
            $sheet->getStyleByColumnAndRow($col, $rowNum)->applyFromArray($styleArray_asis);
            $sheet->getStyleByColumnAndRow($col, $rowNum)->applyFromArray($styleLeftBorder);

            $sheet->setCellValueByColumnAndRow($col + 1, $rowNum, $s_comida);
            $sheet->getStyleByColumnAndRow($col + 1, $rowNum)->applyFromArray($styleArray_asis);

            $sheet->setCellValueByColumnAndRow($col + 2, $rowNum, $r_comida);
            $sheet->getStyleByColumnAndRow($col +2 , $rowNum)->applyFromArray($styleArray_asis);

            $sheet->setCellValueByColumnAndRow($col + 3, $rowNum, $salida);
            $sheet->getStyleByColumnAndRow($col + 3, $rowNum)->applyFromArray($styleArray_asis);
            $sheet->getStyleByColumnAndRow($col + 3, $rowNum)->applyFromArray($styleRightBorder);
        }

        // Avanzar a las siguientes 4 columnas
        $col += count($subHeaders);
    } // Fin del bucle de fechas

    // Avanzar a la siguiente fila para el próximo colaborador
    $rowNum++;

} // Fin del bucle de colaboradores

$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
$rango = "A2:{$highestColumn}{$highestRow}";

// Ajustar estilos básicos
$sheet->setTitle("Reporte Asistencias");

// Aplicar a todo el rango con datos
$sheet->getStyle($rango)->applyFromArray($styleArray);

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