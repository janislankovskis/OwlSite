<?php 

include "project/config.php";
include "library/config.php";

require_once PATH . 'library/core/CoreFunctions.php';
require_once PATH . 'library/core/DatabaseFunctions.php';
require_once PATH . 'library/core/Connect.php';

//init smarty
require_once PATH . 'library/3rdpart/smarty/Smarty.class.php';
$smarty = new Smarty;
$smarty->template_dir = $conf['smarty_templates_dir'];
$smarty->compile_dir = $conf['smarty_compile_dir'];
$smarty->plugins_dir = array_merge($smarty->plugins_dir, $conf['smarty_plugins_dir']);

$module = new PublicModule;
$module->content();
$smarty->assign('module', $module);
if($project['html5'] == true)
{
	$template = 'html5';
}
else
{
	$template = 'xhtml';
}
$smarty->display($template . '.tpl');


?>