<?php
$rutes->post('ajax/Registrar/',function(){
  require_once APPPATH.'/ajax/login/registrar.php';
});

$rutes->post('ajax/Ingresar/',function(){
  require_once APPPATH.'/ajax/login/ingresar.php';
});
