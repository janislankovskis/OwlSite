<?php 

class Config
{
	
	const Table = 'config';
	
	public $ip = '11.22.33.44';
	
	public static function read()
	{
		$config = new Config;
		return $config->ip; 
	}
	
}

?>