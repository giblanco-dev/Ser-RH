<?php 
$sql_fechas = "SELECT DISTINCT Fecha, Dia, NumDia, Festivo FROM `calendario` WHERE Fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' order by Fecha;";

$sql_emple = "SELECT DISTINCT ID_Empleado, NOMBRE_COMPLETO, p.descrip_puesto
FROM colaborador c
INNER JOIN Puesto p ON c.Puesto = p.id_puesto
WHERE c.estatus = 0
ORDER BY NOMBRE_COMPLETO;";

?>