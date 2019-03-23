<?php
$rutes->home(function($View, $Params, $Scope){
    global $title;
    $title = _('Login');
    $View->config(['layout' => 'templates/login']);
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
