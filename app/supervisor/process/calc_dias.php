<?php 
// Se obtienen días de vacaciones

$sql_days = "SELECT 
            ID_Empleado
            , NOMBRE_COMPLETO
            , FECHA_INGRESO
            , Puesto
            , Anio_Ini
            , YEAR(FECHA_INGRESO) Anio_Ini_Val
            , YEAR(NOW()) ANIO_ACTUAL
            , (YEAR(NOW()) - YEAR(FECHA_INGRESO)) DIF_ANIOS
            , (SELECT dias FROM tabulador_dias WHERE ((YEAR(NOW()) - YEAR(FECHA_INGRESO)) >= MIN AND (YEAR(NOW()) - YEAR(FECHA_INGRESO)) < MAX)) DIAS_ASIGN
            FROM colaborador c WHERE 
            Puesto !=  'Supervisor' AND Estatus = 0 AND ID_Empleado = $id_user;";

$res_days = $mysqli->query($sql_days);
$val_res_days = $res_days->num_rows;

if($val_res_days ==  1){

    $row_day = mysqli_fetch_assoc($res_days);
    $fecha_ing = date("d/m/Y", strtotime($row_day['FECHA_INGRESO']));

    $dia_ing = date("d", strtotime($row_day['FECHA_INGRESO']));
    $mes_ing = date("m", strtotime($row_day['FECHA_INGRESO']));

    $dif_years = $row_day['DIF_ANIOS'];
    $days_asign= $row_day['DIAS_ASIGN'];
    if($days_asign <20){
        $per_vac = 1;
        $leyenda_per_vac = " 1 periodo";
    }else{
        $per_vac = 2;
        $leyenda_per_vac = " 2 periodos";
    }

    $min_calendar = date("Y")."-".$mes_ing."-".$dia_ing;
    $min_calendar2 = date_create($min_calendar);
    date_sub($min_calendar2, date_interval_create_from_date_string('1 days'));
    //echo date_format($min_calendar2, 'Y-m-d');
    date_add($min_calendar2, date_interval_create_from_date_string('1 year'));
    $max_calendar = date_format($min_calendar2, 'Y-m-d');  
    //echo '<br>';
    //echo $max_calendar;
    $leyenda_fechas_vac = "Usted puede solicitar ".$leyenda_per_vac." de vacaciones a partir del ".$dia_ing."/".$mes_ing."/".date("Y")." al ".date_format($min_calendar2, 'd/m/Y');


}else{
    $fecha_ing = "No identificada";
    $dif_years = "--";
    $days_asign= "--";
    $per_vac = "--";
    $leyenda_per_vac = "n/a periodos.";
    $min_calendar = "";
    $max_calendar = "";
    $leyenda_fechas_vac = "n/a";
}


?>