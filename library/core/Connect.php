<?php 

global $connect;

$connect = @mysql_connect($project['db_host'], $project['db_user'], $project['db_password']);

if(!$connect)
{	
	exit(file_get_contents( PATH . 'library/templates/dbError.tpl'));
}

$selected = mysql_select_db($project['db_name'], $connect); 
if(!$selected)
{
	exit(file_get_contents( PATH . 'library/templates/dbError.tpl'));
}

//mysql_error($connect);

if($conf['SETNAMESUTF8'])
{
	dbExecute('SET NAMES "UTF8"', $connect);	
}



function close_db_connection()
{
    global $connect;
    mysql_close($connect);
    return;
}



?>