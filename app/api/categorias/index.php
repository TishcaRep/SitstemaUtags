<?php
global $tishdb;
$res = $tishdb->get_results("SELECT * FROM category");
if(empty($res)){
	tojson(['error' => TRUE, 'mensaje' => _('Ocurrió un error al consultar las categorias')]);
} else {
	tojson(['error' => FALSE, 'mensaje' => _(''),'categorias' =>$res]);
}
