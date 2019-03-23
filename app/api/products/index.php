<?php
global $tishdb;
$res = $tishdb->get_results("SELECT * FROM product");

if(empty($res)){
	tojson(['error' => TRUE, 'mensaje' => _('Ocurrió un error al consultar los productos')]);
} else {
	tojson(['error' => FALSE, 'mensaje' => _(''),'productos' =>$res]);
}
