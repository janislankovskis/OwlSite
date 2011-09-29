<?php 
class _utility
{
	
	public static function addslashes_deep($array)
	{
		if(!is_array($array))
		{
			return $array;
		}
		foreach($array as $key=>$val)
		{
			if(is_array($val))
			{
				$array[$key] = self::addslashes_deep($val);
			}
			elseif(is_string($val))
			{
				$array[$key] = addslashes($val);
			}
		}
		
		return $array;
	}
	

	public static function stripslashes_deep($array)
	{
		if(!is_array($array))
		{
			return $array;
		}
		
		foreach($array as $key=>$val)
		{
			if(is_array($val))
			{
				$array[$key] = self::stripslashes_deep($val);
			}
			elseif(is_string($val))
			{
				$array[$key] = stripslashes($val);
			}
			
		}
		
		return $array;
		
	}
	
	
	public static function mb_unserialize($serial_str) 
	{ 
		$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str ); 
		return unserialize($out);
	} 
	
	
	public static function check_email_address($email) 
	{ 
		return filter_var($email, FILTER_VALIDATE_EMAIL);	
	}
	
	public static function generateFileName($name)
    {
        $sufix = self::getSufix($name);
        $newname = date('YmdHis'). '-' . rand(1000, 9999) . $sufix;
        return $newname;
    }
	
	public static function getSufix($name)
    {
        return strtolower(substr($name, strrpos($name,'.')));
    }
	
}

?>