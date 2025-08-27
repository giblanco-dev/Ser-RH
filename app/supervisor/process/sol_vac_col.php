<?php
$periodo_vacacional = "2025";
$sql_datos_colab = "SELECT CONCAT(NOMBRES,' ',APELLIDO_PATERNO) NOMBRE, Puesto, User, pass FROM colaborador WHERE ID_Empleado = $colaborador AND estatus = 0";
$res_datos_colab = $mysqli->query($sql_datos_colab);
$val_datos_colab = $res_datos_colab->num_rows;

if($val_datos_colab == 1){
$row_colab = mysqli_fetch_assoc($res_datos_colab);
$nombre_col = $row_colab['NOMBRE'];
$puesto_col = $row_colab['Puesto'];
$usuario_col = $row_colab['User'];
$pass_user_col = $row_colab['pass'];
}else{
$nombre_col = "No identificado";
$puesto_col = "No identificado";
$usuario_col = "No identificado";
$pass_user_col = "No identificado";
}

$sql_val_sol = "SELECT COUNT(id_solicitud) total_soli, sum(dias_sol) dias_soli FROM sol_vacaciones where id_empleado = $colaborador and estatus < 2;";
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

$sql_dias_aprob = "SELECT sum(dias_sol) dias_aprobados FROM sol_vacaciones where id_empleado = $colaborador and estatus = 1;";
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
WHERE id_empleado = $colaborador
ORDER BY estatus, fecha_ini;";

$res_solicitudes = $mysqli->query($sql_soli);
$val_res_soli = $res_solicitudes->num_rows;

if($val_res_soli > 0){
    $flag_sol = 1;
}else{
    $flag_sol = 0;
}

$sql_soli_a = "SELECT 
id_solicitud
, id_empleado
, DATE_FORMAT(creacion, '%d/%m/%Y') fecha_solicitud 
, fecha_ini
, fecha_fin
, dias_sol
, SV.estatus
, DATE_FORMAT(aprobado, '%d/%m/%Y') fecha_aprob
, (SELECT fecha FROM calendario WHERE fecha > SV.fecha_fin AND festivo = 0 LIMIT 1) fecha_regreso
, (SELECT Dia FROM calendario WHERE fecha > SV.fecha_fin AND festivo = 0 LIMIT 1) Dia
FROM sol_vacaciones SV
WHERE id_empleado = $colaborador and SV.estatus = 1
ORDER BY estatus, fecha_ini;";

$res_solicitudes_a = $mysqli->query($sql_soli_a);
$val_res_soli_a = $res_solicitudes_a->num_rows;

if($val_res_soli_a > 0){
    $flag_sol_a = 1;
}else{
    $flag_sol_a = 0;
}

?>