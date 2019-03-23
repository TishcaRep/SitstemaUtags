<?php
global $Config;

$titulo = 'No se pudo conectar con la base de datos';

$mensaje = <<<EOD
<h3>¿Hola? ¿Hay alguien ahí?</h3>
<p>No se pudo establecer una conexión con la base de datos <strong>{$Config['db']['name']}</strong>.</p> <p>Verifique sus datos de acceso por favor.</p>
EOD;

require_once(APPPATH.'/Core/error/dberror/index.php');
