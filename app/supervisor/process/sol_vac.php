<?php 
$sql_val_sol = "SELECT COUNT(id_solicitud) total_soli, sum(dias_sol) dias_soli FROM sol_vacaciones;";
$res_val_sol = $mysqli->query($sql_val_sol);
$val_rows = $res_val_sol->num_rows;
    $row_cant_sol = mysqli_fetch_assoc($res_val_sol);
    $solicitudes = intval($row_cant_sol['total_soli']);
    $dias_sol = intval($row_cant_sol['dias_soli']);


$sql_dias_aprob = "SELECT sum(dias_sol) dias_aprobados, COUNT(id_solicitud) sol_aprob FROM sol_vacaciones where estatus = 1;";
$res_dias_aprob = $mysqli->query($sql_dias_aprob);
    $row_dias_aprob = mysqli_fetch_assoc($res_dias_aprob);
    $dias_aprobados = intval($row_dias_aprob['dias_aprobados']);
    $sol_aprobadas = intval($row_dias_aprob['sol_aprob']);


$sql_dias_re = "SELECT sum(dias_sol) dias_re, COUNT(id_solicitud) sol_re FROM sol_vacaciones where estatus = 2;";
$res_dias_re = $mysqli->query($sql_dias_re);
    $row_dias_re = mysqli_fetch_assoc($res_dias_re);
    $dias_re = intval($row_dias_re['dias_re']);
    $sol_re = intval($row_dias_re['sol_re']);

$sql_dias_pend = "SELECT sum(dias_sol) dias_pend, COUNT(id_solicitud) sol_pend FROM sol_vacaciones where estatus = 0;";
$res_dias_pend = $mysqli->query($sql_dias_pend);
        $row_dias_pend = mysqli_fetch_assoc($res_dias_pend);
        $dias_pend = intval($row_dias_pend['dias_pend']);
        $sol_pend = intval($row_dias_pend['sol_pend']);


$sql_soli = "SELECT 
id_solicitud
, SV.id_empleado
, CONCAT(EM.NOMBRES, ' ', EM.APELLIDO_PATERNO) NOMBRE
, EM.Puesto
, DATE_FORMAT(creacion, '%d/%m/%Y') fecha_solicitud 
, DATE_FORMAT(fecha_ini, '%d/%m/%Y') fecha_inicial
, DATE_FORMAT(fecha_fin, '%d/%m/%Y') fecha_final
, dias_asign
, dias_sol
, SV.estatus
, ES.descrip_estatus
, DATE_FORMAT(aprobado, '%d/%m/%Y') fecha_aprob
FROM sol_vacaciones SV
INNER JOIN colaborador EM ON SV.id_empleado = EM.ID_Empleado
INNER JOIN estatus_solicitud ES ON SV.estatus = ES.id_estatus_sol
ORDER BY id_empleado, fecha_ini;";

$res_solicitudes = $mysqli->query($sql_soli);
$val_res_soli = $res_solicitudes->num_rows;

if($val_res_soli > 0){
    $flag_sol = 1;
}else{
    $flag_sol = 0;
}


?>