<?php 

global $connect;

$connect = mysql_connect($project['db_host'], $project['db_user'], $project['db_password']);
mysql_select_db($project['db_name'], $connect);

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