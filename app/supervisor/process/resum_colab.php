<?php 
// Se obtienen días de vacaciones

$sql_colab = "SELECT 
            ID_Empleado
            , CONCAT(NOMBRES,' ',APELLIDO_PATERNO) NOMBRE
            , FECHA_INGRESO
            , DATE_FORMAT(FECHA_INGRESO, '%d/%m/%Y') FECHA_INGRESO2
            , Puesto
            , Anio_Ini
            , YEAR(FECHA_INGRESO) Anio_Ini_Val
            , YEAR(NOW()) ANIO_ACTUAL
            , (YEAR(NOW()) - YEAR(FECHA_INGRESO)) DIF_ANIOS
            , (SELECT dias FROM tabulador_dias WHERE ((YEAR(NOW()) - YEAR(FECHA_INGRESO)) >= MIN AND (YEAR(NOW()) - YEAR(FECHA_INGRESO)) < MAX)) DIAS_ASIGN
            , (SELECT COUNT(sv.id_solicitud) FROM sol_vacaciones sv where sv.id_empleado = c.ID_Empleado AND sv.estatus != 2 ) Solicitudes
            , (SELECT COUNT(sv.id_solicitud) FROM sol_vacaciones sv where sv.id_empleado = c.ID_Empleado AND sv.estatus = 1 ) Solicitudes_Aprob
            , (SELECT COUNT(sv.id_solicitud) FROM sol_vacaciones sv where sv.id_empleado = c.ID_Empleado AND sv.estatus = 0 ) Solicitudes_Pend
            , IFNULL((SELECT SUM(sv.dias_sol) FROM sol_vacaciones sv where sv.id_empleado = c.ID_Empleado AND sv.estatus != 2 ),0) Dias_Soli
            , IFNULL((SELECT SUM(sv.dias_sol) FROM sol_vacaciones sv where sv.id_empleado = c.ID_Empleado AND sv.estatus = 0 ),0) Dias_Por_Aprob
            , IFNULL((SELECT SUM(sv.dias_sol) FROM sol_vacaciones sv where sv.id_empleado = c.ID_Empleado AND sv.estatus = 1 ),0) Dias_aprob
            FROM colaborador c WHERE 
            Puesto !=  'Supervisor' AND Estatus = 0 order by FECHA_INGRESO;";

$res_colab = $mysqli->query($sql_colab);

?>