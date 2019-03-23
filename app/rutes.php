<?php
$rutes->home(function($View, $Params, $Scope){
    global $title;
    $title = _('Inicio');
    $View->config(['layout' => 'templates/index']);
    $View->display('views/index');
});

$rutes->otherwise(function($View){
    global $titulo;
    $titulo = '404';
    http_response_code(404);
    $View->config(array('layout' => 'templates/404'));
    $View->display('404');
});

require_once('routes/Auth.php');
