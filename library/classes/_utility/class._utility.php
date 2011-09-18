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
		// First, we check that there's one @ symbol, and that the lengths are right 
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) 
		{ 
			// Email invalid because wrong number of characters in one section, or wrong number of @ symbols. 
			return false; 
		} 
		
		// Split it into sections to make life easier 
		$email_array = explode("@", $email); 
		$local_array = explode(".", $email_array[0]); 
		
		for ($i = 0; $i < sizeof($local_array); $i++) 
		{ 
			if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) 
			{ 
				return false; 
			}
		} 
		
		 
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) 
		{ 
			// Check if domain is IP. If not, it should be valid domain name 
			$domain_array = explode(".", $email_array[1]); 
			if (sizeof($domain_array) < 2) 
			{ 
				return false; 
			
			// Not enough parts to domain 
			} 
			
			for ($i = 0; $i < sizeof($domain_array); $i++) 
			{ 
				if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) 
				{ 
					return false; 
				} 
			} 
		} 
		
		return true;     
	
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