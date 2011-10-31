<?php 

//read config
require_once "../project/config.php";
require_once "../library/config.php";

$conf['ADMIN_WWW'] = WWW . $project['admin'] . '/';

define('ADMIN_WWW', $conf['ADMIN_WWW']);
define('CMSUSER', $conf['cms_session_name']); 

//rewrite smarty smarty_templates_dir for use in admin
$conf['smarty_templates_dir'][] = PATH . $project['admin'] . '/templates/';

require_once PATH . 'library/core/CoreFunctions.php';
require_once PATH . 'library/core/DatabaseFunctions.php';
require_once PATH . 'library/core/Connect.php';

//init smarty
require_once PATH . 'library/3rdpart/smarty/Smarty.class.php';
$smarty = new Smarty;
$smarty = new Smarty;
$smarty->template_dir = $conf['smarty_templates_dir'];
$smarty->compile_dir = $conf['smarty_compile_dir'];
$smarty->plugins_dir = array_merge($smarty->plugins_dir, $conf['smarty_plugins_dir']);

$module = new _adminModule;

$smarty->assign('module', $module);
$smarty->display($module->template . '.tpl');

mysql_close($connect);

?>