<?php 

function debug($arg, $die = true)
{
	echo '<pre>'; 
    if(is_null($arg) || empty($arg) || $arg===true ||  $arg===false )
    {
        echo gettype($arg);
    }
    else
    {
        print_r($arg);
    }
    
    echo '</pre>';
	
	if($die == true)
	{
		die();
	}
}


function redirect($url, $code = '301')
{  
    close_db_connection();
	header('Location:'.$url);
    die();
}

function _core_exit($message='')
{
    close_db_connection();
    die($message);
}

function __autoload($class)
{
	global $conf;
	if( isset($conf['INCLUDE_PATHS']) && is_array($conf['INCLUDE_PATHS']) )	
	{
		foreach($conf['INCLUDE_PATHS'] as $path)
		{
			$file = $path . $class . '/class.' . $class . '.php';
			if(file_exists($file))
			{
				include $file;
				return true;
			}
		}
		return false;
	}
}

function isDevMode($ipAddr)
{
	global $conf;
	if(in_array($ipAddr, $conf['DEV_IP']) && !isset($_GET['devModeOff']))
	{
		return true;
	}
	return false;
}


function url($add = array(), $remove=array())
{
	global $conf;
	if(!isset($conf['smarty_plugins_dir'][0]))
	{
		return;
	}
	
	$addString = '';
	foreach($add as $key=>$val)
	{
		$addString .= $key.'='.$val.',';
	}
	if(strlen($addString)>0) 
	{ 
		$addString = substr($addString, 0, -1);
	}
	
	$removeString = '';
	foreach($remove as $key=>$val)
	{
		$removeString .= $key.'='.$val.',';
	}
	if(strlen($removeString)>0) 
	{ 
		$removeString = substr($removeString, 0, -1);
	}
	
	include ($conf['smarty_plugins_dir'][0] . 'function.url.php');
	$params = array('add' => $addString, 'remove' => $removeString);
	return str_replace('&amp;', '&', smarty_function_url($params));
}


function generateFileName($name)
{
	$sufix = strtolower(substr($name, strrpos($name,'.')));
	$newname = date('YmdHis') . '-' . substr(sha1(rand()), 0, 8) . $sufix;
	return $newname;
}


function isPositiveInt($int=null)
{
	if($int!=null && preg_match('#^[1-9]\d{0,9}$#', $int))
	{
	   return true;
	}	
	return false;
}


function get_mem()
{
    return 'Mem: '. memory_get_usage() . ' b';
}


function ErrorHandler($errno, $errstr, $errfile, $errline)
{
	//TODO: implement
/*	
	if($errno == E_USER_ERROR)
	{
		$str = "<b>My ERROR</b> [$errno] $errstr";
        $str .= "  Fatal error on line $errline in file $errfile";
        $str .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
		
	}
	*/
}


?>