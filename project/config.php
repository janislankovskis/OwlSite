<?php 

global $project;
$project = array();

//db link config
$project['db_host'] = 'localhost';
$project['db_user'] = '';
$project['db_password'] = '';
$project['db_name'] = '';

$project['root'] = '';
$project['admin'] = 'admin';
$project['TABLEPREFIX'] = '';

$project['admin_language'] = 'en';


//Google Analytics
//$project['GA'] = 'GA_ID';

//various options
$project['html5'] = true;
//$project['loadswfobject'] = false;
//$project['loadjquery'] = false;

define('TABLEPREFIX', $project['TABLEPREFIX']);

?>