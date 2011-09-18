<?php 

//init core functions 
//include 'CoreFunctions.php';

//init cache
$cache = new Cache;
global $cache;

//db functions
include 'DatabaseFunctions.php'; 

//connect to database
include 'Connect.php'; 


// set DEV
$conf['DEV'] = false;
if(in_array($_SERVER['REMOTE_ADDR'], $conf['DEV_IP']))
{
	$conf['DEV'] = true;	
}

define('DEV', $conf['DEV']);

//$x = Config::read(); 
//TODO: ielasam no DB tabulas config

/* TODO: move uz vietu kur peec db configa ielasiishanasa paress ip adreses
 */
//error settings

if(!$conf['DEV'])
{
	ini_set('display_errors' , 0);
}

//init smarty
include $conf['PATH'] . 'library/3rdpart/smarty/Smarty.class.php';
//global $template;
$template =  new Smarty;
$template->compile_dir = $conf['smarty_compile_dir'];
$template->template_dir = $conf['smarty_templates_dir'];
$template->plugins_dir = array_merge($template->plugins_dir, $conf['smarty_plugins_dir']);


//TODO: detect interface :: default = xhtml;
$interface = 'xhtml'; // XHTML, XML, CLI, MOBILE, WAP

include $conf['smarty_templates_dir'] . $interface . ".php";

$template->display($interface . '.tpl');



//TODO:errorHandler
//include $conf['PATH'] . 'library/ErrroHandler.php';

?>