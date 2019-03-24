<?php

//Global Paths
define('ABSPATH', dirname(__DIR__));
define('APPPATH', dirname(__FILE__));

//Global Variables
global $tishdb,$tittle,$site_url,$Config;

//Load Composer
require ABSPATH . '/vendor/autoload.php';

//Composer Requires
use Tipsy\Tipsy;

//Config Tipsy
Tipsy::config(ABSPATH .'/config/config.ini');

//Core functions
require_once('core/functions.php');

//Load Config
$Config = Tipsy::config();

if(empty($Config['site']['path'])){
    //require_once('core/assets/install/index.php');
    exit;
}


//Debug
if($Config['debug']['show_errors']){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

//Cargar configuración de la Base de datos
$db_user = $Config['db']['user'];
$db_pass = $Config['db']['pass'];
$db_name = $Config['db']['name'];
$db_host = $Config['db']['host'];
$tishdb = new ezSQL_mysqli($db_user, $db_pass, $db_name, $db_host, 'utf8');

//Auto URL site
$site_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$Config['site']['path'];

//DB driver para conexión a MySQL
try {
    $pdo = new PDO("mysql:dbname={$db_name};host={$db_host};charset=utf8", $db_user, $db_pass);
} catch (Exception $ex){
    require_once('core/error/index.php');
    exit;
}

//Make a basic login structure
$existe = $tishdb->get_col("SHOW TABLES LIKE 'users'");
if(!count($existe)){
    $sql = file_get_contents(ABSPATH . '/vendor/delight-im/auth/Database/MySQL.sql');
    $pdo->exec($sql);
}

//Config variable for global auth (Docs: https://github.com/delight-im/PHP-Auth)
$auth = new \Delight\Auth\Auth($pdo);

// Auth  Login Functions
require_once('core/auth.php');

//Functions Globals
require_once('functions.php');



//Routes
$rutes = Tipsy::router();
require_once('rutes.php');

//Route Acces Header for anyone rute /api/*
Tipsy::middleware(function($Request) {
    if ($Request->loc() == 'api') {
        header("Access-Control-Allow-Origin: *");
    }
});

//Check Access
Tipsy::middleware(function($Request) {
    if ($Request->loc() == 'admin') {
    	if(!in_array($Request->path(),['admin/ingresar'])){
    		if(tk_id() == 0){
    			redirect(site_url('admin/login'));
    		}
    	}
    }
	//Ajax Securitys
	if(strpos($Request->path(), 'ajax') !== FALSE && !in_array($Request->path(),['ajax/Login'])){
		if(id() == 0)
			redirect(site_url('404'));
	}
});

//Run App
Tipsy::run();
