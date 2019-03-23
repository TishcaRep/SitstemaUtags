<?php
$rutes->post('api/categorias',function(){
require_once(APPPATH.'/api/categorias/index.php');
});

$rutes->post('api/categorias/new',function(){
require_once(APPPATH.'/api/categorias/new.php');
});

$rutes->post('api/categorias/consult',function(){
require_once(APPPATH.'/api/categorias/consult.php');
});
