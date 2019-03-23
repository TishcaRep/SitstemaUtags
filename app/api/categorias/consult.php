<?php
global $tishdb;
if (empty($_POST['id'])) {
	tojson(['error' => TRUE, 'mensaje' => _('No se resivieron los datos necesarios!!')]);
}

$res = $tishdb->get_row("SELECT * FROM category WHERE idCAtegory ={$_POST['id']}");

if(empty($res)){
	tojson(['error' => TRUE, 'mensaje' => _('Ocurrió un error al consultar la categoria'),'categorias' =>[] ]);
} else {
	tojson(['error' => FALSE, 'mensaje' => _(''),'categorias' =>[$res]]);
}
