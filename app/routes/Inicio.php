<?php
$rutes->get("dashboard",function ($View)
{
  global $title;
  $title = _('Inicio');
  $View->config(['layout' => 'templates/index']);
  $View->display('views/inicio');
});
