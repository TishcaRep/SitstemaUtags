<?php

//Redireccionar
function redirect($url, $codigo = 303){
    header('Location: ' . $url, true, $codigo);
    die();
}

//Respuesta para Ajax en JSON
function tojson($arr){
    header('Content-Type: application/json');
    die(json_encode($arr));
}

//Url del sito
function site_url($pagina = ''){
    global $site_url;
    return $site_url.'/'.$pagina;
}
