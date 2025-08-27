<?php 
$sql_val_sol = "SELECT COUNT(id_solicitud) total_soli, sum(dias_sol) dias_soli FROM sol_vacaciones where id_empleado = $id_user and estatus < 2;";
$res_val_sol = $mysqli->query($sql_val_sol);
$val_rows = $res_val_sol->num_rows;

if($val_rows == 1){

    $row_cant_sol = mysqli_fetch_assoc($res_val_sol);
    $solicitudes = $row_cant_sol['total_soli'];
    $dias_sol = $row_cant_sol['dias_soli'];

}else{
    $row_cant_sol = 1000;
    $solicitudes = "";
    $dias_sol = "";
}

$sql_dias_aprob = "SELECT sum(dias_sol) dias_aprobados FROM sol_vacaciones where id_empleado = $id_user and estatus = 1;";
$res_dias_aprob = $mysqli->query($sql_dias_aprob);
$row_dias_aprob = mysqli_fetch_assoc($res_dias_aprob);

$dias_aprobados = intval($row_dias_aprob['dias_aprobados']);


$sql_soli = "SELECT 
id_solicitud
, id_empleado
, DATE_FORMAT(creacion, '%d/%m/%Y') fecha_solicitud 
, DATE_FORMAT(fecha_ini, '%d/%m/%Y') fecha_inicial
, DATE_FORMAT(fecha_fin, '%d/%m/%Y') fecha_final
, dias_sol
, SV.estatus
, ES.descrip_estatus
, DATE_FORMAT(aprobado, '%d/%m/%Y') fecha_aprob
FROM sol_vacaciones SV
INNER JOIN estatus_solicitud ES ON SV.estatus = ES.id_estatus_sol
WHERE id_empleado = $id_user;";

$res_solicitudes = $mysqli->query($sql_soli);
$val_res_soli = $res_solicitudes->num_rows;

if($val_res_soli > 0){
    $flag_sol = 1;
}else{
    $flag_sol = 0;
}


?>