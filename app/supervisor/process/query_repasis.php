<?php 
$sql_fechas = "SELECT DISTINCT Fecha, Dia, NumDia FROM `calendario` WHERE Fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' order by Fecha;";

$sql_emple = "";

 $sql_rep_asis = "SELECT DISTINCT CAL.Fecha 
, CAL.Dia 
, CAL.Festivo 
, AC.fecha_registro
, AC.id_empleado 
, COL.NOMBRE_COMPLETO 
, PU.descrip_puesto 
, IF(PU.id_puesto = 5 OR PU.id_puesto = 3, 
     (SELECT id_colaboradorB FROM calendario CAL1 WHERE CAL1.id_colaboradorB = AC.id_empleado AND CAL1.Fecha = CAL.Fecha), 
     (SELECT id_colaborador FROM calendario CAL1 WHERE CAL1.id_colaborador = AC.id_empleado AND CAL1.Fecha = CAL.Fecha)) VACACIONES
, IF(AC.fecha_registro IS NOT NULL, (SELECT horario FROM asistencia_colaborador AC1 
                                     WHERE AC1.id_empleado = AC.id_empleado AND AC1.fecha_registro = CAL.Fecha ORDER BY horario LIMIT 1), 'Sin checada') Entrada
, IF(AC.fecha_registro IS NOT NULL, (SELECT horario FROM asistencia_colaborador AC1 
                                     WHERE AC1.id_empleado = AC.id_empleado AND AC1.fecha_registro = CAL.Fecha ORDER BY horario DESC LIMIT 1), 'Sin checada') Salida
, IF(AC.fecha_registro IS NOT NULL, (SELECT COUNT(horario) FROM asistencia_colaborador AC1 
                       WHERE AC1.id_empleado = AC.id_empleado AND AC1.fecha_registro = CAL.Fecha group by AC1.id_empleado, AC1.fecha_registro, AC1.fecha_registro), 0) Registros_DIA
, IF((SELECT COUNT(horario) FROM asistencia_colaborador AC1 
                       WHERE AC1.id_empleado = AC.id_empleado AND AC1.fecha_registro = CAL.Fecha group by AC1.id_empleado, AC1.fecha_registro, AC1.fecha_registro) >= 4, (SELECT horario 
         FROM asistencia_colaborador AC1 
         WHERE AC1.id_empleado = AC.id_empleado 
           AND AC1.fecha_registro = CAL.Fecha 
         ORDER BY horario ASC LIMIT 1 OFFSET 1), null) Salida_Comida
, IF((SELECT COUNT(horario) FROM asistencia_colaborador AC1 
                       WHERE AC1.id_empleado = AC.id_empleado AND AC1.fecha_registro = CAL.Fecha group by AC1.id_empleado, AC1.fecha_registro, AC1.fecha_registro) >= 4 , (SELECT horario 
         FROM asistencia_colaborador AC1 
         WHERE AC1.id_empleado = AC.id_empleado 
           AND AC1.fecha_registro = CAL.Fecha 
         ORDER BY horario ASC LIMIT 1 OFFSET 2), null) Regreso_Comida
     FROM calendario CAL 
LEFT OUTER JOIN asistencia_colaborador AC ON CAL.Fecha = AC.fecha_registro AND AC.id_empleado = 22 -- Modificar por variable de Empleado
LEFT OUTER JOIN colaborador COL ON AC.id_empleado = COL.ID_Empleado 
LEFT OUTER JOIN puesto PU ON COL.Puesto = PU.id_puesto 
WHERE CAL.Fecha BETWEEN '2025-08-28' AND '2025-09-01' ORDER BY cal.Fecha;";



?>