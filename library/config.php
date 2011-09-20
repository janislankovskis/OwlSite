<?php 

ini_set ('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

date_default_timezone_set('Europe/Riga');

global $conf;

$conf = array();
$conf['DIR'] = $project['root'] . '';
$conf['protocol'] = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, strrpos($_SERVER['SERVER_PROTOCOL'], '/')));
$conf['WWW'] = $conf['protocol'] . '://' .  $_SERVER['HTTP_HOST'] . '/' . $conf['DIR'];
$conf['PATH'] = $_SERVER['DOCUMENT_ROOT'] . '/' . $conf['DIR'];
$conf['ADMIN_PATH'] = $conf['PATH'] . $project['admin'] . '/';

$conf['CACHE_PATH'] = $_SERVER['DOCUMENT_ROOT'] . '/' . $conf['DIR'] . 'cache/';

$conf['INCLUDE_PATHS'] = array(
	$conf['PATH'] . 'library/classes/',
	$conf['PATH'] . 'project/classes/',
);

$conf['DEV_IP'] = array('::1', '127.0.0.1', '78.84.181.21');

$conf['SETNAMESUTF8'] = true;
$conf['CACHEQUERIES'] = true;

$conf['smarty_compile_dir'] = $conf['CACHE_PATH'] . 'templates/';
$conf['smarty_templates_dir'][] = $conf['PATH'] . 'library/templates/';  


$conf['smarty_plugins_dir'] = array (
	$conf['PATH'] . 'library/SmartyPlugins/',
	$conf['PATH'] . 'project/SmartyPlugins/',
);

$conf['cms_session_name'] = 'cmsuser';

define('BASE', '/' . $conf['DIR']);
define('WWW', $conf['WWW']);
define('PATH', $conf['PATH']);
define('ADMIN_PATH', $conf['ADMIN_PATH']);
define('ADMIN_BASE', $project['admin']);

include $conf['PATH'] . 'library/core/CoreFunctions.php';

$conf['DEV'] = isDevMode($_SERVER['REMOTE_ADDR']);
define('DEV', $conf['DEV']);

//favicon
if(file_exists($conf['PATH'] . 'favicon.ico'))
{
	define('FAVICON', true);
}
else
{
	define('FAVICON', false);
}

//set_error_handler(ErrorHandler);

session_start();

?>andler(ErrorHandler);

session_start();

?>