<?php
if (empty($_POST['datos'])) {
  tojson(['error'=>TRUE,'message'=>'No se resivieron los datos necesarios']);
}
$datos = $_POST['datos'];
$res=register($datos['Correo_Electronico'], $datos['Contraseña'],'user' ,"",false,[]);
tojson($res);
