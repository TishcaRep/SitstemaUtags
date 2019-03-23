<?php
global $tishdb;
if (empty($_POST['User']) || empty($_POST['Password'])) {
  tojson(['Error'=>TRUE,'Message'=>'No se recibieron los datos necesarios']);
}
$res=login($_POST['User'],$_POST['Password'],TRUE);

if ($res['error']) {
  tojson(['Error'=>TRUE,'Message'=>'Usuario y/o Contraseña incorrecta']);
}
else {
  tojson(['Error'=>FALSE,'Message'=>'Bienvenido']);
}
