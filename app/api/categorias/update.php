<?php
global $tishdb;
if(empty($_POST['nombreCategoria'])){
	tojson(['error' => TRUE, 'mensaje' => _('No se resivieron los datos necesarios!!')]);
}
$res = $tishdb->get_results("SELECT * FROM category");
if(empty($res)){
	tojson(['error' => TRUE, 'mensaje' => _('Ocurrió un error al crear la categoria')]);
} else {
	tojson(['error' => FALSE, 'mensaje' => _('Ha sido creada exitosa mente!!')]);
}
