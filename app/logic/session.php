<?php
require('conn.php');	
if(!empty($_POST))
{
  $usuario = mysqli_real_escape_string($mysqli,$_POST['user']);
  $password = mysqli_real_escape_string($mysqli,$_POST['password']);
  $error = '';

  $sql = "SELECT NOMBRE_COMPLETO, ID_Empleado, Puesto, User FROM colaborador WHERE User = '$usuario' AND pass = '$password' and estatus = 0";

  $result=$mysqli->query($sql);
  $rows = $result -> num_rows;
  
  if($rows == 1 ){
    $row = $result->fetch_assoc();
            session_start();
            $_SESSION['id'] = $row['ID_Empleado'];
            $_SESSION['name_usuario'] = $row['NOMBRE_COMPLETO'];
            $_SESSION['nivel'] = $row['Puesto'];
            if($row['Puesto'] == 7){
              header('Location: ../supervisor');
            }else{
              header('Location: ../colaborador');
            }
    }else{
    $error = 1;
    echo $error;
    header("Location:../../index.php?error=$error");
    exit();
  }
}else{
    $error = 2;
    header("Location:../../index.php?error=$error");
    exit();
}